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
namespace OrionAlliance\NewModule\Block\Account\Navigation;

/**
 * Marketplace Navigation link
 *
 */
class ShippingMenu extends \OrionAlliance\NewModule\Block\Account\Navigation
{
    /**
     * IsShippineAvlForSeller
     *
     * @return boolean
     */
    public function isShippineAvlForSeller()
    {
        $activeCarriers = $this->shipconfig->getActiveCarriers();
        $status = false;
        foreach ($activeCarriers as $carrierCode => $carrierModel) {
            $allowToSeller = $this->_scopeConfig->getValue(
                'carriers/'.$carrierCode.'/allow_seller'
            );
            if ($allowToSeller) {
                $status = true;
            }
        }
        return $status;
    }
}
