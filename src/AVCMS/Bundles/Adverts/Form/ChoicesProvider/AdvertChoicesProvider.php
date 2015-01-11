<?php
/**
 * User: Andy
 * Date: 11/01/15
 * Time: 14:06
 */

namespace AVCMS\Bundles\Adverts\Form\ChoicesProvider;

use AV\Form\ChoicesProviderInterface;
use AV\Model\Model;

class AdvertChoicesProvider implements ChoicesProviderInterface
{
    private $advertsModel;

    public function __construct(Model $advertsModel)
    {
        $this->advertsModel = $advertsModel;
    }

    public function getChoices()
    {
        $ads = $this->advertsModel->getAll();
        $choices = [];
        foreach ($ads as $ad) {
            $choices[$ad->getId()] = $ad->getName();
        }

        return $choices;
    }
}
