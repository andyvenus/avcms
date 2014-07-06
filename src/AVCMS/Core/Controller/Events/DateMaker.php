<?php
/**
 * User: Andy
 * Date: 11/06/2014
 * Time: 16:00
 */

namespace AVCMS\Core\Controller\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DateMaker implements EventSubscriberInterface
{
    public function setDates($event)
    {
        $entity = $event->getEntity();

        if (!is_callable(array($entity, 'getId'))) {
            return;
        }

        $date_time = new \DateTime();
        $date = $date_time->getTimestamp();

        if ($entity->getId() === null && is_callable(array($entity, 'setDateAdded')) && is_callable(array($entity, 'getDateAdded')) && $entity->getDateAdded() === null) {
            $entity->setDateAdded($date);
        }

        if ($entity->getId() !== null && is_callable(array($entity, 'setDateEdited'))) {
            $entity->setDateEdited($date);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'admin.controller.filter.entity' => array('setDates', 8),
        );
    }
} 