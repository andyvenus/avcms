<?php
/**
 * User: Andy
 * Date: 07/02/15
 * Time: 10:08
 */

namespace AVCMS\Bundles\Adverts\TwigExtension;

use AVCMS\Bundles\Adverts\Model\Adverts;

class AdvertsTwigExtension extends \Twig_Extension
{
    private $adverts;

    private $cache = [];

    public function __construct(Adverts $adverts)
    {
        $this->adverts = $adverts;
    }

    public function advert($advertId)
    {
        if (isset($this->cache[$advertId])) {
            return $this->cache[$advertId];
        }

        $advert = $this->adverts->getOne($advertId);

        if (!$advert) {
            return;
        }

        return $this->cache[$advertId] = $advert->getContent();
    }

    public function getFunctions()
    {
        return [
            'advert' => new \Twig_SimpleFunction(
                'advert',
                array($this, 'advert'),
                array('is_safe' => array('html'))
            )
        ];
    }

    public function getName()
    {
        return 'avcms_adverts';
    }
}
