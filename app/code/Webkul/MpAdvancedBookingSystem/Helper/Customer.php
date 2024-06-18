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
namespace Webkul\MpAdvancedBookingSystem\Helper;

use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\CustomerFactory;

class Customer extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var HttpContext
     */
    private $httpContext;


    /**
     * @var ProductRepository
     */
    protected $productRepository;

    protected $customerFactory;


    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Customer\Model\Session       $customerSession
     * @param HttpContext                           $httpContext
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        HttpContext $httpContext,
        ProductRepository $productRepository,
        CustomerFactory $customerFactory
    ) {
        $this->customerSession = $customerSession;
        $this->httpContext = $httpContext;
        $this->productRepository = $productRepository;
        $this->customerFactory = $customerFactory;
        parent::__construct($context);
    }

    /**
     * Get Customer
     */
    public function getCustomer()
    {
        return $this->customerSession->getCustomer();
    }

    /**
     * Check if customer is logged in
     *
     * @return bool
     */
    public function isCustomerLoggedIn()
    {
        return (bool)$this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }

    /**
     * GetCustomerName
     */
    public function getCustomerName()
    {
        return $this->getCustomer()->getName();
    }

    /**
     * GetCustomerEmail
     */
    public function getCustomerEmail()
    {
        return $this->getCustomer()->getEmail();
    }

    public function getCustomerNameByProductId($productId)
    {
        try {
            $product = $this->productRepository->getById($productId);
            $customerId = $product->getData('customer_id'); // Assuming 'customer_id' is the attribute code

            if ($customerId) {
                $customer = $this->customerFactory->create()->load($customerId);
                return $customer->getName();
            }

            return '';
        } catch (\Exception $e) {
            return '';
        }
    }
}
