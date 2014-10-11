<?php
/**
 * User: Andy
 * Date: 10/10/2014
 * Time: 20:08
 */

namespace AVCMS\Bundles\Users\Subscriber;

use AVCMS\Core\Bundle\BundleManagerInterface;
use AVCMS\Core\Model\Model;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class UpdateBundlePermissionsSubscriber implements EventSubscriberInterface
{
    const LOADER_NAME = 'Bundle';

    private $permissions;

    private $bundleManager;

    public function __construct(BundleManagerInterface $bundleManager, Model $permissions)
    {
        $this->permissions = $permissions;
        $this->bundleManager = $bundleManager;
    }

    public function updatePermissions()
    {
        if ($this->bundleManager->cacheIsFresh()) {
            return;
        }

        $this->permissions->query()->where('loader', self::LOADER_NAME)->delete();

        $configs = $this->bundleManager->getBundleConfigs();
        foreach ($configs as $config) {
            if (isset($config->permissions)) {
                foreach ($config->permissions as $permissionId => $permissionData) {
                    $permission = $this->permissions->newEntity();
                    $permission->setId($permissionId);
                    $permission->setName($permissionData->name);
                    $permission->setDescription($permissionData->description);
                    $permission->setLoader(self::LOADER_NAME);
                    $permission->setOwner($config->name);

                    $this->permissions->insert($permission);
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('updatePermissions')
        );
    }
}