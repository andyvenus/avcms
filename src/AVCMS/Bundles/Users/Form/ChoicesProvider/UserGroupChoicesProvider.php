<?php
/**
 * User: Andy
 * Date: 14/01/15
 * Time: 22:30
 */

namespace AVCMS\Bundles\Users\Form\ChoicesProvider;

use AV\Form\ChoicesProviderInterface;
use AVCMS\Bundles\Users\Model\UserGroups;

class UserGroupChoicesProvider implements ChoicesProviderInterface
{
    protected $userGroups;

    public function __construct(UserGroups $userGroups)
    {
        $this->userGroups = $userGroups;
    }

    public function getChoices()
    {
        $userGroups = $this->userGroups->getAll();

        $choices = [];
        foreach ($userGroups as $userGroup) {
            $choices[$userGroup->getId()] = $userGroup->getName();
        }

        return $choices;
    }
}
