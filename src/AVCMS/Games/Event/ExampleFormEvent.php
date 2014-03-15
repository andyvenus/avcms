<?php
/**
 * User: Andy
 * Date: 13/02/2014
 * Time: 13:53
 */

namespace AVCMS\Games\Event;

use AVCMS\Core\Database\Events\QueryBuilderModelJoinEvent;
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

        if (get_class($model) == 'AVCMS\Games\Model\Games' || get_class($model) == 'AVCMS\Bundles\Blog\Model\Posts' || get_class($model) == 'AVCMS\Games\Model\Categories') {
            $model->addOverflowEntity('testone', 'AVCMS\Games\Model\GameExtension');
        }
    }

    public function joiny(QueryBuilderModelJoinEvent $event)
    {
        $join_model = $event->getJoinModel();

        if ($join_model->getTable() == 'categories') {
            $columns = $event->getColumns();
            $columns[] = 'testone__something';
            $event->setColumns($columns);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'form_handler.construct' => 'exampleThing',
            'form_handler.request' => 'reqThing',
            'model.create' => 'entityExtension',
            'query_builder.model_join' => 'joiny'
        );
    }
}