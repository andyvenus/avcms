<?php
/**
 * User: Andy
 * Date: 30/06/2014
 * Time: 19:01
 */

namespace AVCMS\Bundles\Tags\Events;

use AVCMS\Core\Controller\Event\AdminSaveContentEvent;
use AVCMS\Core\Taxonomy\TaxonomyManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdateTags implements EventSubscriberInterface
{
    protected $taxonomy;

    public function __construct(TaxonomyManager $taxonomy_manager)
    {
        $this->taxonomy = $taxonomy_manager;
    }

    public function updateTags(AdminSaveContentEvent $event)
    {
        $form = $event->getForm();

        if ($tags = $form->getData('tags')) {
            $tags = explode(',', $tags);
            $this->taxonomy->update('tags', $event->getEntity()->getId(), $event->getModel()->getSingular(), $tags);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'admin.save.content' => array('updateTags')
        );
    }
}