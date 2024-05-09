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

namespace OrionAlliance\NewModule\Block;

/*
 * Webkul Marketplace Sellerblock Block
 */
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Catalog\Model\Product;
use OrionAlliance\NewModule\Model\ResourceModel\ProductFlagReason\CollectionFactory;

class Sellerblock extends \Magento\Framework\View\Element\Template
{
    public const FLAG_REASON_ENABLE = 1;
    public const FLAG_REASON_DISABLE = 0;

    /**
     * @var Product
     */
    protected $_product = null;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $Customer;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $Session;

    /**
     * @var \OrionAlliance\NewModule\Helper\Data
     */
    protected $mpHelper;

    /**
     * @var \OrionAlliance\NewModule\Model\ResourceModel\ProductFlagReason\Collection
     */
    protected $reasonCollection;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param Customer $customer
     * @param \Magento\Customer\Model\Session $session
     * @param \OrionAlliance\NewModule\Helper\Data $mpHelper
     * @param array $data
     * @param CollectionFactory|null $reasonCollection
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        Customer $customer,
        \Magento\Customer\Model\Session $session,
        \OrionAlliance\NewModule\Helper\Data $mpHelper,
        array $data = [],
        CollectionFactory $reasonCollection = null
    ) {
        $this->Customer = $customer;
        $this->Session = $session;
        $this->_coreRegistry = $registry;
        $this->mpHelper = $mpHelper;
        $this->reasonCollection = $reasonCollection ?: \Magento\Framework\App\ObjectManager::getInstance()
                                  ->create(CollectionFactory::class);
        parent::__construct($context, $data);
    }

    /**
     * Get product information
     *
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }

        return $this->_product;
    }

    /**
     * GetProductFlagReasons is used to get the product Flag Reasons
     *
     * @return \OrionAlliance\NewModule\Model\ResourceModel\ProductFlagReason\Collection
     */
    public function getProductFlagReasons()
    {
        $reasonCollection = $this->reasonCollection->create()
                          ->addFieldToFilter('status', self::FLAG_REASON_ENABLE)
                          ->setPageSize(5);
        return $reasonCollection;
    }
}
