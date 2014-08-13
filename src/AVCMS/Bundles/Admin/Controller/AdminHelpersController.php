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
            $matched_users[] = $users->getOne($query);
        }
        else {
            $matched_users = $users->query()->where('username', 'LIKE', '%'.$query.'%')->get();
        }

        $paired_users = array();
        foreach ($matched_users as $user) {
            $paired_users[] = array('id' => $user->getId(), 'text' => $user->getUsername());
        }

        return new JsonResponse($paired_users);
    }

    public function slugGeneratorAction(Request $request)
    {
        $slug_generator = $this->container->get('slug.generator');

        $slug = $slug_generator->generate($request->get('title'));

        return new JsonResponse(array('slug' => $slug));
    }
} 