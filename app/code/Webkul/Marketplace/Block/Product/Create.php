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

namespace Webkul\Marketplace\Block\Product;

/*
 * Webkul Marketplace Product Create Block
 */
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Category;
use Magento\GoogleOptimizer\Model\Code as ModelCode;
use Webkul\Marketplace\Helper\Data as HelperData;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\DB\Helper as FrameworkDbHelper;
use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Eav\Model\Entity\AttributeFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as OptionCollectionFactory;
use Webkul\MpAdvancedBookingSystem\Helper\Data as BookingHelper;


class Create extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;

    /**
     * @var \Magento\Catalog\Model\Category
     */
    protected $_category;

    /**
     * @var ModelCode
     */
    protected $_modelCode;

    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;

    /**
     * @var CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var FrameworkDbHelper
     */
    protected $frameworkDbHelper;

    /**
     * @var CategoryHelper
     */
    protected $categoryHelper;

    /**
     * @var CacheInterface
     */
    private $cacheInterface;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     *
     * @var string|null
     */
    protected $filter = null;

    /**
     * @var SerializerInterface
     */
    protected $serializer;
    /**
     * @var \Magento\Cms\Helper\Wysiwyg\Images|null
     */
    protected $wysiwygImages;
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;


    protected $logger;

    protected $_attributeFactory;

    protected $_optionCollectionFactory;

    protected $bookingHelper;

    /**
     * Construct
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param Product $product
     * @param Category $category
     * @param ModelCode $modelCode
     * @param HelperData $helperData
     * @param ProductRepositoryInterface $productRepository
     * @param CollectionFactory $categoryCollectionFactory
     * @param FrameworkDbHelper $frameworkDbHelper
     * @param CategoryHelper $categoryHelper
     * @param DataPersistorInterface $dataPersistor
     * @param SerializerInterface $serializer
     * @param \Magento\Cms\Helper\Wysiwyg\Images|null $wysiwygImages
     * @param CacheInterface|null $cacheInterface
     * @param \Magento\Directory\Model\Currency $currency
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        Product $product,
        Category $category,
        ModelCode $modelCode,
        HelperData $helperData,
        ProductRepositoryInterface $productRepository,
        CollectionFactory $categoryCollectionFactory,
        FrameworkDbHelper $frameworkDbHelper,
        CategoryHelper $categoryHelper,
        DataPersistorInterface $dataPersistor,
        SerializerInterface $serializer,
        \Magento\Cms\Helper\Wysiwyg\Images $wysiwygImages = null,
        CacheInterface $cacheInterface = null,
        \Magento\Directory\Model\Currency $currency,
        AttributeFactory $attributeFactory,
        OptionCollectionFactory $optionCollectionFactory,
        \Psr\Log\LoggerInterface $logger,
        \Webkul\MpAdvancedBookingSystem\Helper\Data $bookingHelper,
        array $data = []
    ) {
        $this->_product = $product;
        $this->_category = $category;
        $this->_modelCode = $modelCode;
        $this->_helperData = $helperData;
        $this->_productRepository = $productRepository;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->frameworkDbHelper = $frameworkDbHelper;
        $this->categoryHelper = $categoryHelper;
        $this->dataPersistor = $dataPersistor;
        $this->serializer = $serializer;
        $this->wysiwygImages = $wysiwygImages ?: \Magento\Framework\App\ObjectManager::getInstance()
                                  ->create(\Magento\Cms\Helper\Wysiwyg\Images::class);
        $this->cacheInterface = $cacheInterface ?: \Magento\Framework\App\ObjectManager::getInstance()
                                    ->create(CacheInterface::class);
        $this->currency = $currency ?: \Magento\Framework\App\ObjectManager::getInstance()
                                    ->create(Magento\Directory\Model\Currency::class);
        $this->_attributeFactory = $attributeFactory;
        $this->_optionCollectionFactory = $optionCollectionFactory;
        $this->logger = $logger;
        $this->bookingHelper = $bookingHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get product
     *
     * @param [int] $id
     * @return product
     */
    public function getProduct($id)
    {
        return $this->_product->load($id);
    }

    public function getLanguageSeniorityPairs($productId)
    {
        $product = $this->getProduct($productId);
        return $product ? $product->getData('language_seniority') : '';
    }

    public function getSkillSeniorityPairs($productId)
    {
        $product = $this->getProduct($productId);
        return $product ? $product->getData('skill_seniority') : '';
    }

    public function getCountry($productId)
    {
        $product = $this->getProduct($productId);
        return $product ? $product->getData('country_pic') : '';
    }

    public function getIndustryExpertise($productId)
    {
        $product = $this->getProduct($productId);
        return $product ? $product->getData('industry_expertise') : '';
    }

    /**
     * Get category
     *
     * @return void
     */
    public function getCategory()
    {
        return $this->_category;
    }

    /**
     * Get Googleoptimizer Fields Values.
     *
     * @param ModelCode|null $experimentCodeModel
     *
     * @return array
     */
    public function getGoogleoptimizerFieldsValues()
    {
        $entityId = $this->getRequest()->getParam('id');
        $storeId = $this->_helperData->getCurrentStoreId();
        $experimentCodeModel = $this->_modelCode->loadByEntityIdAndType(
            $entityId,
            'product',
            $storeId
        );
        $result = [];
        $result['experiment_script'] =
        $experimentCodeModel ? $experimentCodeModel->getExperimentScript() : '';
        $result['code_id'] =
        $experimentCodeModel ? $experimentCodeModel->getCodeId() : '';

        return $result;
    }

    /**
     * Get product by sku
     *
     * @param string $sku
     * @return array
     */
    public function getProductBySku($sku)
    {
        return $this->_productRepository->get($sku);
    }

    /**
     * Retrieve cache interface
     *
     * @return CacheInterface
     */
    private function getCacheModel()
    {
        return $this->cacheInterface;
    }

    /**
     * Retrieve categories tree
     *
     * @param string|null $filter
     *
     * @return array
     */
    public function getCategoriesTree($filter = null)
    {
        if (!$this->_helperData->getAllowedCategoryIds()) {
            $categoryTree = $this->getCacheModel()->load('seller_category_tree_' . $this->filter);
            if ($categoryTree) {
                return json_encode($this->serializer->unserialize($categoryTree));
            }
        }

        $this->filter = $filter;
        $shownCategoriesIds = $this->getShownCategoryIds();

        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
        $collection = $this->categoryCollectionFactory->create();
        $collection->addAttributeToFilter('entity_id', ['in' => array_keys($shownCategoriesIds)])
            ->addAttributeToSelect(['name', 'is_active', 'parent_id']);

        $sellerCategory = [
            Category::TREE_ROOT_ID => [
                'value' => Category::TREE_ROOT_ID,
                'optgroup' => null,
            ],
        ];

        foreach ($collection as $category) {
            $catId = $category->getId();
            $catParentId = $category->getParentId();
            foreach ([$catId, $catParentId] as $categoryId) {
                if (!isset($sellerCategory[$categoryId])) {
                    $sellerCategory[$categoryId] = ['value' => $categoryId];
                }
            }

            $sellerCategory[$catId]['is_active'] = $category->getIsActive();
            $sellerCategory[$catId]['label'] = $category->getName();
            $sellerCategory[$catParentId]['optgroup'][] = &$sellerCategory[$catId];
        }
        if (!$this->_helperData->getAllowedCategoryIds()) {
            $this->getCacheModel()->save(
                $this->serializer->serialize($sellerCategory[Category::TREE_ROOT_ID]['optgroup']),
                'seller_category_tree_' . $filter,
                [
                    Category::CACHE_TAG,
                    \Magento\Framework\App\Cache\Type\Block::CACHE_TAG
                ]
            );
        }
        return json_encode($sellerCategory[Category::TREE_ROOT_ID]['optgroup']);
    }

    /**
     * Get Shown Category Ids
     *
     * @return array
     */
    public function getShownCategoryIds()
    {
        $storeId = $this->_helperData->getCurrentStoreId();
        $categoryCollection = $this->categoryCollectionFactory->create();
        if ($this->filter !== null) {
            $categoryCollection->addAttributeToFilter(
                'name',
                ['like' => $this->frameworkDbHelper->addLikeEscape($this->filter, ['position' => 'any'])]
            );
        }

        if ($this->_helperData->getAllowedCategoryIds()) {
            $allowedCategoryIds = explode(',', trim($this->_helperData->getAllowedCategoryIds()));
            $categoryCollection->addAttributeToSelect('path')
            ->addAttributeToFilter('entity_id', ['in' => $allowedCategoryIds])
            ->setStoreId($storeId);
        } else {
            $categoryCollection->addAttributeToSelect('path')
            ->addAttributeToFilter('entity_id', ['neq' => Category::TREE_ROOT_ID])
            ->setStoreId($storeId);
        }

        $shownCategoriesIds = [];

        /** @var \Magento\Catalog\Model\Category $category */
        foreach ($categoryCollection as $category) {
            foreach (explode('/', $category['path']) as $parentId) {
                $shownCategoriesIds[$parentId] = 1;
            }
        }

        return $shownCategoriesIds;
    }

    /**
     * Get Persistent Data for Product
     *
     * @return array
     */
    public function getPersistentData()
    {
        $persistentData = (array)$this->dataPersistor->get('seller_catalog_product');
        $fields = [
            "set" => "",
            "type" => "",
            "product" => [
                "name" => "",
                "category_ids" => [],
                "description" => "",
                "short_description" => "",
                "sku" => "",
                "price" => "",
                "special_price" => "",
                "special_from_date" => "",
                "special_to_date" => "",
                "product_has_weight" => 1,
                "weight" => "",
                "mp_product_cart_limit" => "",
                "visibility" => "",
                "tax_class_id" => "",
                "url_key" => "",
                "meta_title" => "",
                "meta_keyword" => "",
                "meta_description" => "",
                "quantity_and_stock_status" => [
                    "is_in_stock" => 1,
                    "qty" => ""
                ],
                "image" => "",
                "small_image" => "",
                "thumbnail" => ""
            ],
        ];

        $persistentData = $this->setFieldsValue($persistentData, $fields);
        $this->dataPersistor->clear('seller_catalog_product');
        return $persistentData;
    }

    /**
     * Validate and Set Default Values for Fields
     *
     * @param array $persistentData
     * @param array $fields
     *
     * @return array
     */
    public function setFieldsValue(&$persistentData, $fields)
    {
        foreach ($fields as $key => $field) {
            if (is_array($field)) {
                if (empty($persistentData[$key])) {
                    $persistentData[$key] = [];
                }

                $this->setFieldsValue($persistentData[$key], $fields[$key]);
            } else {
                if (empty($persistentData[$key])) {
                    $persistentData[$key] = $field;
                }
            }
        }

        return $persistentData;
    }
    /**
     * GetWysiwygUrl function
     *
     * @return string
     */
    public function getWysiwygUrl()
    {
        $currentTreePath = $this->wysiwygImages->idEncode(
            \Magento\Cms\Model\Wysiwyg\Config::IMAGE_DIRECTORY
        );
        $url =  $this->getUrl(
            'marketplace/wysiwyg_images/index',
            [
                'current_tree_path' => $currentTreePath
            ]
        );
        return $url;
    }

    /**
     * Get formatted price without currency symbol
     *
     * @param float $price
     * @return float
     */
    public function getFormattedPriceWithoutSymbol($price)
    {
        return $this->currency->format($price, ['display'=>\Magento\Framework\Currency::NO_SYMBOL], false);
    }
    /**
     * Get allowed product types
     *
     * @return array
     */
    public function getAllowedProductType()
    {
        return explode(',', $this->_helperData->getAllowedProductType());
    }


    public function getLanguagesOptions()
    {
        $options = $this->getOptionsJson('language_options');
        $this->logger->info('Languages Options: ' . print_r($options, true));
        return $options;
    }


    public function getSkillsOptions()
    {
        $options = $this->getOptionsJson('skill_options');
        $this->logger->info('Skills Options: ' . print_r($options, true));
        return $options;
    }

    public function getLanguageSenioritiesOptions()
    {
        $options = $this->getOptionsJson('language_seniority_options');
        $this->logger->info('Language Seniorities Options: ' . print_r($options, true));
        return $options;
    }


    public function getSkillSenioritiesOptions()
    {
        $options = $this->getOptionsJson('skill_seniority_options');
        $this->logger->info('Skill Seniorities Options: ' . print_r($options, true));
        return $options;
    }

    protected function getOptionsJson($attributeCode)
    {
        $attribute = $this->_attributeFactory->create()->loadByCode(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
        $this->logger->info('Attribute: ' . print_r($attribute->getData(), true));
        $options = $this->_optionCollectionFactory->create()->setAttributeFilter($attribute->getId())->setStoreFilter()->load();
        $this->logger->info('Options: ' . print_r($options->getData(), true));
        $result = [];
        foreach ($options as $option) {
            $result[] = ['label' => $option->getValue(), 'value' => $option->getValue()];
        }
        return json_encode($result);
    }
}
