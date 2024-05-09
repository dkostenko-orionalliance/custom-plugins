<?php
namespace OrionAlliance\NewModule\Controller\Adminhtml\Customer\Attribute;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use OrionAlliance\NewModule\Model\CustomerAttributeFactory;

class Create extends Action
{
    protected $resultPageFactory;
    protected $customerAttributeFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CustomerAttributeFactory $customerAttributeFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->customerAttributeFactory = $customerAttributeFactory;
    }

    public function execute()
    {
        // Logic for creating a new custom attribute
        $attribute = $this->customerAttributeFactory->create();
        $attribute->setData([
            'attribute_code' => 'example_attribute',
            'attribute_label' => 'Example Attribute'
            // Add more attribute data here
        ]);
        $attribute->save();

        // Redirect to the grid page or any other page
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index');
    }
}
