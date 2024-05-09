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
class PaymentMenu extends \OrionAlliance\NewModule\Block\Account\Navigation
{
    /**
     * IsPaymentAvlForSeller
     *
     * @return boolean
     */
    public function isPaymentAvlForSeller()
    {
        $activeMethods = $this->paymentConfig->getActiveMethods();
        $status = false;
        foreach ($activeMethods as $methodCode => $methodModel) {
            if (preg_match('/mp[^a-b][^0-9][^A-Z]/', $methodCode)) {
                $status = true;
            }
        }
        return $status;
    }
}
