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
namespace OrionAlliance\NewModule\Controller\Wysiwyg;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use OrionAlliance\NewModule\Api\Data\WysiwygImageInterfaceFactory;

class Delete extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var WysiwygImageInterfaceFactory
     */
    protected $wysiwygImage;
    /**
     * @var \OrionAlliance\NewModule\Helper\Data
     */
    protected $mpHelper;

    /**
     * Initialization
     *
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory
     * @param WysiwygImageInterfaceFactory $wysiwygImage
     * @param PageFactory $resultPageFactory
     * @param \OrionAlliance\NewModule\Helper\Data $mpHelper
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory,
        WysiwygImageInterfaceFactory $wysiwygImage,
        PageFactory $resultPageFactory,
        \OrionAlliance\NewModule\Helper\Data $mpHelper
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->wysiwygImage = $wysiwygImage;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->mpHelper = $mpHelper;
        parent::__construct($context);
    }
   /**
    * DDelete wysiwg image
    *
    * @return void
    */
    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            $resultJson = $this->jsonResultFactory->create();
            $params = $this->getRequest()->getParams();
            if (isset($params["imageurl"])) {
                $checkVal = $this->deleteWysiwygImages($params["imageurl"]);
                if ($checkVal) {
                    return $resultJson->setData([
                        'result' => "success",
                    ]);
                }
            }
            return $resultJson->setData([
                'result' => "failure",
            ]);
        }
    }
    /**
     * DeleteWysiwygImages function
     *
     * @param string $url
     * @return bool
     */
    public function deleteWysiwygImages($url)
    {
        try {
            $descImage = $this->wysiwygImage->create()->getCollection()
            ->addFieldToFilter("file", ["like"=>'%'.$url.'%']);
            $descImage->walk('delete');
            return 1;
        } catch (\Exception $e) {
            $this->mpHelper->logDataInLogger(
                "Controller_Wysiwyg_Delete deleteWysiwygImages : ".$e->getMessage()
            );
            return 0;
        }
    }
}
