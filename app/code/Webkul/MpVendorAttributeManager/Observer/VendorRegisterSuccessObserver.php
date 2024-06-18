<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorAttributeManager
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpVendorAttributeManager\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Eav\Model\Entity;
use Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Model\Customer\Mapper;
use Magento\Framework\Api\DataObjectHelper;
use Webkul\MpVendorAttributeManager\Model\ResourceModel\VendorAttribute\CollectionFactory as VendorCollection;

class VendorRegisterSuccessObserver implements ObserverInterface
{
    /**
     * @var \Magento\Eav\Model\Entity
     */
    protected $_eavEntity;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory
     */
    protected $_attributeCollectionFactory;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $_customerRepository;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterfaceFactory
     */
    protected $_customerDataFactory;

    /**
     * @var \Magento\Customer\Model\Customer\Mapper
     */
    protected $_customerMapper;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $_dataObjectHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezoneInterface;

    /**
     * @var \Webkul\MpVendorAttributeManager\Model\ResourceModel\VendorAttribute\CollectionFactory
     */
    protected $vendorAttributeCollectionFactory;

    /**
     * Constructor function
     *
     * @param Entity $eavEntity
     * @param CollectionFactory $attributeCollectionFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerInterfaceFactory $customerDataFactory
     * @param Mapper $customerMapper
     * @param DataObjectHelper $dataObjectHelper
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface
     * @param VendorCollection $vendorAttributeCollectionFactory
     */
    public function __construct(
        Entity $eavEntity,
        CollectionFactory $attributeCollectionFactory,
        CustomerRepositoryInterface $customerRepository,
        CustomerInterfaceFactory $customerDataFactory,
        Mapper $customerMapper,
        DataObjectHelper $dataObjectHelper,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface,
        VendorCollection $vendorAttributeCollectionFactory
    ) {
        $this->_eavEntity = $eavEntity;
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
        $this->_customerRepository = $customerRepository;
        $this->_customerDataFactory = $customerDataFactory;
        $this->_customerMapper = $customerMapper;
        $this->_dataObjectHelper = $dataObjectHelper;
        $this->timezoneInterface = $timezoneInterface;
        $this->vendorAttributeCollectionFactory = $vendorAttributeCollectionFactory;
    }

    /**
     * Customer register event handler.
     *
     * Save vendor attributes
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $data = $observer['account_controller'];
        $paramData = $data->getRequest();
        $customer = $observer->getCustomer();
        $customerId = $customer->getId();
        $typeId = $this->_eavEntity->setType('customer')->getTypeId();
        $collection = $this->_attributeCollectionFactory->create()
                        ->setEntityTypeFilter($typeId)
                        ->addVisibleFilter()
                        ->setOrder('sort_order', 'ASC');
        $customData = $paramData->getPostValue();
        
        $customer = $this->_customerDataFactory->create();
        $customData = $this->setBooleanData($customData, $paramData);
       
        $customData['id'] = $customerId;
        if (!isset($customData['is_vendor_group'])) {
            $customData['is_vendor_group'] = 0;
        }

        $this->_dataObjectHelper->populateWithArray(
            $customer,
            $customData,
            \Magento\Customer\Api\Data\CustomerInterface::class
        );
        $customer->setData('ignore_validation_flag', true);
        $this->_customerRepository->save($customer);
    }

    /**
     * Set Values for Boolean Type Attribute
     *
     * @param array $customerData
     * @param object $paramData
     * @return array
     */
    protected function setBooleanData($customerData, $paramData)
    {
        $customerAttributeType = [0,1];
        $booleanAttributes = $this->vendorAttributeCollectionFactory->create()
                                ->getVendorAttributeCollection()
                                ->addFieldToFilter("frontend_input", ['eq' => 'boolean'])
                                ->addFieldToFilter("wk_attribute_status", ['eq' => 1])
                                ->addFieldToFilter("attribute_used_for", ["in" => $customerAttributeType]);

        if ($booleanAttributes->getSize()) {
            foreach ($booleanAttributes as $attribute) {
                $attributeCode = $attribute->getAttributeCode();
                $attributeValue = (boolean)$paramData->getPostValue($attributeCode);
                $customerData[$attributeCode] = $attributeValue;
            }
        }
        return $customerData;
    }
}
