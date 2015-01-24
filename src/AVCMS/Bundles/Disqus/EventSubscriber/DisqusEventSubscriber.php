<?php
/**
 * User: Andy
 * Date: 24/01/15
 * Time: 16:52
 */

namespace AVCMS\Bundles\Disqus\EventSubscriber;

use AVCMS\Bundles\Comments\Event\CommentsAreaEvent;
use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DisqusEventSubscriber implements EventSubscriberInterface
{
    protected $settingsManager;

    protected $twig;

    public function __construct(SettingsManager $settingsManager, \Twig_Environment $twig)
    {
        $this->settingsManager = $settingsManager;
        $this->twig = $twig;
    }

    public function disqusCommentsArea(CommentsAreaEvent $event)
    {
        if ($this->settingsManager->getSetting('use_disqus')) {
            $event->setCommentsArea($this->twig->render('@Disqus/disqus_comments_area.twig', ['content_type' => $event->getContentType(), 'content_id' => $event->getContent()]));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'comments_area' => ['disqusCommentsArea']
        ];
    }
}
