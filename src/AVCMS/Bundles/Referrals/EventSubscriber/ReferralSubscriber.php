<?php
/**
 * User: Andy
 * Date: 20/01/15
 * Time: 19:16
 */

namespace AVCMS\Bundles\Referrals\EventSubscriber;

use AVCMS\Bundles\Referrals\Model\Referral;
use AVCMS\Bundles\Referrals\Model\Referrals;
use AVCMS\Bundles\Users\Event\CreateUserEvent;
use AVCMS\Bundles\Users\Model\Users;
use AVCMS\Core\HitCounter\HitCounter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ReferralSubscriber implements EventSubscriberInterface
{
    protected $referrals;

    protected $users;

    protected $hitCounter;

    protected $session;

    public function __construct(Referrals $referrals, Users $users, HitCounter $hitCounter, SessionInterface $session)
    {
        $this->referrals = $referrals;
        $this->users = $users;
        $this->hitCounter = $hitCounter;
        $this->session = $session;
    }

    public function checkReferral(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->query->has('ref_id')) {
            $refId = $request->query->get('ref_id');
            $referral = $this->referrals->getOne($refId);

            if ($referral === null) {
                return;
            }
        }
        elseif ($request->query->has('ref_user_id')) {
            $userId = $request->query->get('ref_user_id');
            $referral = $this->referrals->getUserReferral($userId);

            if ($referral === null) {
                $user = $this->users->getOne($userId);
                if ($user === null) {
                    return;
                }

                $referral = new Referral();
                $referral->setUserId($user->getId());
                $referral->setName($user->getUsername());
                $referral->setType('user');
                $referral->setLastReferral(time());
                $this->referrals->save($referral);
            }
        }
        else {
            return;
        }

        if ($this->hitCounter->registerHit($this->referrals, $referral->getId(), 'inbound')) {
            $referral->setLastReferral(time());
            $referral->setInbound($referral->getInbound() + 1);
            $this->referrals->save($referral);
        }

        $this->session->set('ref_id', $referral->getId());
    }

    public function checkRegistration(CreateUserEvent $event)
    {
        if ($this->session->has('ref_id')) {
            $this->referrals->increaseConversions($this->session->get('ref_id'));
            $event->getUser()->referral->setReferral($this->session->get('ref_id'));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['checkReferral'],
            'user.create' => ['checkRegistration'],
        ];
    }
}
