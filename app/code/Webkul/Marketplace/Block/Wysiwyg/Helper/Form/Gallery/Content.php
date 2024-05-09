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

namespace OrionAlliance\NewModule\Block\Wysiwyg\Helper\Form\Gallery;

use OrionAlliance\NewModule\Api\Data\WysiwygImageInterfaceFactory;

class Content extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\File\Size
     */
    protected $_fileSizeService;

    /**
     * @var WysiwygImageInterfaceFactory
     */
    protected $wysiwygImage;

    /**
     * @var \OrionAlliance\NewModule\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Backend\Block\Template\Context     $context
     * @param \Magento\Framework\File\Size                $fileSize
     * @param WysiwygImageInterfaceFactory                $wysiwygImage
     * @param \OrionAlliance\NewModule\Helper\Data             $helper
     * @param array                                       $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\File\Size $fileSize,
        WysiwygImageInterfaceFactory $wysiwygImage,
        \OrionAlliance\NewModule\Helper\Data $helper,
        array $data = []
    ) {
        $this->_fileSizeService = $fileSize;
        $this->wysiwygImage = $wysiwygImage;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }
    /**
     * Get file size
     *
     * @return \Magento\Framework\File\Size
     */
    public function getFileSizeService()
    {
        return $this->_fileSizeService;
    }
    /**
     * SaveImageDesc function
     *
     * @return \OrionAlliance\NewModule\Model\WysiwygImage
     */
    public function saveImageDesc()
    {
        $sellerId = $this->helper->getCustomerId();
        $wysiwygImage = $this->wysiwygImage->create()
                    ->getCollection()
                    ->addFieldToFilter("seller_id", $sellerId);
        return $wysiwygImage;
    }
}
