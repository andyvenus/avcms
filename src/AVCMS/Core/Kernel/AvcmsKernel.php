<?php
/**
 * User: Andy
 * Date: 22/10/14
 * Time: 12:20
 */

namespace AVCMS\Core\Kernel;

use AV\Kernel\BundleKernel;

class AvcmsKernel extends BundleKernel
{
    // This code looks the same as what we are overloading, but we are
    // using the AVCMS BundleManager over the default AV Framework version
    protected function buildBundleManager()
    {
        $this->bundleManager = new BundleManager($this->bundleDirs, $this->options['app_dir'].'/config', $this->options['cache_dir']);
        $this->bundleManager->setDebug($this->debug);
    }
}
