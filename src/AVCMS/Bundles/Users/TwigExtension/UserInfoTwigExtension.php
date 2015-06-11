<?php
/**
 * User: Andy
 * Date: 11/11/14
 * Time: 13:53
 */

namespace AVCMS\Bundles\Users\TwigExtension;

use AVCMS\Bundles\Users\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserInfoTwigExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getGlobals()
    {
        $token = $this->tokenStorage->getToken();

        if (is_callable([$token, 'getUser'])) {
            $user = $token->getUser();
        }
        else {
            $user = null;
        }

        return [
            'user' => $user
        ];
    }

    public function getFunctions()
    {
        return array(
            'user_info' => new \Twig_SimpleFunction('user_info',
                array($this, 'userInfo'),
                array('is_safe' => array('html')
                )
            ),
        );
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function userInfo($user, $options = array())
    {
        if (!$user) {
            return null;
        }

        return $this->environment->render('@Users/user_info.twig', ['info_user' => $user, 'options' => $this->mergeDefaultOptions($options)]);
    }

    private function mergeDefaultOptions($options)
    {
        $defaults = [
            'avatar_width' => 25,
            'avatar_height' => 25,
            'active_user' => false,
            'show_avatar' => true,
            'show_username' => true,
        ];

        return array_replace_recursive($defaults, $options);
    }

    public function getName()
    {
        return 'avcms_user_info';
    }
}
