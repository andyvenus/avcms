<?php
/**
 * User: Andy
 * Date: 06/12/14
 * Time: 12:12
 */

namespace AV\Kernel\Tests\Fixtures;

use AV\Kernel\Bundle\BundleManagerInterface;
use AV\Kernel\BundleKernel;

class BundleKernelForTest extends BundleKernel
{
    public function setBundleManager(BundleManagerInterface $bundleManager)
    {
        $this->bundleManager = $bundleManager;
    }
} 