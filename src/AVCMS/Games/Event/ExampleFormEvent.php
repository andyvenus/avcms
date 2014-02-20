<?php
/**
 * User: Andy
 * Date: 13/02/2014
 * Time: 13:53
 */

namespace AVCMS\Games\Event;

use AVCMS\Core\Form\Event\FormHandlerConstructEvent;
use AVCMS\Core\Form\Event\FormHandlerRequestEvent;
use AVCMS\Core\Model\Event\CreateModelEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExampleFormEvent implements EventSubscriberInterface
{
    public function exampleThing(FormHandlerConstructEvent $event)
    {
        $blueprint = $event->getFormBlueprint();

        if ($blueprint->getName() == 'blog_post_form') {
            $blueprint->add('something', 'text', array(
                'label' => "WOOT",
            ));
        }

        $handler = $event->getFormHandler();
    }

    public function reqThing(FormHandlerRequestEvent $event)
    {
        //$data = $event->getFormData();
        //$data['nice'] = 'Cups';
        //$event->setFormData($data);
    }

    public function entityExtension(CreateModelEvent $event)
    {
        $model = $event->getModel();

        if (get_class($model) == 'AVCMS\Games\Model\Games' || get_class($model) == 'AVCMS\Blog\Model\Posts') {
            $model->addOverflowEntity('testone', 'AVCMS\Games\Model\GameExtension');
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'form_handler.construct' => 'exampleThing',
            'form_handler.request' => 'reqThing',
            'model.create' => 'entityExtension'
        );
    }
}