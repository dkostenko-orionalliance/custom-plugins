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

namespace OrionAlliance\NewModule\Controller\Product;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use OrionAlliance\NewModule\Helper\Data as HelperData;
use Magento\Framework\Json\Helper\Data as JsonHelper;

/**
 * Marketplace Product Verifysku controller.
 * Verify SKU If avialable or not.
 */
class Verifysku extends Action
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $_productResourceModel;

    /**
     * @var HelperData
     */
    protected $helper;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * Construct
     *
     * @param Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResourceModel
     * @param HelperData $helper
     * @param JsonHelper $jsonHelper
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ResourceModel\Product $productResourceModel,
        HelperData $helper,
        JsonHelper $jsonHelper
    ) {
        $this->_productResourceModel = $productResourceModel;
        $this->helper = $helper;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * Verify Product SKU availability action.
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        $helper = $this->helper;
        $skuPrefix = $helper->getSkuPrefix();
        $isPartner = $helper->isSeller();
        $skuType = $helper->getSkuType();
        $params = $this->getRequest()->getParams();
        if ($isPartner == 1) {
            $sku = $params['sku'];
            $productId = $params['product_id'];
            if ($skuType == "dynamic") {
                $sku = $skuPrefix.$sku;
            }
            
            try {
                $id = $this->_productResourceModel->getIdBySku($sku);
                if ($id && ($id != $productId)) {
                    $avialability = 0;
                } else {
                    $avialability = 1;
                }
                $this->getResponse()->representJson(
                    $this->jsonHelper->jsonEncode(
                        ['avialability' => $avialability]
                    )
                );
            } catch (\Exception $e) {
                $this->helper->logDataInLogger(
                    "Controller_Product_Verifysku execute : ".$e->getMessage()
                );
                $this->getResponse()->representJson(
                    $this->jsonHelper->jsonEncode('')
                );
            }
        } else {
            return $this->resultRedirectFactory->create()->setPath(
                'marketplace/account/becomeseller',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
    }
}
