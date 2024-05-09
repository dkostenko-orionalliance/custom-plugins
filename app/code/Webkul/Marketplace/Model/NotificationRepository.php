<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   OrionAlliance_NewModule
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace OrionAlliance\NewModule\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use OrionAlliance\NewModule\Api\Data\NotificationInterface;
use OrionAlliance\NewModule\Model\ResourceModel\Notification\CollectionFactory;
use OrionAlliance\NewModule\Model\ResourceModel\Notification as ResourceModelNotification;

class NotificationRepository implements \OrionAlliance\NewModule\Api\NotificationRepositoryInterface
{
    /**
     * @var NotificationFactory
     */
    protected $notificationFactory;

    /**
     * @var Notification[]
     */
    protected $instancesById = [];

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ResourceModelNotification
     */
    protected $resourceModel;

    /**
     * @param NotificationFactory       $notificationFactory
     * @param CollectionFactory         $collectionFactory
     * @param ResourceModelNotification $resourceModel
     */
    public function __construct(
        NotificationFactory $notificationFactory,
        CollectionFactory $collectionFactory,
        ResourceModelNotification $resourceModel
    ) {
        $this->notificationFactory = $notificationFactory;
        $this->collectionFactory = $collectionFactory;
        $this->resourceModel = $resourceModel;
    }
    /**
     * Get by  id
     *
     * @param int $id
     */
    public function getById($id)
    {
        $notificationData = $this->notificationFactory->create();
        $notificationData->load($id);
        if (!$notificationData->getId()) {
            $this->instancesById[$id] = $notificationData;
        }
        $this->instancesById[$id] = $notificationData;

        return $this->instancesById[$id];
    }

    /**
     * Get by Type
     *
     * @param string $type
     */
    public function getByType($type = null)
    {
        $notificationCollection = $this->collectionFactory->create()
                ->addFieldToFilter('type', $type);
        $notificationCollection->load();

        return $notificationCollection;
    }

    /**
     * Get by notification id
     *
     * @param string $type
     * @param int|null $notificationId
     */
    public function getByNotificationIdType($type, $notificationId = null)
    {
        $notificationCollection = $this->collectionFactory->create()
        ->addFieldToFilter('notification_id', $notificationId)
        ->addFieldToFilter('type', $type);
        $notificationCollection->load();

        return $notificationCollection;
    }

    /**
     * Get list
     */
    public function getList()
    {
        /** @var \OrionAlliance\NewModule\Model\ResourceModel\Notification\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->load();

        return $collection;
    }

    /**
     * Delete records
     *
     * @param NotificationInterface $notification
     */
    public function delete(NotificationInterface $notification)
    {
        $id = $notification->getId();
        try {
            $this->resourceModel->delete($notification);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __('Unable to remove notification data record with id %1', $id)
            );
        }
        unset($this->instancesById[$id]);

        return true;
    }

   /**
    * Delete by id
    *
    * @param int $id
    */
    public function deleteById($id)
    {
        $notification = $this->get($id);

        return $this->delete($notification);
    }
}
