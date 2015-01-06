<?php
/**
 * User: Andy
 * Date: 06/01/15
 * Time: 14:51
 */

namespace AVCMS\Bundles\CmsFoundation\Form\ChoicesProvider;

use AV\Form\ChoicesProviderInterface;
use AVCMS\Bundles\CmsFoundation\Model\Menus;

class MenuChoicesProvider implements ChoicesProviderInterface
{
    private $menus;

    public function __construct(Menus $menus)
    {
        $this->menus = $menus;
    }

    public function getChoices()
    {
        $menus = $this->menus->getAll();

        $choices = [];
        foreach ($menus as $menu) {
            $choices[$menu->getId()] = $menu->getLabel();
        }

        return $choices;
    }
}
