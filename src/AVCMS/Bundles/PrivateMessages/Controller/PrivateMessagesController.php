<?php
/**
 * User: Andy
 * Date: 22/02/15
 * Time: 13:39
 */

namespace AVCMS\Bundles\PrivateMessages\Controller;

use AV\Form\FormError;
use AVCMS\Bundles\PrivateMessages\Form\PrivateMessageForm;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class PrivateMessagesController extends Controller
{
    /**
     * @var \AVCMS\Bundles\PrivateMessages\Model\PrivateMessages
     */
    private $messages;

    public function setUp()
    {
        $this->messages = $this->model('PrivateMessages');
        $this->messages->setUsers($this->model('Users'));

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new InsufficientAuthenticationException;
        }

        if (!$this->isGranted('PERM_PRIVATE_MESSAGES')) {
            throw new AccessDeniedException;
        }
    }

    public function inboxAction()
    {
        $messages = $this->messages->getUserMessages($this->activeUser()->getId(), $this->model('Users'));

        return new Response($this->render('@PrivateMessages/inbox.twig', ['messages' => $messages]));
    }

    public function deleteAction(Request $request)
    {
        if (!$this->checkCsrfToken($request)) {
            throw new InvalidCsrfTokenException;
        }

        $this->messages->query()
            ->where('recipient_id', $this->activeUser()->getId())
            ->whereIn('id', $request->get('ids'))
            ->delete();

        $unread = $this->messages->updateMessageCount($this->activeUser()->getId());

        return new JsonResponse(['success' => true, 'unread' => $unread]);
    }

    public function toggleReadAction(Request $request)
    {
        if (!$this->checkCsrfToken($request)) {
            throw new InvalidCsrfTokenException;
        }

        $readStatus = 0;
        if ($request->get('read') == 1) {
            $readStatus = 1;
        }

        $unread = $this->messages->toggleRead($request->get('ids'), $this->activeUser()->getId(), $readStatus);

        return new JsonResponse(['success' => true, 'unread' => $unread]);
    }

    public function readAction(Request $request)
    {
        $message = $this->messages->getMessage($this->activeUser()->getId(), $request->get('id'));

        if (!$message) {
            throw $this->createNotFoundException();
        }

        $this->messages->markMessageRead($message, $this->activeUser());

        return new Response($this->render('@PrivateMessages/read_message.twig', ['message' => $message]));
    }

    public function sendAction(Request $request)
    {
        $replyMessage = null;
        if ($request->get('reply')) {
            $replyMessage = $this->messages->getMessage($this->activeUser()->getId(), $request->get('reply'));

            if (!$replyMessage) {
                throw $this->createNotFoundException();
            }

            $subject = "Re: ".$replyMessage->getSubject();
            $recipientId = $replyMessage->getSenderId();
        }
        elseif ($request->get('recipient')) {
            $subject = null;
            $recipientId = $request->get('recipient');
        }


        if (!isset($recipientId) || ($recipient = $this->model('Users')->getOne($recipientId)) === null) {
            throw $this->createNotFoundException();
        }

        $message = $this->messages->newEntity();

        /** @noinspection PhpUndefinedVariableInspection */
        $form = $this->buildForm(new PrivateMessageForm($subject), $request, $message);

        $floodControlTime = $this->activeUser()->group->getFloodControlTime();
        if ($form->isSubmitted() && $this->get('session')->get('avcms_last_pm', 0) > time() - $floodControlTime) {
            $form->addCustomErrors([new FormError(null, 'Please wait at least {seconds} seconds between sending messages', true, ['seconds' => $floodControlTime])]);
        }

        if ($form->isValid()) {
            $form->saveToEntities();

            $message->setSenderId($this->activeUser()->getId());
            $message->setIp($request->getClientIp());
            $message->setRecipientId($recipientId);
            $message->setDate(time());

            $this->messages->save($message);
            $this->messages->updateMessageCount($recipientId);

            $this->get('session')->set('avcms_last_pm', time());

            if ($recipient->getReceiveEmails() && $recipient->getEmail()) {
                $mailer = $this->container->get('mailer');
                $email = $mailer->newEmail(
                    $this->trans('You received a new private message'),
                    $this->render(
                        "@PrivateMessages/email/email.new_pm.twig",
                        ['message' => $message, 'sender' => $this->activeUser(), 'recipient' => $recipient]
                    )
                );
                $email->setTo($recipient->getEmail());
                $mailer->send($email);
            }

            return $this->redirect('private_messages_inbox', [], 302, 'info', $this->trans('Message Sent'));
        }

        return new Response($this->render('@PrivateMessages/send_message.twig', ['form' => $form->createView(), 'recipient' => $recipient, 'reply_message' => $replyMessage]));
    }
}
