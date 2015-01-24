<?php
/**
 * User: Andy
 * Date: 24/01/15
 * Time: 16:16
 */

namespace AVCMS\Bundles\Comments\Event;

use Symfony\Component\EventDispatcher\Event;

class CommentsAreaEvent extends Event
{
    protected $contentType;

    protected $content;

    protected $totalComments;

    protected $template;

    protected $commentsArea = null;

    public function __construct($contentType, $content, $totalComments, $template)
    {
        $this->contentType = $contentType;
        $this->content = $content;
        $this->totalComments = $totalComments;
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getTotalComments()
    {
        return $this->totalComments;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return mixed
     */
    public function getCommentsArea()
    {
        return $this->commentsArea;
    }

    /**
     * @param mixed $commentsArea
     */
    public function setCommentsArea($commentsArea)
    {
        $this->commentsArea = $commentsArea;
    }


}
