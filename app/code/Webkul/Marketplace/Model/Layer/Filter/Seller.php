<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Marketplace
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\Marketplace\Model\Layer\Filter;

use Magento\Catalog\Model\Layer;
use Magento\Framework\Registry;
use Magento\Catalog\Model\Layer\Filter\DataProvider\CategoryFactory;
use Magento\Framework\DB\Sql\Expression as SqlExpression;
use Magento\Framework\DB\Select as DBSelect;

class Seller extends \Magento\Catalog\Model\Layer\Filter\AbstractFilter
{
    /**
     * @var int
     */
    protected $_categoryId;

    /**
     * Category Model
     *
     * @var \Magento\Catalog\Model\Category
     */
    protected $_appliedCategory;

    /**
     * Core data
     *
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;

    /**
     * Core registry object
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var CategoryDataProvider
     */
    private $dataProvider;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;
    /**
     * @var \Webkul\Marketplace\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_mpProductCollectionFactory;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;
    /**
     * @var \Webkul\Marketplace\Helper\Data
     */
    protected $mpHelper;

    /**
     * Construct
     *
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \Magento\Framework\Escaper $escaper
     * @param CategoryFactory $categoryDataProviderFactory
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Webkul\Marketplace\Model\ResourceModel\Product\CollectionFactory $mpProductCollectionFactory
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Webkul\Marketplace\Helper\Data $mpHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Escaper $escaper,
        CategoryFactory $categoryDataProviderFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        \Webkul\Marketplace\Model\ResourceModel\Product\CollectionFactory $mpProductCollectionFactory,
        \Magento\Framework\App\RequestInterface $request,
        \Webkul\Marketplace\Helper\Data $mpHelper,
        array $data = []
    ) {
        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $data);
        $this->_storeManager = $storeManager;
        $this->_escaper = $escaper;
        $this->dataProvider = $categoryDataProviderFactory->create(['layer' => $this->getLayer()]);
        $this->_resource = $resource;
        $this->_mpProductCollectionFactory = $mpProductCollectionFactory;
        $this->_request = $request;
        $this->_mpHelper = $mpHelper;
        $this->_requestVar = $this->_mpHelper->getRequestVar();
    }

    /**
     * Get filter value for reset current filter state
     *
     * @return mixed|null
     */
    public function getResetValue()
    {
        return $this->dataProvider->getResetValue();
    }

    /**
     * Apply category filter to layer
     *
     * @param   \Magento\Framework\App\RequestInterface $request
     * @return  $this
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        $storeId = $this->_storeManager->getStore()->getId();
        $filter = $request->getParam($this->getRequestVar());
        if (!$filter) {
            return $this;
        }

        $collection = $this->getLayer()->getProductCollection();
       
        $sellerProductCollection = $this->_mpProductCollectionFactory->create();

        if ($filter == $this->_mpHelper::MARKETPLACE_ADMIN_URL) {
            $productIds = $sellerProductCollection->getData();
            $collection->addAttributeToFilter('entity_id', ['nin' => $productIds]);
        } else {
            $sellerTable = $this->_resource->getTableName('marketplace_userdata');
            $fields = ['shop_url'];
            $sellerProductCollection->getSelect()->join(
                $sellerTable.' as seller',
                'seller.seller_id = main_table.seller_id',
                $fields
            );
            $sellerProductCollection->getSelect()->where("seller.shop_url = '".$filter."'");
            $sellerProductCollection->getSelect()->reset(DBSelect::COLUMNS)->columns('main_table.mageproduct_id');
            $sellerProductCollection->getSelect()->group("main_table.mageproduct_id");
            $productIds = $sellerProductCollection->getData();
            $collection->addAttributeToFilter("entity_id", ['in' => $productIds]);
        }
        $sellerData = $this->_mpHelper->getSellerDataByShopUrl($filter);
        if (isset($sellerData->getData()[0]['shop_title']) && $sellerData->getData()[0]['shop_title']) {
            $filter = $sellerData->getData()[0]['shop_title'];
        }
        $this->getLayer()->getState()->addFilter($this->_createItem($filter, $filter));
        return $this;
    }

    /**
     * Get filter name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getName()
    {
        return __('Seller');
    }

    /**
     * Get data array for building attribute filter items
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return array
     */
    protected function _getItemsData()
    {
        if ($this->_mpHelper->isSellerFilterActive()) {
            return $this->itemDataBuilder->build();
        }
        $collection = $this->getLayer()->getProductCollection();
        $facetedData = $collection->getFacetedData('seller_id');
    
        foreach ($facetedData as $item) {
            
            $sellerId = $item['value'];
            // $shopUrl = $item->getShopUrl();
            // $shopTitle = $item->getShopTitle();
            $count = $item['count'];
            if ($sellerId == 0) {
                $title = $this->_mpHelper->getAdminFilterDisplayName();
                $shopUrl = $this->_mpHelper::MARKETPLACE_ADMIN_URL;
            } else {
                $title ='';
                $shopUrl = '';
                  
                $sellerData = $this->_mpHelper->getSellerDataBySellerId($sellerId);
                if (isset($sellerData->getData()[0]['shop_url']) && $sellerData->getData()[0]['shop_url']) {
                    $shopUrl = $sellerData->getData()[0]['shop_url'];
                }
                if (isset($sellerData->getData()[0]['shop_title']) && $sellerData->getData()[0]['shop_title']) {
                    $title = $sellerData->getData()[0]['shop_title'];
                } else {
                    $title = $shopUrl;
                }
               
            }

            $this->itemDataBuilder->addItemData($title, $shopUrl, $count);
        }

        return $this->itemDataBuilder->build();
    }

    /**
     * Get All Product Ids of Category or Search Page
     *
     * @return array
     */
    public function getProductIds()
    {
        $collection = $this->getLayer()->getProductCollection();
        $collection->getSelect()
                    ->reset(DBSelect::LIMIT_COUNT)
                    ->reset(DBSelect::LIMIT_OFFSET)
                    ->reset(DBSelect::COLUMNS)
                    ->reset(DBSelect::ORDER)
                    ->columns('e.entity_id');
        $query = $collection->getSelect()->__toString();
        $connection = $this->_resource->getConnection();
        $result = $connection->fetchAll($query);
        $productIds = [];
        foreach ($result as $row) {
            $productIds[] = $row['entity_id'];
        }

        return $productIds;
    }
}
