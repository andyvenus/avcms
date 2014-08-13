<?php
/**
 * User: Andy
 * Date: 27/07/2014
 * Time: 10:06
 */

namespace AVCMS\BundlesDev\BundleManager\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\Yaml\Yaml;

class BundleBaseController extends Controller
{
    /**
     * @param $bundle
     * @return null|\AVCMS\Core\Bundle\BundleConfig
     */
    protected function getBundleConfig($bundle)
    {
        $bundles = $this->container->get('bundle_manager');

        try {
            $bundle_config = $bundles->loadBundleConfig($bundle);
        }
        catch(\Exception $e) {
            return null;
        }

        return $bundle_config;
    }

    protected function getBundleBuilderConfig()
    {
        return Yaml::parse(file_get_contents('src/AVCMS/Core/Bundle/BundleBuilder/BundleBlueprint/config.yml'));
    }

    /**
     * Convert something_like_this to somethingLikeThis
     *
     * @param $string
     * @param bool $capitalize_first_character
     * @return mixed
     */
    protected function dashesToCamelCase($string, $capitalize_first_character = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalize_first_character) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
} 