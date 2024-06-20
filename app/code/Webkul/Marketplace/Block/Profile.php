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

namespace Webkul\Marketplace\Block;

/*
 * Webkul Marketplace Seller Profile Block
 */
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Catalog\Block\Product\AbstractProduct;
use Webkul\Marketplace\Helper\Data as MpHelper;
use Webkul\Marketplace\Model\FeedbackFactory;
use Webkul\Marketplace\Model\ResourceModel\Product\CollectionFactory;
use Webkul\Marketplace\Model\ProductFactory as MpProductModel;
use Magento\Catalog\Model\ProductFactory;
use Webkul\Marketplace\Model\ResourceModel\SellerFlagReason\CollectionFactory as SellerFlagReason;
use Webkul\MpVendorAttributeManager\Helper\Data as MpVendorAttrHelper;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Profile extends AbstractProduct
{
    public const FLAG_REASON_ENABLE = 1;
    public const FLAG_REASON_DISABLE = 0;
    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $_postDataHelper;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $session;

    /**
     * @var \Magento\Framework\Stdlib\StringUtils
     */
    protected $stringUtils;

    /**
     * @var MpHelper
     */
    protected $mpHelper;

    /**
     * @var FeedbackFactory
     */
    protected $feedbackModel;

    /**
     * @var CollectionFactory
     */
    protected $mpProductCollection;

    /**
     * @var MpProductModel
     */
    protected $mpProductModel;

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Webkul\Marketplace\Model\ResourceModel\SellerFlagReason\Collection
     */
    protected $reasonCollection;
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $filterProvider;

    /**
     * Vendor attribute collection object
     *
     * @var MpVendorAttrHelper
     */
    protected $mpVendorAttrHelper;

    protected $customerRepository;
    protected $searchCriteriaBuilder;

    /**
     * Construct
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param Customer $customer
     * @param \Magento\Customer\Model\Session $session
     * @param \Magento\Framework\Stdlib\StringUtils $stringUtils
     * @param MpHelper $mpHelper
     * @param FeedbackFactory $feedbackModel
     * @param CollectionFactory $mpProductCollection
     * @param MpProductModel $mpProductModel
     * @param ProductFactory $productFactory
     * @param array $data
     * @param SellerFlagReason|null $reasonCollection
     * @param \Magento\Cms\Model\Template\FilterProvider|null $filterProvider
     * @param MpVendorAttrHelper $mpVendorAttrHelper
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        Customer $customer,
        \Magento\Customer\Model\Session $session,
        \Magento\Framework\Stdlib\StringUtils $stringUtils,
        MpHelper $mpHelper,
        FeedbackFactory $feedbackModel,
        CollectionFactory $mpProductCollection,
        MpProductModel $mpProductModel,
        ProductFactory $productFactory,
        array $data = [],
        CustomerRepositoryInterface $customerRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SellerFlagReason $reasonCollection = null,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider = null,
        MpVendorAttrHelper $mpVendorAttrHelper
    ) {
        $this->_postDataHelper = $postDataHelper;
        $this->urlHelper = $urlHelper;
        $this->Customer = $customer;
        $this->Session = $session;
        $this->stringUtils = $stringUtils;
        $this->mpHelper = $mpHelper;
        $this->feedbackModel = $feedbackModel;
        $this->mpProductCollection = $mpProductCollection;
        $this->mpProductModel = $mpProductModel;
        $this->productFactory = $productFactory;
        $this->reasonCollection = $reasonCollection ?: \Magento\Framework\App\ObjectManager::getInstance()
                                  ->create(SellerFlagReason::class);
        $this->filterProvider = $filterProvider ?: \Magento\Framework\App\ObjectManager::getInstance()
        ->create(\Magento\Cms\Model\Template\FilterProvider::class);
        parent::__construct($context, $data);
        $this->mpVendorAttrHelper = $mpVendorAttrHelper;
        $this->customerRepository = $customerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $partner = $this->getProfileDetail();
        if ($partner) {
            $title = $partner->getShopTitle();
            if (!$title) {
                $title = __('Marketplace Seller Profile');
            }
            $this->pageConfig->getTitle()->set($title);
            $description = $partner->getMetaDescription();
            if ($description) {
                $this->pageConfig->setDescription($description);
            } else {
                $this->pageConfig->setDescription(
                    $this->stringUtils->substr($partner->getCompanyDescription(), 0, 255)
                );
            }
            $keywords = $partner->getMetaKeywords();
            if ($keywords) {
                $this->pageConfig->setKeywords($keywords);
            }

            $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
            if ($pageMainTitle && $title) {
                $pageMainTitle->setPageTitle($title);
            }

            $this->pageConfig->addRemotePageAsset(
                $this->_urlBuilder->getCurrentUrl(''),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
        }

        return $this;
    }

    /**
     * Get Seller Profile Details
     *
     * @return \Webkul\Marketplace\Model\Seller | bool
     */
    public function getProfileDetail()
    {
        $helper = $this->mpHelper;
        return $helper->getProfileDetail(MpHelper::URL_TYPE_PROFILE);
    }

    /**
     * Get feed
     *
     * @return void
     */
    public function getFeed()
    {
        $partner = $this->getProfileDetail();
        if ($partner) {
            return $this->mpHelper->getFeedTotal($partner->getSellerId());
        } else {
            return [];
        }
    }

    /**
     * Get feed collection
     *
     * @return void
     */
    public function getFeedCollection()
    {
        $collection = [];
        $partner = $this->getProfileDetail();
        if ($partner) {
            $collection = $this->feedbackModel->create()
            ->getCollection()
            ->addFieldToFilter('status', ['neq' => 0])
            ->addFieldToFilter('seller_id', $partner->getSellerId())
            ->setOrder('entity_id', 'DESC')
            ->setPageSize(4)
            ->setCurPage(1);
        }

        return $collection;
    }

    /**
     * Get best sell products
     *
     * @return array
     */
    public function getBestsellProducts()
    {
        $products = [];
        $partner = $this->getProfileDetail();
        if ($partner) {
            $catalogProductWebsite = $this->mpProductCollection->create()->getTable('catalog_product_website');
            $helper = $this->mpHelper;
            if (count($helper->getAllWebsites()) == 1) {
                $websiteId = 0;
            } else {
                $websiteId = $helper->getWebsiteId();
            }
            $querydata = $this->mpProductModel->create()
                                ->getCollection()
                                ->addFieldToFilter(
                                    'seller_id',
                                    ['eq' => $partner->getSellerId()]
                                )
                                ->addFieldToFilter(
                                    'status',
                                    ['neq' => 2]
                                )
                                ->addFieldToSelect('mageproduct_id')
                                ->setOrder('mageproduct_id');
            $products = $this->productFactory->create()->getCollection();
            $products->addAttributeToSelect('*');
            $products->addAttributeToFilter('entity_id', ['in' => $querydata->getAllIds()]);
            $products->addAttributeToFilter('visibility', ['in' => [4]]);
            $products->addAttributeToFilter('status', 1);
            if ($websiteId) {
                $products->getSelect()
                ->join(
                    ['cpw' => $catalogProductWebsite],
                    'cpw.product_id = e.entity_id'
                )->where(
                    'cpw.website_id = '.$websiteId
                );
            }
            $products->setPageSize(4)->setCurPage(1)->setOrder('entity_id');
        }

        return $products;
    }

    /**
     * GetSellerFlagReasons is used to get the seller Flag Reasons
     *
     * @return \Webkul\Marketplace\Model\ResourceModel\SellerFlagReason\Collection
     */
    public function getSellerFlagReasons()
    {
        $reasonCollection = $this->reasonCollection->create()
                          ->addFieldToFilter('status', self::FLAG_REASON_ENABLE)
                          ->setPageSize(5);
        return $reasonCollection;
    }
    /**
     * GetFilterData function
     *
     * @param [string] $content
     * @return string
     */
    public function getFilterData($content)
    {
        if ($content != "") {
            return $this->filterProvider->getPageFilter()->filter($content);
        }
        return $content;
    }

    /**
     * Get Custmer by Id
     *
     * @param int $customerId
     * @return array
     */
    public function getCustomer($customerId)
    {
        if ($customerId == '') {
            $customerId = $this->Session->getCustomer()->getId();
        }
        return $this->Customer->load($customerId);
    }


    public function getCustomer2($identifier)
    {
        try {
            if (is_numeric($identifier)) {
                // If identifier is numeric, assume it's customerId
                return $this->customerRepository->getById($identifier);
            } else {
                // Otherwise, assume it's sellerId and search by custom attribute (adjust attribute code if needed)
                $searchCriteria = $this->searchCriteriaBuilder
                    ->addFilter('seller_id', $identifier, 'eq')
                    ->create();
                $customers = $this->customerRepository->getList($searchCriteria)->getItems();
                return reset($customers); // return the first customer found
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null; // Or handle the exception as needed
        }
    }

    public function getCustomerAttribute($customer, $attributeCode)
    {
        $getterMethod = 'get' . str_replace('_', '', ucwords($attributeCode, '_'));
        if (method_exists($customer, $getterMethod)) {
            return $customer->$getterMethod();
        }
        return null;
    }

    // /**
    //  * Return custom attributes collection
    //  *
    //  * @param boolean $status
    //  * @return \Webkul\MpVendorAttributeManager\Model\ResourceModel\VendorAttribute\Collection
    //  */
    // private function getAttributeCollection()
    // {
    //     try {
    //         $vendorCollection = $this->vendorAttributeCollection->create();
    //         $vendorAttribute = $vendorCollection->getTable('marketplace_vendor_attribute');

    //         $typeId = $this->_eavEntity->setType('customer')->getTypeId();
    //         $collection = $this->_attributeCollection->create()
    //             ->setEntityTypeFilter($typeId)
    //             ->setOrder('sort_order', 'ASC');
    //         $collection->getSelect()
    //         ->join(
    //             ["vendor_attr" => $vendorAttribute],
    //             "vendor_attr.attribute_id = main_table.attribute_id"
    //         );

    //         return $collection;
    //     } catch (\Exception $ex) {
    //         return false;
    //     }

    //     return false;
    // }

    /**
     * Get Attribute Collection
     *
     * @param  boolean $isSeller
     *
     * @return collection
     */
    public function getAttributeCollection($isSeller = false)
    {
        return $this->mpVendorAttrHelper->getAttributeCollection($isSeller);
    }


    /**
     * Get Attribute Collection by Attribute Group
     *
     * @param  boolean $groupId
     * @param  boolean $isSeller
     *
     * @return collection
     */
    public function getAttributeCollectionByGroup($groupId, $isSeller = false)
    {
        $groupAttributes = $this->mpVendorAttrHelper->getAttributesByGroupId($groupId);
        if ($groupAttributes->getSize()) {
            $attributeUsedFor = [0,1];
            if ($isSeller) {
                $attributeUsedFor = [0,2];
            }

            $attributeCollection = $groupAttributes->addFieldToFilter(
                "vat.attribute_used_for",
                ["in" => $attributeUsedFor]
            );

            return $attributeCollection;
        }
        return false;
    }
}
