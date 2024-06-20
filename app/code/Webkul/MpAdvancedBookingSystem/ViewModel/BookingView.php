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
namespace Webkul\MpAdvancedBookingSystem\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\UrlInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Checkout\Helper\Cart;
use Webkul\MpAdvancedBookingSystem\Helper\Data;
use Webkul\MpAdvancedBookingSystem\Helper\Customer;
use Magento\Wishlist\Helper\Data as WishlistHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Webkul\Marketplace\Helper\Data as MpHelper;
use Magento\Catalog\Helper\Output as OutputHelper;
use Webkul\MpAdvancedBookingSystem\Helper\Options as OptionsHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

class BookingView implements ArgumentInterface
{

    public const API_KEY_XML_PATH = "api_key";
    
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $httpRequest;
    
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlInterface;
    
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonSerializer;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $pricingHelper;

    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $cartHelper;

    /**
     * @var \Webkul\MpAdvancedBookingSystem\Helper\Data
     */
    protected $helper;

    /**
     * @var \Webkul\MpAdvancedBookingSystem\Helper\Customer
     */
    protected $customerHelper;

    /**
     * @var WishlistHelper
     */
    protected $wishlistHelper;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * @var MpHelper
     */
    protected $mpHelper;

    /**
     * @var OutputHelper
     */
    protected $outputHelper;

    /**
     * @var OptionsHelper
     */
    protected $optionsHelper;

     /**
     * @var \Magento\Directory\Model\ResourceModel\Country\CollectionFactory
     */
    protected $_countryCollectionFactory;

    protected $attributeFactory;
    protected $optionCollectionFactory;
    protected $_scopeConfig;
    protected $logger;

    
    /**
     * Constructor
     *
     * @param Http $httpRequest
     * @param UrlInterface $urlInterface
     * @param Json $jsonSerializer
     * @param PricingHelper $pricingHelper
     * @param Cart $cartHelper
     * @param Data $helper
     * @param Customer $customerHelper
     * @param WishlistHelper $wishlistHelper
     * @param JsonHelper $jsonHelper
     * @param MpHelper $mpHelper
     * @param OutputHelper $outputHelper
     * @param OptionsHelper $optionsHelper
     */
    public function __construct(
        Http $httpRequest,
        UrlInterface $urlInterface,
        Json $jsonSerializer,
        PricingHelper $pricingHelper,
        Cart $cartHelper,
        Data $helper,
        Customer $customerHelper,
        WishlistHelper $wishlistHelper,
        JsonHelper $jsonHelper,
        MpHelper $mpHelper,
        OutputHelper $outputHelper,
        OptionsHelper $optionsHelper,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        Attribute $attributeFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $optionCollectionFactory,
        ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger, // Add this line
    ) {
        $this->httpRequest = $httpRequest;
        $this->urlInterface = $urlInterface;
        $this->jsonSerializer = $jsonSerializer;
        $this->pricingHelper = $pricingHelper;
        $this->cartHelper = $cartHelper;
        $this->helper = $helper;
        $this->customerHelper = $customerHelper;
        $this->wishlistHelper = $wishlistHelper;
        $this->jsonHelper = $jsonHelper;
        $this->mpHelper = $mpHelper;
        $this->outputHelper = $outputHelper;
        $this->optionsHelper = $optionsHelper;
        $this->_countryCollectionFactory = $countryCollectionFactory;
        $this->attributeFactory = $attributeFactory;
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->logger = $logger; // Add this line
    }

    /**
     * Return MpAdvancedBookingSystem Data Helper
     *
     * @return object
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * Return MpAdvancedBookingSystem Customer Helper
     *
     * @return object
     */
    public function getCustomerHelper()
    {
        return $this->customerHelper;
    }
    
    /**
     * Return Request Http Object
     *
     * @return object
     */
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }
    
    /**
     * Return Pricing Data Helper
     *
     * @return object
     */
    public function getPricingHelper()
    {
        return $this->pricingHelper;
    }

    /**
     * Return Wishlist Data Helper
     *
     * @return object
     */
    public function getWishlistHelper()
    {
        return $this->wishlistHelper;
    }

    /**
     * Get country collection
     *
     * @return \Magento\Directory\Model\ResourceModel\Country\Collection
     */
    public function getCountryCollection()
    {
        $collection = $this->_countryCollectionFactory->create()->loadByStore();
        return $collection;
    }

    /**
     * Retrieve list of top destinations countries
     *
     * @return array
     */
    protected function getTopDestinations()
    {
        $destinations = (string)$this->_scopeConfig->getValue(
            'general/country/destinations',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return !empty($destinations) ? explode(',', $destinations) : [];
    }

    /**
     * Retrieve list of countries option array
     *
     * @return array
     */
    public function getCountryOptionArray()
    {
        $options = $this->getCountryCollection()
                ->setForegroundCountries($this->getTopDestinations())
                ->toOptionArray();
        return $options ?: [];
    }

    /**
     * Return Json Data Helper
     *
     * @return object
     */
    public function getJsonHelper()
    {
        return $this->jsonHelper;
    }

    /**
     * Return Marketplace Data Helper
     *
     * @return object
     */
    public function getMpHelper()
    {
        return $this->mpHelper;
    }

    /**
     * Return Options Helper
     *
     * @return object
     */
    public function getOptionsHelper()
    {
        return $this->optionsHelper;
    }

    /**
     * Return Catalog Output Helper
     *
     * @return object
     */
    public function getOutputHelper()
    {
        return $this->outputHelper;
    }

    /**
     * Return JSON object from Data Array
     *
     * @param array $data
     * @return object
     */
    public function getJsonEncoded($data)
    {
        return $this->jsonSerializer->serialize($data);
    }

    /**
     * Return Submit Question URL
     *
     * @return string $submitQuestionUrl
     */
    public function getSubmitQuestionUrl()
    {
        $submitQuestionUrl = $this->urlInterface->getUrl(
            "mpadvancebooking/hotelbooking/submitquestion",
            [
                "_secure" => $this->getHttpRequest()->isSecure()
            ]
        );
        return $submitQuestionUrl;
    }

    /**
     * Return Submit Answer URL
     *
     * @return string $submitAnswerUrl
     */
    public function getSubmitAnswerUrl()
    {
        $submitAnswerUrl = $this->urlInterface->getUrl(
            "mpadvancebooking/hotelbooking/submitanswer",
            [
                "_secure" => $this->getHttpRequest()->isSecure()
            ]
        );
        return $submitAnswerUrl;
    }

    /**
     * Return Contact URL
     *
     * @return string $contactUrl
     */
    public function getContactUrl()
    {
        $submitAnswerUrl = $this->urlInterface->getUrl(
            "mpadvancebooking/booking/contact",
            [
                "_secure" => $this->getHttpRequest()->isSecure()
            ]
        );
        return $submitAnswerUrl;
    }

    /**
     * Return Default Booking Product Data
     *
     * @param object $product
     * @return object
     */
    public function getDefaultBookingProductData($product)
    {
        $productId = $product->getId();
        $options = $this->helper->getProductOptions($productId);
        
        $bookingInfo = $this->helper->getBookingInfo($productId);
        
        $data = [
            "slots" => $this->helper->getFormattedSlots($productId),
            "parentId" => $this->helper->getParentSlotId($productId),
            "formKey" => $this->helper->getFormKey(),
            "productId" => $productId,
            "options" => $options,
            "slotsUrl" => $this->urlInterface->getUrl('mpadvancebooking/booking/slots'),
            "cartUrl" => $this->cartHelper->getAddUrl($product),
            "booking_type" => $bookingInfo['type']
        ];
        
        return $this->getJsonEncoded($data);
    }

    /**
     * GetGoogleApiKey
     */
    public function getGoogleApiKey()
    {
        return trim($this->helper->getConfigValue(self::API_KEY_XML_PATH));
    }

    // public function getLanguagesOptions()
    // {
    //     return $this->jsonSerializer->serialize($this->helper->getLanguageOptions());
    // }

    // public function getLanguageSenioritiesOptions()
    // {
    //     return $this->jsonSerializer->serialize($this->helper->getLanguageSeniorityOptions());
    // }

    public function getSkillsOptions()
    {
        return $this->jsonSerializer->serialize($this->helper->getSkillOptions());
    }

    public function getSkillsSenioritiesOptions()
    {
        return $this->jsonSerializer->serialize($this->helper->getSkillSeniorityOptions());
    }

    public function getIndustryExpertiseOptions()
    {
        // $attribute = $this->attributeFactory->loadByCode(\Magento\Catalog\Model\Product::ENTITY, 'industry_expertise_options');
        // $options = $attribute->getSource()->getAllOptions(false);
        $options = $this->getOptionsJsonAsArray('industry_expertise_options');
        return $options;
        // $options = $this->optionCollectionFactory->create()
        //     ->setAttributeFilter($attribute->getId())
        //     ->setStoreFilter()
        //     ->load()
        //     ->toOptionArray();

        // // Return options in a format suitable for use in the select dropdown
        // $result = [];
        // foreach ($options as $option) {
        //     $result[] = ['label' => $option['label'], 'value' => $option['value']];
        // }
        // return $result;
    }

    public function getProductName($product)
    {
        return $product->getName();
    }

    public function getProductDescription($product)
    {
        return $product->getDescription();
    }

    public function getCustomerName($product)
    {
        return $this->customerHelper->getCustomerNameByProductId($product->getId());
    }

    public function getCountryPic($product)
    {
        return $product->getData('country_pic');
    }

    public function getSkillSeniority($product)
    {
        return $product->getData('skill_seniority');
    }

    public function getLanguageSeniority($product)
    {
        return $product->getData('language_seniority');
    }

    public function getLanguagesOptions()
    {
        $options = $this->getOptionsJson('language_options');
        $this->logger->info('Languages Options: ' . print_r($options, true));
        return $options;
    }


    // public function getSkillsOptions()
    // {
    //     $options = $this->getOptionsJson('skill_options');
    //     $this->logger->info('Skills Options: ' . print_r($options, true));
    //     return $options;
    // }


    public function getLanguageSenioritiesOptions()
    {
        $options = $this->getOptionsJson('language_seniority_options');
        $this->logger->info('Language Seniorities Options Layout: ' . print_r($options, true));
        return $options;
    }


    public function getSkillSenioritiesOptions()
    {
        $options = $this->getOptionsJson('skill_seniority_options');
        $this->logger->info('Skill Seniorities Options Layout: ' . print_r($options, true));
        return $options;
    }

    protected function getOptionsJson($attributeCode)
    {
        $attribute = $this->attributeFactory->loadByCode(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
        $this->logger->info('Layout Attribute: ' . print_r($attribute->getData(), true));
        $options = $this->optionCollectionFactory->create()->setAttributeFilter($attribute->getId())->setStoreFilter()->load();
        $this->logger->info('Layout Options: ' . print_r($options->getData(), true));
        $result = [];
        foreach ($options as $option) {
            $result[] = ['label' => $option->getValue(), 'value' => $option->getValue()];
        }
        return json_encode($result);
    }


    protected function getOptionsJsonAsArray($attributeCode)
    {
        $attribute = $this->attributeFactory->loadByCode(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
        $this->logger->info('Layout Attribute: ' . print_r($attribute->getData(), true));
        $options = $this->optionCollectionFactory->create()->setAttributeFilter($attribute->getId())->setStoreFilter()->load();
        $this->logger->info('Layout Options: ' . print_r($options->getData(), true));
        $result = [];
        foreach ($options as $option) {
            $result[] = ['label' => $option->getValue(), 'value' => $option->getValue()];
        }
        return $result;
    }

    // public function getIndustryExpertiseOptions()
    // {
    //     $options = $this->getOptionsJson('industry_expertise');
    //     $this->_logger->debug('Industry Expertise Options: ' . print_r($options, true));
    //     return $options;
    // }

    // protected function getOptionsJson($attributeCode)
    // {
    //     $attribute = $this->attributeFactory->create()->loadByCode(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
    //     $options = $this->optionCollectionFactory->create()->setAttributeFilter($attribute->getId())->setStoreFilter()->load();
    //     $result = [];
    //     foreach ($options as $option) {
    //         $result[] = ['label' => $option->getValue(), 'value' => $option->getValue()];
    //     }
    //     return json_encode($result);
    // }
}
