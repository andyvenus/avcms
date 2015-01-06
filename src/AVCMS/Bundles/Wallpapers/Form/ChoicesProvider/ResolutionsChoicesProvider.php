<?php
/**
 * User: Andy
 * Date: 06/01/15
 * Time: 19:36
 */

namespace AVCMS\Bundles\Wallpapers\Form\ChoicesProvider;

use AV\Form\ChoicesProviderInterface;
use AVCMS\Bundles\Wallpapers\ResolutionsManager\ResolutionsManager;

class ResolutionsChoicesProvider implements ChoicesProviderInterface
{
    private $resolutionsManager;

    public function __construct(ResolutionsManager $resolutionsManager)
    {
        $this->resolutionsManager = $resolutionsManager;
    }

    public function getChoices()
    {
        return $this->resolutionsManager->getAllResolutions();
    }
}
