<?php
/**
 * User: Andy
 * Date: 04/11/14
 * Time: 11:07
 */

namespace AVCMS\Bundles\Reports;

use AV\Kernel\Bundle\BundleManagerInterface;

class ReportTypesManager
{
    protected $bundleManager;

    protected $contentTypes = [];

    public function __construct(BundleManagerInterface $bundleManager)
    {
        $this->bundleManager = $bundleManager;

        foreach ($this->bundleManager->getBundleConfigs() as $bundleConfig) {
            if (isset($bundleConfig['reports'])) {
                foreach ($bundleConfig['reports'] as $contentType => $content) {
                    $this->contentTypes[$contentType] = $content;
                }
            }
        }
    }

    public function contentTypeValid($contentType)
    {
        return isset($this->contentTypes[$contentType]);
    }

    public function getContentType($contentType)
    {
        return isset($this->contentTypes[$contentType]) ? $this->contentTypes[$contentType] : [];
    }

    public function getContentTypes()
    {
        return $this->contentTypes;
    }

    public function getUserId($contentType, $content)
    {
        $config = $this->getContentType($contentType);

        if (!$config['user_id_field']) {
            return null;
        }

        if (!is_callable([$content, 'get'.$config['user_id_field']])) {
            return null;
        }

        return $content->{'get'.$config['user_id_field']}();
    }
} 