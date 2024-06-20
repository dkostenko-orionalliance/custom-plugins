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

namespace Webkul\MpAdvancedBookingSystem\Plugin\Controller\Marketplace\Order;

class InvoicePlugin
{
    /**
     * @var array
     */
    public const ALLOWED_TYPES = [
        "virtual",
        "booking",
        "hotelbooking"
    ];

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollFactory;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollFactory
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollFactory,
    ) {
        $this->productCollFactory = $productCollFactory;
    }

    /**
     * After plugin for set row order status
     *
     * @param \Webkul\Marketplace\Controller\Order\Invoice $subject
     * @param void $result
     * @param object $row
     */
    public function afterSetRowOrderStatus(
        \Webkul\Marketplace\Controller\Order\Invoice $subject,
        $result,
        $row
    ) {
        $isVirtual = true;
        $productIds = $row->getProductIds();
        $productIdsArray = explode(",", $productIds);
        $productCollection = $this->productCollFactory->create()
                            ->addAttributeToFilter("entity_id", ["in" => $productIdsArray]);

        foreach ($productCollection as $prodColl) {
            if (!in_array($prodColl->getTypeId(), self::ALLOWED_TYPES)) {
                $isVirtual = false;
            }
        }
        if ($isVirtual) {
            $row->setOrderStatus('complete');
        } else {
            $row->setOrderStatus('processing');
        }
    }
}
