<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpAdvancedBookingSystem
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpAdvancedBookingSystem\Observer;

use Magento\Framework\Event\ObserverInterface;

class AfterRefundOrder implements ObserverInterface
{
    /**
     * @var \Webkul\MpAdvancedBookingSystem\Helper\Order
     */
    protected $_bookingHelper;

    /**
     * @var \Webkul\MpAdvancedBookingSystem\Model\Booked\CollectionFactory
     */
    protected $bookedCollection;

    /**
     * @var \Magento\Sales\Api\OrderItemRepositoryInterface
     */
    protected $itemRepository;

    /**
     * Constructor
     *
     * @param \Webkul\MpAdvancedBookingSystem\Helper\Order                                 $bookingHelper
     * @param \Webkul\MpAdvancedBookingSystem\Model\ResourceModel\Booked\CollectionFactory $bookedCollection
     * @param \Magento\Sales\Api\OrderItemRepositoryInterface                              $itemRepository
     */
    public function __construct(
        \Webkul\MpAdvancedBookingSystem\Helper\Order $bookingHelper,
        \Webkul\MpAdvancedBookingSystem\Model\ResourceModel\Booked\CollectionFactory $bookedCollection,
        \Magento\Sales\Api\OrderItemRepositoryInterface $itemRepository
    ) {
        $this->_bookingHelper = $bookingHelper;
        $this->bookedCollection = $bookedCollection;
        $this->itemRepository = $itemRepository;
    }

    /**
     * Execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $creditmemo = $observer->getEvent()->getCreditmemo();
            foreach ($creditmemo->getItems() as $item) {
                if ($item->getBackToStock()) {
                    $this->updateBookedSlotsInfo($item, $creditmemo);
                }
            }
            $this->_bookingHelper->clearCache();
        } catch (\Exception $e) {
            $this->_bookingHelper->logDataInLogger(
                "Observer_AfterRefundOrder execute : ".$e->getMessage()
            );
        }
    }

    /**
     * Set Booking Slots Info
     *
     * @param object $item
     * @param object $creditmemo
     */
    public function updateBookedSlotsInfo($item, $creditmemo)
    {
        try {
            $helper = $this->_bookingHelper;
            $itemId = $item->getOrderItemId();
            $orderItem = $this->itemRepository->get($itemId);
            $orderId = $creditmemo->getOrderId();
            $customerId = (int) $creditmemo->getCustomerId();
            $quoteItemId = $orderItem->getQuoteItemId();
            $bookingData = $helper->getDetailsByQuoteItemId($quoteItemId);
            $productId = $item->getProductId();
            $returnQty = $item->getQty();
            if (!$bookingData['error']) {
                $slotId = $bookingData['slot_id'];
                $parentId = $bookingData['parent_slot_id'];
                $collection = $this->bookedCollection->create()
                    ->addFieldToFilter('order_id', $orderId)
                    ->addFieldToFilter('order_item_id', $itemId)
                    ->addFieldToFilter('item_id', $bookingData['item_id'])
                    ->addFieldToFilter('product_id', $productId)
                    ->addFieldToFilter('slot_id', $slotId)
                    ->addFieldToFilter('parent_slot_id', $parentId)
                    ->addFieldToFilter('customer_id', $customerId);
                if ($collection->getSize()) {
                    foreach ($collection as $data) {
                        if ($returnQty==$data->getQty()) {
                            $data->delete();
                        } else {
                            $data->setQty($data->getQty()-$returnQty)->save();
                        }
                    }
                }
                $bookingInfo = $helper->getBookingInfo($productId);
                if ($bookingInfo['is_booking']) {
                    $slotData = $helper->getSlotData(
                        $slotId,
                        $parentId,
                        $productId,
                        $bookingData
                    );
                    $attributeSetId = 0;
                    if (!empty($slotData['attribute_set_id'])) {
                        $attributeSetId = $slotData['attribute_set_id'];
                    }
                    $bookingData['qty'] = $returnQty;
                    $bookingData['product_id'] = $productId;
                    $bookingData['attribute_set_id'] = $attributeSetId;
                    $helper->saveBookingInfoData(
                        $bookingData,
                        $bookingInfo
                    );
                }
            }
        } catch (\Exception $e) {
            $this->_bookingHelper->logDataInLogger(
                "Observer_AfterRefundOrder updateBookedSlotsInfo : ".$e->getMessage()
            );
        }
    }
}
