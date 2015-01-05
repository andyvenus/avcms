<?php
/**
 * User: Andy
 * Date: 05/01/15
 * Time: 15:11
 */

namespace AVCMS\Bundles\Categories\MenuItemType;

use AV\Form\FormBlueprint;
use AV\Model\Model;
use AVCMS\Core\Menu\MenuItem;
use AVCMS\Core\Menu\MenuItemConfigInterface;
use AVCMS\Core\Menu\MenuItemType\MenuItemTypeInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CategoriesMenuItemType implements MenuItemTypeInterface
{
    protected $categoriesModel;

    protected $name;

    protected $description;

    protected $urlGenerator;

    public function __construct(Model $categoriesModel, UrlGeneratorInterface $urlGenerator)
    {
        $this->categoriesModel = $categoriesModel;
        $this->urlGenerator = $urlGenerator;
        $this->name = ucfirst($categoriesModel->getSingular().' Categories');
        $this->description = 'Shows a list of categories for this content type';
    }

    public function getMenuItems(MenuItemConfigInterface $menuItemConfig)
    {
        $categories = $this->categoriesModel->getAll();

        $menuItems = [];
        foreach ($categories as $category) {
            $menuItem = new MenuItem($menuItemConfig);
            $menuItem->setLabel($category->getName());
            $menuItem->setUrl('#');

            $menuItems[] = $menuItem;
        }

        return $menuItems;
    }

    public function getFormFields(FormBlueprint $form)
    {
        // TODO: Implement getFormFields() method.
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
