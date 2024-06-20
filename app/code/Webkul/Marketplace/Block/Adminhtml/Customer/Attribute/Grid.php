<?php
namespace Webkul\Marketplace\Block\Adminhtml\Customer\Attribute;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Webkul\Marketplace\Model\CustomerAttributeFactory;

class Grid extends Template
{
    protected $customerAttributeFactory;

    public function __construct(
        Context $context,
        CustomerAttributeFactory $customerAttributeFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerAttributeFactory = $customerAttributeFactory;
    }

    public function getCustomAttributes()
    {
        // Load and return custom attributes
        return $this->customerAttributeFactory->create()->getCollection();
    }
}
