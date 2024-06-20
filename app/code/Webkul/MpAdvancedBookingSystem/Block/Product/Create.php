<?php

namespace Webkul\MpAdvancedBookingSystem\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as OptionCollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Webkul\MpAdvancedBookingSystem\Helper\Data as BookingHelper;
use Webkul\Marketplace\Model\SellerFactory; // Assuming you have a SellerFactory
use Webkul\Marketplace\Model\ProductFactory; // Add this line
use Webkul\Marketplace\Helper\Data as MpHelper; // Add this line

class Create extends Template
{
    protected $attributeFactory;
    protected $optionCollectionFactory;
    protected $logger;
    protected $sellerFactory;
    protected $productFactory; // Add this line
    protected $mpHelper; // Add this line

    public function __construct(
        Template\Context $context,
        Attribute $attributeFactory,
        OptionCollectionFactory $optionCollectionFactory,
        \Psr\Log\LoggerInterface $logger, // Add this line
        \Webkul\MpAdvancedBookingSystem\Helper\Data $helper,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Webkul\MpAdvancedBookingSystem\Helper\Data $bookingHelper,
        SellerFactory $sellerFactory, // Adjust the namespace based on your actual implementation
        ProductFactory $productFactory, // Add this line
        MpHelper $mpHelper, // Add this line
        array $data = []
    ) {
        $this->attributeFactory = $attributeFactory;
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->logger = $logger; // Add this line
        $this->helper = $helper;
        $this->customerRepository = $customerRepository;
        $this->bookingHelper = $bookingHelper;
        $this->sellerFactory = $sellerFactory;
        $this->productFactory = $productFactory; // Add this line
        $this->mpHelper = $mpHelper; // Add this line
        parent::__construct($context, $data);
    }

    public function getLanguagesOptions()
    {
        $options = $this->getOptionsJson('language_options');
        // Add this line to check if the data is correct
        // $this->logger->debug_print_backtrace('Languages Options 1: ' . print_r($options, true));
        return $options;
    }

    public function getSkillsOptions()
    {
        $options = $this->getOptionsJson('skill_options');
        // Add this line to check if the data is correct
        // $this->logger->debug_print_backtrace('Skills Options 1: ' . print_r($options, true));
        return $options;
    }

    public function getLanguageSenioritiesOptions()
    {
        $options = $this->getOptionsJson('language_seniority_options');
        // Add this line to check if the data is correct
        // $this->logger->debug_print_backtrace('Language Seniorities Options 1: ' . print_r($options, true));
        return $options;
    }

    public function getSkillSenioritiesOptions()
    {
        $options = $this->getOptionsJson('skill_seniority_options');
        // Add this line to check if the data is correct
        // $this->logger->debug_print_backtrace('Skill Seniorities Options 1: ' . print_r($options, true));
        return $options;
    }

    protected function getOptionsJson($attributeCode)
    {
        $attribute = $this->attributeFactory->loadByCode(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
        // $this->logger->debug_print_backtrace('Json Options 1 attribute: ' . print_r($attribute, true));
        $options = $this->optionCollectionFactory->create()->setAttributeFilter($attribute->getId())->setStoreFilter()->load();
        // $this->logger->debug_print_backtrace('Json Options 1 options: ' . print_r($options, true));
        $result = [];
        foreach ($options as $option) {
            $result[] = ['label' => $option->getValue(), 'value' => $option->getValue()];
        }
        return json_encode($result);
    }

    protected function getProductId()
    {
        return $this->helper->getProductId();
    }

    protected function getProduct()
    {
        $productId = $this->getProductId();
        return $this->helper->getProduct($this->getProductId());
    }

    protected function getProduct2()
    {
        $productId = $this->getProductId();
        $this->logger->debug('Product ID: ' . $productId); // Add this line for debugging
        $product = $this->productFactory->create()->load($productId); // Update this line
        if (!$product || !$product->getId()) {
            $this->logger->debug('Product not found or failed to load.'); // Add this line for debugging
        }
        return $product;
    }


    public function getLanguageSeniority()
    {
        $product = $this->getProduct();
        return $product ? $product->getData('language_seniority') : '';
    }

    public function getSkillSeniority()
    {
        $product = $this->getProduct();
        return $product ? $product->getData('skill_seniority') : '';
    }



    public function getDescription()
    {
        $product = $this->getProduct();
        return $product ? $product->getData('description') : '';
    }



    public function getShortDescription()
    {
        $product = $this->getProduct();
        return $product ? $product->getData('short_description') : '';
    }



    public function getCountry()
    {
        $product = $this->getProduct();
        return $product ? $product->getData('country_pic') : '';
    }


    public function getIndustryExpertise()
    {
        $product = $this->getProduct();
        return $product ? $product->getData('industry_expertise') : '';
    }

    public function getIndustryExpertiseOptions()
    {
        
        $options = $this->getOptionsJson('industry_expertise_options');
        // Add this line to check if the data is correct
        // $this->logger->debug_print_backtrace('Skills Options 1: ' . print_r($options, true));
        return $options;
    }

    public function getName()
    {
        $product = $this->getProduct();
        return $product ? $product->getData('name') : '';
    }


    public function getVendorOrCustomerName()
    {
        $product = $this->getProduct();
        if ($product) {
            $vendorId = $this->getVendorIdFromProduct($product);
            if ($vendorId) {
                $vendor = $this->sellerFactory->create()->load($vendorId); // Assuming load method exists
                return $vendor ? $vendor->getName() : '';
            }

            $customerId = $this->getCustomerIdFromProduct($product);
            if ($customerId) {
                $customer = $this->customerRepository->getById($customerId);
                return $customer ? $customer->getFirstname() . ' ' . $customer->getLastname() : '';
            }
        }
        return '';
    }

    public function getCustomerId()
    {
        $product = $this->getProduct();
        return $product ? $product->getCustomerId() : '';
    }

    protected function getVendorIdFromProduct($product)
    {
        // Replace 'vendor_attribute_code' with the actual attribute code for vendor
        return $product->getData('vendor_id');
    }

    protected function getCustomerIdFromProduct($product)
    {
        // Replace 'customer_attribute_code' with the actual attribute code for customer
        return $product->getData('customer_id');
    }


    protected function getSellerId()
    {
        $product = $this->getProduct2();
        // Replace 'customer_attribute_code' with the actual attribute code for customer
        return $product ? $product->getData('seller_id') : '';
    }


    protected function getSellerId2()
    {
        $product = $this->getProduct2();
        // Replace 'customer_attribute_code' with the actual attribute code for customer
        return $product ? $product->getSellerId() : '';
    }

    public function getSellerName($product)
    {
        $product = $this->getProduct2();
        $vendorId = $this->getVendorIdFromProduct($product);
        if ($vendorId) {
            $vendor = $this->sellerFactory->create()->load($vendorId);
            return $vendor->getName();
        }
        return '';
    }

    public function getVendorFirstName()
    {
        $productId = $this->getProductId();
        $data = $this->mpHelper->getSellerProductDataByProductId($productId);
        // $marketplaceProduct = $helper->getSellerProductDataByProductId($product['entity_id']);
        $sellerId = '';
        foreach ($data as $value) {
            $sellerId = $value['seller_id'];
        }
        if($sellerId) {
            // $vendor = $this->sellerFactory->create()->load($sellerId);
            $vendor = $this->customerRepository->getById($sellerId);
            if ($vendor) {
                return $vendor->getFirstname();
                // return $vendor->getName();
                // $vendorInfo = [
                //     // 'Name' => $vendor->getName(),
                //     'Firstname' => $vendor->getFirstname(),
                //     'Lastname' => $vendor->getLastname(),
                //     'FirstName' => $vendor->getFirstName(),
                //     'LastName' => $vendor->getLastName(),
                //     'Email' => $vendor->getEmail(), // Assuming getEmail() method exists
                //     // 'Company' => $vendor->getCompany() // Assuming getCompany() method exists
                // ];
                // return $vendor->getFirstname() + ' ' + $vendor->getLastname();
                // return implode(", ", array_map(
                //     function ($v, $k) {
                //         return sprintf("%s: %s", $k, $v);
                //     },
                //     $vendorInfo,
                //     array_keys($vendorInfo)
                // ));
            }
            return 'No vendor found';
        }
        return 'No seller id found';
        // return $sellerId;
    }

    public function getVendorLastName()
    {
        $productId = $this->getProductId();
        $data = $this->mpHelper->getSellerProductDataByProductId($productId);
        // $marketplaceProduct = $helper->getSellerProductDataByProductId($product['entity_id']);
        $sellerId = '';
        foreach ($data as $value) {
            $sellerId = $value['seller_id'];
        }
        if($sellerId) {
            // $vendor = $this->sellerFactory->create()->load($sellerId);
            $vendor = $this->customerRepository->getById($sellerId);
            if ($vendor) {
                return $vendor->getLastname();
                // return $vendor->getName();
                // $vendorInfo = [
                //     // 'Name' => $vendor->getName(),
                //     'Firstname' => $vendor->getFirstname(),
                //     'Lastname' => $vendor->getLastname(),
                //     'FirstName' => $vendor->getFirstName(),
                //     'LastName' => $vendor->getLastName(),
                //     'Email' => $vendor->getEmail(), // Assuming getEmail() method exists
                //     // 'Company' => $vendor->getCompany() // Assuming getCompany() method exists
                // ];
                // return $vendor->getFirstname() + ' ' + $vendor->getLastname();
                // return implode(", ", array_map(
                //     function ($v, $k) {
                //         return sprintf("%s: %s", $k, $v);
                //     },
                //     $vendorInfo,
                //     array_keys($vendorInfo)
                // ));
            }
            return 'No vendor found';
        }
        return 'No seller id found';
        // return $sellerId;
    }
}
