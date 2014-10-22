<?php
/**
 * User: Andy
 * Date: 22/10/14
 * Time: 12:20
 */

namespace AVCMS\Core\Kernel;

use AV\Kernel\BundleKernel;
use AVCMS\Core\Bundle\Config\BundleConfigValidator;

class AvcmsKernel extends BundleKernel
{
    protected function getBundleConfigValidator()
    {
        return new BundleConfigValidator();
    }
} 