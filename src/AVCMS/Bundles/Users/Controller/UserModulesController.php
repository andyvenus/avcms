<?php
/**
 * User: Andy
 * Date: 30/09/2014
 * Time: 20:15
 */

namespace AVCMS\Bundles\Users\Controller;

use AVCMS\Bundles\Users\Model\User;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserModulesController extends Controller
{
    public function userInfoModule(User $user)
    {
        return new Response($this->render('@Users/user_info_module.twig', ['profile_user' => $user]));
    }
} 