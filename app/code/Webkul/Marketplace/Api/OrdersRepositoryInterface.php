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
 * Orders CRUD interface.
 */
interface OrdersRepositoryInterface
{
    /**
     * Retrieve seller order by id.
     *
     * @api
     * @param string $id
     * @return \OrionAlliance\NewModule\Api\Data\OrdersInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve all seller order by seller id.
     *
     * @api
     * @param int $sellerId
     * @return \OrionAlliance\NewModule\Api\Data\OrdersInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBySellerId($sellerId);

    /**
     * Retrieve order by order id.
     *
     * @api
     * @param int $orderId
     * @return \OrionAlliance\NewModule\Api\Data\OrdersInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByOrderId($orderId);

    /**
     * Retrieve all seller order.
     *
     * @api
     * @return \OrionAlliance\NewModule\Api\Data\OrdersInterface
     */
    public function getList();
}
