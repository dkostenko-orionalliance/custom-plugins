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
namespace OrionAlliance\NewModule\Ui\DataProvider\Backend;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use OrionAlliance\NewModule\Model\ResourceModel\Product\CollectionFactory;

/**
 * Class Product  DataProvider
 */
class ProductListDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    /**
     * Product collection
     *
     * @var \OrionAlliance\NewModule\Model\ResourceModel\Product\Collection
     */
    protected $collection;

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ProductCollection $productCollection
     * @param CollectionFactory $collectionFactory
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
        $marketplaceProduct = $collectionFactory->create();
        $allIds = $marketplaceProduct->getAllIds();
        /** @var Collection $collection */
        $collectionData = $productCollection->create();
        $collectionData->addAttributeToSelect('status');
       // $collectionData->addFieldToFilter('entity_id', ['in' => $allIds]);
        $collectionData->joinField(
            'qty',
            'cataloginventory_stock_item',
            'qty',
            'product_id=entity_id',
            '{{table}}.stock_id=1',
            'left'
        );
        $model = $collectionFactory->create();
        $marketplaceProduct = $model->getTable('marketplace_product');
        $collectionData->getSelect()->join(
            $marketplaceProduct.' as mp',
            'e.entity_id = mp.mageproduct_id'
        );

        $customerGridFlat = $model->getTable('customer_grid_flat');
        $collectionData->getSelect()->join(
            $customerGridFlat.' as cgf',
            'mp.seller_id = cgf.entity_id',
            ["seller_name" => "name"]
        );
        // $collectionData->addFilterToMap("name", "cgf.name");
        $this->collection = $collectionData;
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
    }
}
