<?php
/**
 * User: Andy
 * Date: 18/03/15
 * Time: 18:37
 */

namespace AVCMS\Bundles\Users\Controller;

use AVCMS\Bundles\Users\Form\MemberListSearchForm;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberListController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Users\Model\Users
     */
    protected $users;

    public function setUp()
    {
        $this->users = $this->model('Users');
    }

    public function memberListAction(Request $request)
    {
        $finder = $this->users->find();
        $users = $finder->setSearchFields(array('username', 'email'))
            ->setResultsPerPage(32)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null, 'group' => null))
            ->get();

        $formBlueprint = new MemberListSearchForm();

        $attr = $request->attributes->all();
        $attr['page'] = 1;
        $formBlueprint->setAction($this->generateUrl('member_list', $attr));
        $form = $this->buildForm($formBlueprint, $request);

        return new Response($this->render('@Users/member_list.twig', [
            'users' => $users,
            'form' => $form->createView(),
            'total_pages' => $finder->getTotalPages(),
            'current_page' => $finder->getCurrentPage()
        ]));
    }
}
