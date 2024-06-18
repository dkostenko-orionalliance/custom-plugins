<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorAttributeManager
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpVendorAttributeManager\ViewModel;

use Webkul\Marketplace\Helper\Data as MarketplaceHelper;

/**
 * MpVendorAttributeManager Helper View Model
 */
class HelperViewModel implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var MarketplaceHelper
     */
    protected $mpHelper;
    
    /**
     * @var \Webkul\MpVendorAttributeManager\Helper\Data $helper
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param MarketplaceHelper $mpHelper
     * @param \Webkul\MpVendorAttributeManager\Helper\Data $helper
     */
    public function __construct(
        MarketplaceHelper $mpHelper,
        \Webkul\MpVendorAttributeManager\Helper\Data $helper
    ) {
        $this->mpHelper = $mpHelper;
        $this->helper = $helper;
    }

    /**
     * Get Marketplace Helper
     *
     * @return object \Webkul\Marketplace\Helper\Data
     */
    public function getMarketplaceHelper()
    {
        return $this->mpHelper;
    }

    /**
     * Get MpVendorAttributeManager Helper
     *
     * @return object \Webkul\Marketplace\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }
}
