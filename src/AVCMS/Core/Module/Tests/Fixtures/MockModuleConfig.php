<?php
/**
 * User: Andy
 * Date: 19/09/2014
 * Time: 14:24
 */

namespace AVCMS\Core\Module\Tests\Fixtures;

use AVCMS\Core\Model\Entity;
use AVCMS\Core\Module\ModuleConfigInterface;

class MockModuleConfig implements ModuleConfigInterface
{
    protected $active = 1;

    public function setActive($value)
    {
        $this->active = $value;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setCacheTime($value)
    {
        // TODO: Implement setCacheTime() method.
    }

    public function getCacheTime()
    {
        // TODO: Implement getCacheTime() method.
    }

    public function getContent()
    {
        // TODO: Implement getContent() method.
    }

    public function setContent($content)
    {
        // TODO: Implement setContent() method.
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function setId($value)
    {
        // TODO: Implement setId() method.
    }

    public function setLimitRoutes($value)
    {
        // TODO: Implement setLimitRoutes() method.
    }

    public function getLimitRoutes()
    {
        // TODO: Implement getLimitRoutes() method.
    }

    public function getLimitRoutesArray()
    {
        // TODO: Implement getLimitRoutesArray() method.
    }

    public function setLimitRoutesArray($value)
    {
        // TODO: Implement setLimitRoutesArray() method.
    }

    public function setModule($value)
    {
        // TODO: Implement setModule() method.
    }

    public function getModule()
    {
        // TODO: Implement getModule() method.
    }

    public function setModuleInfo($moduleInfo)
    {
        // TODO: Implement setModuleInfo() method.
    }

    public function getModuleInfo()
    {
        // TODO: Implement getModuleInfo() method.
    }

    public function setOrder($value)
    {
        // TODO: Implement setOrder() method.
    }

    public function getOrder()
    {
        // TODO: Implement getOrder() method.
    }

    public function setPosition($value)
    {
        // TODO: Implement setPosition() method.
    }

    public function getPosition()
    {
        // TODO: Implement getPosition() method.
    }

    public function getSettings()
    {
        // TODO: Implement getSettings() method.
    }

    public function setSettings($value)
    {
        // TODO: Implement setSettings() method.
    }

    public function getSettingsArray()
    {
        // TODO: Implement getSettingsArray() method.
    }

    public function setShowHeader($value)
    {
        // TODO: Implement setShowHeader() method.
    }

    public function getShowHeader()
    {
        // TODO: Implement getShowHeader() method.
    }

    public function getTemplate()
    {
        // TODO: Implement getTemplate() method.
    }

    public function setTemplate($template)
    {
        // TODO: Implement setTemplate() method.
    }

    public function setTemplateType($value)
    {
        // TODO: Implement setTemplateType() method.
    }

    public function getTemplateType()
    {
        // TODO: Implement getTemplateType() method.
    }

    public function setTitle($value)
    {
        // TODO: Implement setTitle() method.
    }

    public function getTitle()
    {
        // TODO: Implement getTitle() method.
    }
}