<?php
/**
 * User: Andy
 * Date: 19/09/2014
 * Time: 13:51
 */
namespace AVCMS\Core\Module;

interface ModuleConfigInterface
{
    public function setActive($value);

    public function getActive();

    public function setCacheTime($value);

    public function getCacheTime();

    public function getContent();

    public function setContent($content);

    public function getId();

    public function setId($value);

    public function setLimitRoutes($value);

    public function getLimitRoutes();

    public function getLimitRoutesArray();

    public function setLimitRoutesArray($value);

    public function setModule($value);

    public function getModule();

    public function setModuleInfo($moduleInfo);

    /**
     * @return \AVCMS\Core\Module\Module
     */
    public function getModuleInfo();

    public function setOrder($value);

    public function getOrder();

    public function setPosition($value);

    public function getPosition();

    public function getSettings();

    public function setSettings($value);

    public function getSettingsArray();

    public function setShowHeader($value);

    public function getShowHeader();

    public function getTemplate();

    public function setTemplate($template);

    public function setTemplateStyle($value);

    public function getTemplateStyle();

    public function setTitle($value);

    public function getTitle();
}