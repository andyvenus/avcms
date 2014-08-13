<?php
/**
 * User: Andy
 * Date: 30/06/2014
 * Time: 19:01
 */

namespace AVCMS\Bundles\Tags\Events;

use AVCMS\Bundles\Admin\Event\AdminEditFormBuiltEvent;
use AVCMS\Bundles\Admin\Event\AdminSaveContentEvent;
use AVCMS\Core\Taxonomy\TaxonomyManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UpdateTags
 * @package AVCMS\Bundles\Tags\Events
 *
 * Responds to events in the save content controller to retrieve, display &
 * save tags taxonomy to/from the form
 */
class UpdateTags implements EventSubscriberInterface
{
    /**
     * @var \AVCMS\Core\Taxonomy\TaxonomyManager
     */
    protected $taxonomy;

    /**
     * @param TaxonomyManager $taxonomy_manager
     */
    public function __construct(TaxonomyManager $taxonomy_manager)
    {
        $this->taxonomy = $taxonomy_manager;
    }

    /**
     * If the edit content form has a 'tags' field, get the tags from the database
     * and set the field value to a comma delimited list of them
     *
     * @param AdminEditFormBuiltEvent $event
     */
    public function getTags(AdminEditFormBuiltEvent $event)
    {
        $form = $event->getForm();
        $entity = $event->getEntity();

        if ($form->hasFieldWithName('tags') && $form->getData('tags') === null && method_exists($entity, 'getId') && $entity->getId()) {
            $tags = $this->taxonomy->get('tags', $entity->getId(), $event->getModel()->getSingular());
            $tag_names = array();
            foreach($tags as $tag) {
                $tag_names[] = $tag->getName();
            }

            $form->setData('tags', implode(',', $tag_names));
        }
    }

    /**
     * Get the tags from the content form and save them
     *
     * @param AdminSaveContentEvent $event
     */
    public function updateTags(AdminSaveContentEvent $event)
    {
        $form = $event->getForm();

        if ($tags = $form->getData('tags')) {
            $tags = explode(',', $tags);
            $this->taxonomy->update('tags', $event->getEntity()->getId(), $event->getModel()->getSingular(), $tags);
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'admin.edit.form.built' => array('getTags'),
            'admin.save.content' => array('updateTags')
        );
    }
}