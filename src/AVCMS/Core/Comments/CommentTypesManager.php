<?php
/**
 * User: Andy
 * Date: 28/10/14
 * Time: 21:25
 */

namespace AVCMS\Core\Comments;

use AV\Kernel\Bundle\BundleManagerInterface;

class CommentTypesManager
{
    protected $bundleManager;

    protected $contentTypes = [];

    public function __construct(BundleManagerInterface $bundleManager)
    {
        $this->bundleManager = $bundleManager;

        foreach ($this->bundleManager->getBundleConfigs() as $bundleConfig) {
            if (isset($bundleConfig['comments'])) {
                foreach ($bundleConfig['comments'] as $contentType => $content) {
                    $this->contentTypes[$contentType] = $content;
                }
            }
        }
    }

    public function contentTypeValid($contentType)
    {
        return isset($this->contentTypes[$contentType]);
    }

    public function getModelClass($contentType)
    {
        if (!isset($this->contentTypes[$contentType]) || !isset($this->contentTypes[$contentType]['model'])) {
            throw new \Exception("Model not found for content type $contentType");
        }

        return $this->contentTypes[$contentType]['model'];
    }

    public function getTitleField($contentType)
    {
        return isset($this->contentTypes[$contentType]['title_field']) ? $this->contentTypes[$contentType]['title_field'] : null;
    }

    public function getFrontendRoute($contentType)
    {
        return isset($this->contentTypes[$contentType]['frontend_route']) ? $this->contentTypes[$contentType]['frontend_route'] : null;
    }

    public function getFrontendRouteParams($contentType)
    {
        return isset($this->contentTypes[$contentType]['frontend_route_params']) ? $this->contentTypes[$contentType]['frontend_route_params'] : [];
    }

    public function getContentTypes()
    {
        return $this->contentTypes;
    }
} 