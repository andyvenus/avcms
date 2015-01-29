<?php
/**
 * User: Andy
 * Date: 05/01/15
 * Time: 15:11
 */

namespace AVCMS\Bundles\Wallpapers\MenuItemType;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\Wallpapers\ResolutionsManager\ResolutionsManager;
use AVCMS\Core\Menu\MenuItem;
use AVCMS\Core\Menu\MenuItemConfigInterface;
use AVCMS\Core\Menu\MenuItemType\MenuItemTypeInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WallpaperResolutionsMenuItemType implements MenuItemTypeInterface
{
    protected $resolutionsManager;

    protected $name = 'Wallpaper Resolutions';

    protected $description = 'Adds menu items for each Wallpaper resolution that is available';

    protected $urlGenerator;

    protected $resolutionRoute;

    public function __construct(ResolutionsManager $resolutionsManager, UrlGeneratorInterface $urlGenerator, $resolutionRoute)
    {
        $this->resolutionsManager = $resolutionsManager;
        $this->urlGenerator = $urlGenerator;
        $this->resolutionRoute = $resolutionRoute;
    }

    public function getMenuItems(MenuItemConfigInterface $menuItemConfig)
    {
        $allRes = $this->resolutionsManager->getAllResolutions();

        $menuItems = [];

        if ($menuItemConfig->getSetting('display') === 'child') {
            $menuItem = new MenuItem();
            $menuItem->fromArray($menuItemConfig->toArray(), true);
            $menuItem->setUrl('#');

            $menuItems[] = $menuItem;
        }

        foreach ($allRes as $resCat => $resolutions) {
            foreach ($resolutions as $resolutionId => $resolutionName) {
                $menuItem = new MenuItem();
                $menuItem->setId('resolution-' . $resCat . '-' . $resolutionId);
                $menuItem->setLabel($resolutionName);
                $menuItem->setUrl($this->urlGenerator->generate($this->resolutionRoute, ['resolution' => $resolutionId]));

                if ($menuItemConfig->getSetting('display') === 'child') {
                    $menuItem->setParent($menuItemConfig->getId());
                }

                $menuItems[] = $menuItem;
            }
        }

        return $menuItems;
    }

    public function getFormFields(FormBlueprint $form)
    {
        $form->add('settings[display]', 'select', [
            'label' => 'Resolutions Display Type',
            'choices' => [
                'inline' => 'Inline (resolutions items appear in the main menu)',
                'child' => 'Child (resolutions appear under this menu item, must not be nested)'
            ]
        ]);
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
