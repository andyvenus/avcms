<?php
/**
 * User: Andy
 * Date: 12/01/15
 * Time: 15:41
 */

namespace AVCMS\Bundles\FacebookConnect;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FacebookRedirectLoginHelper extends \Facebook\FacebookRedirectLoginHelper
{
    /**
     * @var SessionInterface
     */
    protected $session;

    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Stores a state string in session storage for CSRF protection.
     * Developers should subclass and override this method if they want to store
     *   this state in a different location.
     *
     * @param string $state
     *
     * @throws \Exception
     */
    protected function storeState($state)
    {
        if (!isset($this->session)) {
            throw new \Exception('Session not set');
        }

        $this->session->set('FBRLH_state', $state);
        $this->session->save();
    }

    /**
     * Loads a state string from session storage for CSRF validation.  May return
     *   null if no object exists.  Developers should subclass and override this
     *   method if they want to load the state from a different location.
     *
     * @return string|null
     *
     * @throws \Exception
     */
    protected function loadState()
    {
        if (!isset($this->session)) {
            throw new \Exception('Session not set');
        }

        if ($this->session->has('FBRLH_state')) {
            return $this->state = $this->session->get('FBRLH_state');
        }

        return null;
    }
}
