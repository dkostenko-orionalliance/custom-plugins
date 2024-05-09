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
namespace OrionAlliance\NewModule\Controller\Adminhtml\Seller;

class Flags extends \Magento\Customer\Controller\Adminhtml\Index
{
    /**
     * Get Seller's Flags list
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $customerId = $this->initCurrentCustomer();
        $resultLayout = $this->resultLayoutFactory->create();
        $block = $resultLayout->getLayout()->getBlock('admin.seller.flags');
        $block->setCustomerId($customerId)->setUseAjax(true);
        return $resultLayout;
    }
}
