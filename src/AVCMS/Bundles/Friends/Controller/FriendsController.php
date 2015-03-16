<?php
/**
 * User: Andy
 * Date: 15/03/15
 * Time: 15:25
 */

namespace AVCMS\Bundles\Friends\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FriendsController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Friends\Model\FriendRequests
     */
    private $friendRequests;

    /**
     * @var \AVCMS\Bundles\Friends\Model\Friends
     */
    private $friends;

    public function setUp()
    {
        $this->friends = $this->model('Friends');
        $this->friendRequests = $this->model('FriendRequests');

        if (!$this->setting('enable_friends')) {
            throw $this->createNotFoundException();
        }

        if (!$this->userLoggedIn()) {
            throw new AccessDeniedException;
        }
    }

    public function friendsAction()
    {
        $friendRequests = $this->friendRequests->getFriendRequests($this->activeUser()->getId(), $this->model('Users'));
        $friends = $this->friends->getUsersFriends($this->activeUser()->getId(), $this->model('Users'));

        return new Response($this->render('@Friends/friends.twig', ['requests' => $friendRequests, 'friends' => $friends]));
    }

    public function sendFriendRequestAction(Request $request)
    {
        $receiver = $this->model('Users')->getOne($request->get('user'));

        if (!$receiver) {
            throw $this->createNotFoundException('User not found');
        }

        if ($this->friendRequests->requestExists($this->activeUser()->getId(), $receiver->getId())) {
            return new JsonResponse(['success' => false, 'message' => $this->trans('Friend request already sent')]);
        }

        $friendRequest = $this->friendRequests->newEntity();
        $friendRequest->setSenderId($this->activeUser()->getId());
        $friendRequest->setReceiverId($receiver->getId());
        $friendRequest->setDate(time());

        $this->friendRequests->save($friendRequest);

        if ($receiver->getReceiveEmails()) {
            $mailer = $this->get('mailer');
            $email = $mailer->newEmail($this->trans('New Friend Request'), $this->render('@Friends/email/email.friend_request.twig', ['sender' => $this->activeUser()]));
            $email->setTo($receiver->getEmail());
            $mailer->send($email);
        }

        return new JsonResponse(['success' => true, 'message' => $this->trans('Friend request sent')]);
    }

    public function cancelFriendRequestAction(Request $request)
    {
        if (!$request->get('user')) {
            return new JsonResponse(['success' => false, 'message' => 'No user ID specified']);
        }

        $this->friendRequests->deleteRequest($this->activeUser()->getId(), $request->get('user'));

        return new JsonResponse(['success' => true, 'message' => $this->trans('Request Cancelled')]);
    }

    public function acceptFriendRequestAction(Request $request)
    {
        $friendRequest = $this->friendRequests->getRequest($request->get('user'), $this->activeUser()->getId());

        if (!$friendRequest) {
            return new JsonResponse(['success' => false, 'message' => 'Friend request not found']);
        }

        $friendship = $this->friends->newEntity();
        $friendship->setUser1($this->activeUser()->getId());
        $friendship->setUser2($friendRequest->getSenderId());

        $this->friends->insert($friendship);

        $this->friendRequests->deleteRequest($request->get('user'), $this->activeUser()->getId());

        return new JsonResponse(['success' => true, 'message' => $this->trans('Friend request accepted')]);
    }

    public function declineFriendRequestAction(Request $request)
    {
        $this->friendRequests->deleteRequest($request->get('user'), $this->activeUser()->getId());

        return new JsonResponse(['success' => true, 'message' => $this->trans('Friend request declined')]);
    }

    public function removeFriendAction(Request $request)
    {
        $this->friends->removeFriendship($this->activeUser()->getId(), $request->get('user'));

        return new JsonResponse(['success' => true, 'message' => $this->trans('Friend removed')]);
    }
}
