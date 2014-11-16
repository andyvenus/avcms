<?php
/**
 * User: Andy
 * Date: 19/09/2014
 * Time: 16:04
 */

namespace AVCMS\Core\Module;

class Module
{
    /**
     * @var int|null
     */
    protected $id = null;

    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var string
     */
    protected $description = null;

    /**
     * @var string
     */
    protected $controller = null;

    /**
     * @var string
     */
    protected $templateStyle = 'plain';

    /**
     * @var string
     */
    protected $cachable = 0;

    /**
     * @var string
     */
    protected $defaultCacheTime = 0;

    /**
     * @var
     */
    protected $type = 'standard';

    /**
     * @var array
     */
    protected $userSettings = [];

    /**
     * @var array
     */
    protected $cacheIdVars;

    /**
     * @var string
     */
    protected $permissions;

    /**
     * @var
     */
    protected $cacheBustModel;

    /**
     * @var array
     */
    protected $acceptedTemplateTypes = ['panel', 'none'];

    public function __construct($data = null)
    {
        if ($data) {
            $this->fromArray($data);
        }
    }

    /**
     * @return string
     */
    public function isCachable()
    {
        return $this->cachable;
    }

    /**
     * @param string $cachable
     */
    public function setCachable($cachable)
    {
        $this->cachable = $cachable;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getDefaultCacheTime()
    {
        return $this->defaultCacheTime;
    }

    /**
     * @param string $defaultCacheTime
     */
    public function setDefaultCacheTime($defaultCacheTime)
    {
        $this->defaultCacheTime = $defaultCacheTime;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTemplateType()
    {
        return $this->templateStyle;
    }

    /**
     * @param string $templateStyle
     */
    public function setTemplateType($templateStyle)
    {
        $this->templateStyle = $templateStyle;
    }

    public function getAcceptedTemplateTypes()
    {
        return $this->acceptedTemplateTypes;
    }

    public function setAcceptedTemplateTypes($templateTypes)
    {
        $this->acceptedTemplateTypes = $templateTypes;
    }

    /**
     * @return array
     */
    public function getUserSettings()
    {
        return $this->userSettings;
    }

    /**
     * @param array $userSettings
     */
    public function setUserSettings($userSettings)
    {
        $this->userSettings = $userSettings;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getCacheIdVars()
    {
        return $this->cacheIdVars;
    }

    public function setCacheIdVars($cacheIdVars)
    {
        $this->cacheIdVars = $cacheIdVars;
    }

    public function getDefaultPermissions()
    {
        return $this->permissions;
    }

    public function setDefaultPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    public function getCacheBustModel()
    {
        return $this->cacheBustModel;
    }

    public function setCacheBustModel($cacheBustModel)
    {
        $this->cacheBustModel = $cacheBustModel;
    }

    public function fromArray(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set'.str_replace('_', '', $key);
            if (!method_exists($this, $method)) {
                throw new \Exception(sprintf('Cannot add data "%s" to module object', $key));
            }

            $this->$method($value);
        }
    }
}