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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PrivateMessagesController extends Controller
{
    /**
     * @var \AVCMS\Bundles\PrivateMessages\Model\PrivateMessages
     */
    private $messages;

    public function setUp()
    {
        $this->messages = $this->model('PrivateMessages');

        if (!$this->userLoggedIn()) {
            throw new AccessDeniedException;
        }
    }

    public function inboxAction()
    {
        $messages = $this->messages->getUserMessages($this->activeUser()->getId(), $this->model('Users'));

        return new Response($this->render('@PrivateMessages/inbox.twig', ['messages' => $messages]));
    }

    public function readAction(Request $request)
    {
        $message = $this->messages->getMessage($this->activeUser()->getId(), $request->get('id'), $this->model('Users'));

        if (!$message) {
            throw $this->createNotFoundException();
        }

        $this->messages->markMessageRead($message);

        return new Response($this->render('@PrivateMessages/read_message.twig', ['message' => $message]));
    }

    public function sendAction(Request $request)
    {
        $replyMessage = null;
        if ($request->get('reply')) {
            $replyMessage = $this->messages->getMessage($this->activeUser()->getId(), $request->get('reply'), $this->model('Users'));

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

            $this->get('session')->set('avcms_last_pm', time());

            return $this->redirect('private_messages_inbox', [], 302, 'info', $this->trans('Message Sent'));
        }

        return new Response($this->render('@PrivateMessages/send_message.twig', ['form' => $form->createView(), 'recipient' => $recipient, 'reply_message' => $replyMessage]));
    }
}
