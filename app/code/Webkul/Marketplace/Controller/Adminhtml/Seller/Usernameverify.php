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

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use OrionAlliance\NewModule\Helper\Data as MpDataHelper;

/**
 * Marketplace Seller Shop URL Verify controller.
 */
class Usernameverify extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    /**
     * @var \OrionAlliance\NewModule\Model\ResourceModel\Seller\CollectionFactory
     */
    protected $_sellerCollectionFactory;

    /**
     * Initialize dependencies
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \OrionAlliance\NewModule\Model\ResourceModel\Seller\CollectionFactory $sellerCollectionFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \OrionAlliance\NewModule\Model\ResourceModel\Seller\CollectionFactory $sellerCollectionFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_jsonHelper = $jsonHelper;
        $this->_sellerCollectionFactory = $sellerCollectionFactory;
        parent::__construct($context);
    }

    /**
     * Verify seller shop URL exists or not
     *
     * @return \Magento\Framework\App\Response\Http
     */
    public function execute()
    {
        $profileUrl = trim($this->getRequest()->getParam("profileurl", ""));
        if ($profileUrl == "" || $profileUrl == MpDataHelper::MARKETPLACE_ADMIN_URL) {
            $this->getResponse()->representJson($this->_jsonHelper->jsonEncode(1));
        } else {
            $collection = $this->_sellerCollectionFactory->create();
            $collection->addFieldToFilter('shop_url', $profileUrl);
            $this->getResponse()->representJson($this->_jsonHelper->jsonEncode($collection->getSize()));
        }
    }
}
