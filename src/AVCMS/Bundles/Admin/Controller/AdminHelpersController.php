<?php
/**
 * User: Andy
 * Date: 08/06/2014
 * Time: 10:15
 */

namespace AVCMS\Bundles\Admin\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminHelpersController extends Controller
{
    public function usernameSuggestAction(Request $request)
    {
        $query = $request->get('q');

        $users = $this->model('@users');

        if ($request->query->has('id_search')) {
            $matchedUsers[] = $users->getOne($query);
        }
        else {
            $matchedUsers = $users->query()->where('username', 'LIKE', '%'.$query.'%')->get();
        }

        $paired_users = array();
        foreach ($matchedUsers as $user) {
            if (is_object($user)) {
                $paired_users[] = array('id' => $user->getId(), 'text' => $user->getUsername());
            }
        }
        $paired_users[] = ['id' => '0', 'text' => 'None'];

        return new JsonResponse($paired_users);
    }

    public function slugGeneratorAction(Request $request)
    {
        $slugGenerator = $this->container->get('slug.generator');

        $slug = $slugGenerator->slugify($request->get('title'));

        return new JsonResponse(array('slug' => $slug));
    }
} 
