<?php
namespace OrionAlliance\NewModule\Controller\Adminhtml\Customer\Attribute;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use OrionAlliance\NewModule\Model\CustomerAttributeFactory;

class Delete extends Action
{
    protected $customerAttributeFactory;

    public function __construct(
        Context $context,
        CustomerAttributeFactory $customerAttributeFactory
    ) {
        parent::__construct($context);
        $this->customerAttributeFactory = $customerAttributeFactory;
    }

    public function execute()
    {
        // Logic for deleting custom attribute
        $attributeId = $this->getRequest()->getParam('id');
        $attribute = $this->customerAttributeFactory->create()->load($attributeId);
        if ($attribute->getId()) {
            $attribute->delete();
        }

        // Redirect to the grid page or any other page
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index');
    }
}
