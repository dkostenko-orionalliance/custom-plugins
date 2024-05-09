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

namespace OrionAlliance\NewModule\Controller\Seller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use OrionAlliance\NewModule\Helper\Data as MpDataHelper;

/**
 * Webkul Marketplace Seller Usernameverify controller.
 */
class Usernameverify extends Action
{
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
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \OrionAlliance\NewModule\Model\ResourceModel\Seller\CollectionFactory $sellerCollectionFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \OrionAlliance\NewModule\Model\ResourceModel\Seller\CollectionFactory $sellerCollectionFactory
    ) {
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
