<?php
/**
 * User: Andy
 * Date: 20/12/14
 * Time: 19:31
 */

namespace AVCMS\Bundles\Categories\Subscribers;

use AV\Kernel\Bundle\BundleManagerInterface;
use AV\Model\ModelFactory;
use AVCMS\Bundles\Admin\Event\AdminSaveContentEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SetCategoryParentOnContentSubscriber implements EventSubscriberInterface
{
    /**
     * @var BundleManagerInterface
     */
    protected $bundleManager;

    protected $modelFactory;

    public function __construct(BundleManagerInterface $bundleManager, ModelFactory $modelFactory)
    {
        $this->bundleManager = $bundleManager;
        $this->modelFactory = $modelFactory;
    }

    public function beforeSave(AdminSaveContentEvent $event)
    {
        $entity = $event->getEntity();

        if (!is_callable([$entity, 'setCategoryParentId']) || !is_callable([$entity, 'getCategoryId'])) {
            return;
        }

        $contentType = $event->getModel()->getSingular();

        $categoriesModel = $this->getCategoriesModel($contentType);
        $category = $categoriesModel->getOne($entity->getCategoryId());

        if ($category->getParent()) {
            $entity->setCategoryParentId($category->getParent());
        }
        else {
            $entity->setCategoryParentId(null);
        }
    }

    private function getCategoriesModel($contentType)
    {
        foreach ($this->bundleManager->getBundleConfigs() as $bundleConfig) {
            if (!isset($bundleConfig['categories'])) {
                continue;
            }

            if (isset($bundleConfig['categories'][$contentType])) {
                $modelClass = $bundleConfig['categories'][$contentType]['model'];
                return $this->modelFactory->create($modelClass);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'admin.before.content.save' => ['beforeSave']
        ];
    }
}
