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
namespace Webkul\MpAdvancedBookingSystem\Ui\DataProvider;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Webkul\Marketplace\Model\ResourceModel\Product\CollectionFactory;
use Webkul\Marketplace\Helper\Data as HelperData;
use Webkul\MpAdvancedBookingSystem\Model\ResourceModel\Info\CollectionFactory as InfoCollection;

/**
 * DataProvider SellerProductListDataProvider
 */
class SellerProductListDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    /**
     * Product collection
     *
     * @var \Webkul\Marketplace\Model\ResourceModel\Product\Collection
     */
    protected $collection;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ProductCollection $productCollection
     * @param CollectionFactory $collectionFactory
     * @param InfoCollection $infoCollection
     * @param HelperData $helperData
     * @param \Magento\Framework\Registry $registry
     * @param array $addFieldStrategies
     * @param array $addFilterStrategies
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ProductCollection $productCollection,
        CollectionFactory $collectionFactory,
        InfoCollection $infoCollection,
        HelperData $helperData,
        \Magento\Framework\Registry $registry,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $productCollection,
            $addFieldStrategies,
            $addFilterStrategies,
            $meta,
            $data
        );

        $sellerId = $helperData->getCustomerId();
        if (!$registry->registry('mp_flat_catalog_flag')) {
            $registry->register('mp_flat_catalog_flag', 1);
        }

        $marketplaceProduct = $collectionFactory->create()
        ->addFieldToFilter('seller_id', $sellerId);
        $allIds = $marketplaceProduct->getAllIds();

        $bookingProduct = $infoCollection->create();
        $allBookingIds = $bookingProduct->getAllProductIds();
        $allIds = array_unique(array_intersect($allIds, $allBookingIds));

        /** @var Collection $collection */
        $collectionData = $productCollection->create();
        $collectionData->addAttributeToSelect('status');
        $collectionData->addFieldToFilter('entity_id', ['in' => $allIds]);
        $collectionData->joinField(
            'qty',
            'cataloginventory_stock_item',
            'qty',
            'product_id=entity_id',
            '{{table}}.stock_id=1',
            'left'
        );
        $collectionData->setFlag('has_stock_status_filter');
        $this->collection = $collectionData;
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
    }
}
