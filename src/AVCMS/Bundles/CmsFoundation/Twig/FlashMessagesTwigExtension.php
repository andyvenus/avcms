<?php
/**
 * User: Andy
 * Date: 07/12/14
 * Time: 12:40
 */

namespace AVCMS\Bundles\CmsFoundation\Twig;

use Symfony\Component\HttpFoundation\Session\Session;

class FlashMessagesTwigExtension extends \Twig_Extension
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var MessagesProviderInterface[]
     */
    private $providers = [];

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getMessages()
    {
        $flashes = $this->session->getFlashBag()->all();

        $messages = [];
        foreach ($flashes as $type => $typeMessages) {
            foreach ($typeMessages as $message) {
                $messages[] = ['type' => $type, 'message' => $message];
            }
        }

        foreach ($this->providers as $provider) {
            if ($providerMessages = $provider->getMessages()) {
                $messages = array_merge($messages, $providerMessages);
            }
        }

        return $messages;
    }

    public function addMessagesProvider(MessagesProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    public function getFunctions()
    {
        return array(
            'get_messages' => new \Twig_SimpleFunction('get_messages',
                array($this, 'getMessages')
            )
        );
    }

    public function getName()
    {
        return 'avcms_flash_messages';
    }
}
