<?php
/**
 * User: Andy
 * Date: 29/10/14
 * Time: 14:31
 */

namespace AVCMS\Bundles\Users\TwigExtension;

use AVCMS\Bundles\Users\Model\User;
use Symfony\Component\HttpFoundation\RequestStack;

class UserAvatarTwigExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    protected $siteUrl;

    protected $avatarPath;

    public function __construct($avatarPath, RequestStack $requestStack)
    {
        $this->request = $requestStack->getMasterRequest();

        $this->siteUrl = $this->request->getUriForPath('/');

        $basename = basename($this->siteUrl);

        if (strpos($basename, '.php') !== false) {
            $this->siteUrl = str_replace($basename, '', $this->siteUrl);
        }

        $this->avatarPath = $this->siteUrl.$avatarPath;
    }

    public function getName()
    {
        return 'avcms_user_avatar';
    }

    public function getFunctions()
    {
        return array(
            'avatar_url' => new \Twig_SimpleFunction('avatar_url',
                array($this, 'userAvatarUrl'),
                array('is_safe' => array('html')
                )
            ),
            'cover_url' => new \Twig_SimpleFunction('cover_url',
                array($this, 'userCoverUrl'),
                array('is_safe' => array('html')
                )
            )
        );
    }

    public function userAvatarUrl(User $user)
    {
        $avatar = $user->getAvatar();

        if (!$avatar) {
            return $this->siteUrl.'web/resources/Users/images/default_avatar.png';
        }

        return $this->avatarPath.'/'.$avatar . '?x=' . $user->getLastProfileUpdate();
    }

    public function userCoverUrl(User $user)
    {
        $cover = $user->getCoverImage();

        if (!$cover) {
            return $this->siteUrl.'web/resources/Users/images/default_cover.png';
        }

        return $this->avatarPath.'/'.$cover;
    }
}
