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
namespace OrionAlliance\NewModule\Api;

/**
 * Notification CRUD interface.
 */
interface NotificationRepositoryInterface
{
    /**
     * Retrieve notification by id.
     *
     * @api
     * @param string $id
     * @return \OrionAlliance\NewModule\Api\Data\NotificationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve all notification.
     *
     * @api
     * @param int $type
     * @return \OrionAlliance\NewModule\Api\Data\NotificationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByType($type);

    /**
     * Retrieve all notification.
     *
     * @api
     * @param int $type
     * @param int $notificationId
     * @return \OrionAlliance\NewModule\Api\Data\NotificationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByNotificationIdType($type, $notificationId);

    /**
     * Retrieve all notification.
     *
     * @api
     * @return \OrionAlliance\NewModule\Api\Data\NotificationInterface
     */
    public function getList();

    /**
     * Delete Seller Notification.
     *
     * @api
     * @param \Webkul\Notification\Api\Data\NotificationInterface $notification
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\OrionAlliance\NewModule\Api\Data\NotificationInterface $notification);

    /**
     * Delete Seller Notification by ID.
     *
     * @api
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);
}
