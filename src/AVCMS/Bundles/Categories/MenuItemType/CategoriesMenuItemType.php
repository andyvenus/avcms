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

    protected $name = 'Content Categories';

    protected $description = 'Shows a list of categories for this content type';

    protected $urlGenerator;

    protected $categoryRoute;

    public function __construct(Model $categoriesModel, UrlGeneratorInterface $urlGenerator, $categoryRoute)
    {
        $this->categoriesModel = $categoriesModel;
        $this->urlGenerator = $urlGenerator;
        $this->categoryRoute = $categoryRoute;
    }

    public function getMenuItems(MenuItemConfigInterface $menuItemConfig)
    {
        if ($menuItemConfig->getSetting('sub_categories') == 1) {
            $categories = $this->categoriesModel->getAllCategories();
        }
        else {
            $categories = $this->categoriesModel->getParentCategories();
        }

        $menuItems = [];

        if ($menuItemConfig->getSetting('display') === 'child') {
            $menuItem = new MenuItem();
            $menuItem->fromArray($menuItemConfig->toArray(), true);
            $menuItem->setUrl('#');

            $menuItems[] = $menuItem;
        }

        foreach ($categories as $category) {
            $menuItem = new MenuItem();
            $menuItem->setId('category-'.$category->getId());
            if ($category->getParent()) {
                $menuItem->setParent('category-'.$category->getParent());
            }
            $menuItem->setLabel($category->getName());
            $menuItem->setUrl($this->urlGenerator->generate($this->categoryRoute, ['category' => $category->getSlug()]));

            if ($menuItemConfig->getSetting('display') === 'child' && $menuItem->getParent() === null) {
                $menuItem->setParent($menuItemConfig->getId());
            }

            $menuItems[] = $menuItem;
        }

        return $menuItems;
    }

    public function getFormFields(FormBlueprint $form)
    {
        $form->add('settings[display]', 'select', [
            'label' => 'Categories display Type',
            'choices' => [
                'inline' => 'Inline (categories items appear in the main menu)',
                'child' => 'Child (categories appear under this menu item, must not be nested)'
            ]
        ]);

        $form->add('settings[sub_categories]', 'checkbox', [
            'label' => 'Show Sub-Categories',
            'default' => 1
        ]);
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
