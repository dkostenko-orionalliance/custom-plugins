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

class AfterInvoiceSave implements ObserverInterface
{
    /**
     * @var \Webkul\MpAdvancedBookingSystem\Helper\Order
     */
    private $helper;

    /**
     * Constructor
     *
     * @param \Webkul\MpAdvancedBookingSystem\Helper\Order $helper
     */
    public function __construct(
        \Webkul\MpAdvancedBookingSystem\Helper\Order $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $order = $observer->getInvoice()->getOrder();
            foreach ($order->getItems() as $item) {
                $this->helper->checkBookingProduct($item->getProductId());
            }
        } catch (\Exception $e) {
            $this->helper->logDataInLogger(
                "Observer_AfterInvoiceSave execute : ".$e->getMessage()
            );
        }
    }
}
