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
namespace OrionAlliance\NewModule\Block\Page;

class Header extends \Magento\Theme\Block\Html\Header\Logo
{
    /**
     * @var string
     */
    protected $_template = 'OrionAlliance_NewModule::layout2/page/header.phtml';

    /**
     * @var \OrionAlliance\NewModule\Helper\Data
     */
    protected $helper;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \OrionAlliance\NewModule\Helper\Data $helper
     * @param \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \OrionAlliance\NewModule\Helper\Data $helper,
        \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageHelper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct(
            $context,
            $fileStorageHelper,
            $data
        );
    }

    /**
     * Get seller shop name
     *
     * @return string
     */
    public function getSellerShopName()
    {
        $sellerId = $this->helper->getCustomerId();
        $collection = $this->helper->getSellerCollectionObj($sellerId);
        $shopName = '';
        foreach ($collection as $key => $value) {
            $shopName = $value->getShopTitle();
            if (empty($value->getShopTitle())) {
                $shopName = $value->getShopUrl();
            }
        }
        return $shopName;
    }

    /**
     * Get seller logo
     *
     * @return string
     */
    public function getSellerLogo()
    {
        $sellerId = $this->helper->getCustomerId();
        $collection = $this->helper->getSellerCollectionObj($sellerId);
        $logoPic = 'noimage.png';
        foreach ($collection as $key => $value) {
            $logoPic = $value->getLogoPic();
            if (empty($logoPic)) {
                $logoPic = 'noimage.png';
            }
        }
        return $logoPic;
    }

    /**
     * Get logo image URL
     *
     * @return string
     */
    public function getSellerDashboardLogoSrc()
    {
        if ($logo = $this->helper->getSellerDashboardLogoUrl()) {
            return $logo;
        }
        return $this->getLogoSrc();
    }

    /**
     * Get Helper Data
     *
     * @return \OrionAlliance\NewModule\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }
}
