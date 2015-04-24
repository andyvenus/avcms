<?php
/**
 * User: Andy
 * Date: 21/02/15
 * Time: 11:43
 */

namespace AVCMS\Bundles\Points\EventSubscriber;

use AV\Form\Event\FormHandlerConstructEvent;
use AVCMS\Bundles\Users\Form\UserAdminForm;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PointsExtendUserFormSubscriber implements EventSubscriberInterface
{
    public function addPointsField(FormHandlerConstructEvent $event)
    {
        $form = $event->getFormBlueprint();

        if (!$form instanceof UserAdminForm) {
            return;
        }

        $form->add('points', 'text', [
            'label' => 'Points',
            'validation' => [
                ['rule' => 'Numeric']
            ]
        ]);
    }

    public static function getSubscribedEvents()
    {
        return [
            'form_handler.construct' => 'addPointsField'
        ];
    }
}
