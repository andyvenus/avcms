<?php
/**
 * User: Andy
 * Date: 14/11/14
 * Time: 10:34
 */

namespace AVCMS\Bundles\Users\Form\ChoicesProvider;

use AV\Form\ChoicesProviderInterface;
use AVCMS\Bundles\Users\Model\Permissions;

class PermissionsChoicesProvider implements ChoicesProviderInterface
{
    /**
     * @var Permissions
     */
    protected $permissions;

    public function __construct(Permissions $permissions)
    {
        $this->permissions = $permissions;
    }

    public function getChoices()
    {
        $permissions = $this->permissions->getAll();

        $choices = [];
        /**
         * @var $permission \AVCMS\Bundles\Users\Model\Permission[]
         */
        foreach ($permissions as $permission)
        {
            $choices[$permission->getId()] = $permission->getName();
        }

        return $choices;
    }
}