<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorAttributeManager
 */
namespace Webkul\MpVendorAttributeManager\Block\Adminhtml\Customer\Edit\CustomerTab;

use Magento\Framework\Registry;

class CustomerInformation extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $vendorAttributeFactory;
    protected $_coreRegistry;

    public const JS_TEMPLATE = 'customfields/customer/customer.phtml';

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Webkul\MpVendorAttributeManager\Model\VendorAttributeFactory $vendorAttributeFactory,
        Registry $registry,
        array $data = []
    ) {
        $this->vendorAttributeFactory = $vendorAttributeFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::JS_TEMPLATE);
        }
        return $this;
    }

    public function getCustomerAttributes()
    {
        $attributeUsedForCustomer = [0, 1];
        $customerAttributes = $this->vendorAttributeFactory->create()->getCollection()
            ->addFieldToFilter("attribute_used_for", ["in" => $attributeUsedForCustomer])
            ->addFieldToFilter("wk_attribute_status", "1");

        return $customerAttributes;
    }

    public function getCustomer()
    {
        return $this->_coreRegistry->registry('current_customer');
    }

    public function getCustomerCompositeAttribute()
    {
        $customer = $this->getCustomer();
        if ($customer) {
            $vendorAttributes = $this->vendorAttributeFactory->create()->load($customer->getId(), 'entity_id');
            $compositeField = $vendorAttributes->getData('composite_field');
            $secondPart = $vendorAttributes->getData('second_part');
            return ['composite_field' => $compositeField, 'second_part' => $secondPart];
        }
        return null;
    }

    public function shouldUseSecondPart()
    {
        $customer = $this->getCustomer();
        if ($customer) {
            $vendorAttributes = $this->vendorAttributeFactory->create()->load($customer->getId(), 'entity_id');
            $useSecondPart = $vendorAttributes->getData('use_second_part');
            return $useSecondPart == '1';
        }
        return false;
    }
}
