<?php
/**
 * User: Andy
 * Date: 23/10/14
 * Time: 16:06
 */

namespace AVCMS\Bundles\CmsFoundation\Subscribers;

use AV\Model\Event\CreateModelEvent;
use AVCMS\Core\Taxonomy\TaxonomyManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TaxonomyInjectorSubscriber implements EventSubscriberInterface
{
    /**
     * @var TaxonomyManager
     */
    private $taxonomyManager;

    public function __construct(TaxonomyManager $taxonomyManager)
    {
        $this->taxonomyManager = $taxonomyManager;
    }

    public function injectTaxonomyManager(CreateModelEvent $event)
    {
        $event->getModel()->setTaxonomyManager($this->taxonomyManager);
    }

    public static function getSubscribedEvents()
    {
        return [
            'model.create' => ['injectTaxonomyManager']
        ];
    }
}