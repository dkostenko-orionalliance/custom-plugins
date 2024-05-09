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
use OrionAlliance\NewModule\Api\Data\OrdersInterface;
use OrionAlliance\NewModule\Model\ResourceModel\Orders\CollectionFactory;

class OrdersRepository implements \OrionAlliance\NewModule\Api\OrdersRepositoryInterface
{
    /**
     * @var OrdersFactory
     */
    protected $ordersFactory;

    /**
     * @var Orders[]
     */
    protected $instancesById = [];

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param OrdersFactory       $ordersFactory
     * @param CollectionFactory   $collectionFactory
     */
    public function __construct(
        OrdersFactory $ordersFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->ordersFactory = $ordersFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get by id
     *
     * @param int $id
     * @return \OrionAlliance\NewModule\Model\Orders
     */
    public function getById($id)
    {
        $ordersData = $this->ordersFactory->create();
        $ordersData->load($id);
        if (!$ordersData->getId()) {
            $this->instancesById[$id] = $ordersData;
        }
        $this->instancesById[$id] = $ordersData;

        return $this->instancesById[$id];
    }

    /**
     * Get by sellerid
     *
     * @param int||null $sellerId
     * @return \OrionAlliance\NewModule\Model\ResourceModel\Orders\Collection
     */
    public function getBySellerId($sellerId = null)
    {
        $ordersCollection = $this->collectionFactory->create()
        ->addFieldToFilter('seller_id', $sellerId);
        $ordersCollection->load();

        return $ordersCollection;
    }

    /**
     * Get by orderid
     *
     * @param int $orderId
     * @return \OrionAlliance\NewModule\Model\ResourceModel\Orders\Collection
     */
    public function getByOrderId($orderId)
    {
        $ordersCollection = $this->collectionFactory->create()
        ->addFieldToFilter('order_id', $orderId);
        $ordersCollection->load();

        return $ordersCollection;
    }

    /**
     * Get Collection
     *
     * @return \OrionAlliance\NewModule\Model\ResourceModel\Orders\Collection
     */
    public function getList()
    {
        /** @var \OrionAlliance\NewModule\Model\ResourceModel\Orders\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->load();

        return $collection;
    }
}
