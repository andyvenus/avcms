<?php
/**
 * User: Andy
 * Date: 23/01/15
 * Time: 14:43
 */

namespace AVCMS\Bundles\Pages\MenuItem;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\Pages\Model\Pages;
use AVCMS\Core\Menu\MenuItem;
use AVCMS\Core\Menu\MenuItemConfigInterface;
use AVCMS\Core\Menu\MenuItemType\MenuItemTypeInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PageMenuItemType implements MenuItemTypeInterface
{
    protected $pages;

    protected $urlGenerator;

    public function __construct(Pages $pages, UrlGeneratorInterface $urlGenerator)
    {
        $this->pages = $pages;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param MenuItemConfigInterface $menuItemConfig
     * @return MenuItem[]|MenuItem
     */
    public function getMenuItems(MenuItemConfigInterface $menuItemConfig)
    {
        $pageId = $menuItemConfig->getSetting('page_id');

        $page = $this->pages->getOne($pageId);

        if (!$page || !$page->getPublished()) {
            return [];
        }

        $menuItem = new MenuItem();
        $menuItem->fromArray($menuItemConfig->toArray(), true);

        $menuItem->setUrl($this->urlGenerator->generate('page', ['slug' => $page->getSlug()]));

        return $menuItem;
    }

    public function getFormFields(FormBlueprint $form)
    {
        $pages = $this->pages->getAll();

        $pageChoices = [];
        foreach ($pages as $page) {
            $pageChoices[$page->getId()] = $page->getTitle();
        }

        $form->add('settings[page_id]', 'select', [
            'label' => 'Page',
            'choices' => $pageChoices
        ]);
    }

    public function getName()
    {
        return 'Page';
    }

    public function getDescription()
    {
        return 'A HTML page from the pages section';
    }
}
