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
namespace OrionAlliance\NewModule\Block\Order\Creditmemo\Totals;

class Cod extends \OrionAlliance\NewModule\Block\Order\Totals\Cod
{
    /**
     * Add Cod total string
     *
     * @param string $currencyRate
     * @param string $after
     */
    protected function _addCodCharges($currencyRate, $after = 'discount')
    {
        $parent = $this->getParentBlock();
        $creditmemoId = $parent->getCreditmemo()->getId();
        $codchargesData = $this->orderCollection
        ->addFieldToFilter(
            'main_table.order_id',
            $this->getOrder()->getId()
        )->addFieldToFilter(
            'main_table.seller_id',
            $this->helper->getCustomerId()
        )->getTotalSellerCreditmemoCodCharges($creditmemoId);

        $codchargesTotal = $codchargesData[0]['cod_charges'];

        $codTotal = new \Magento\Framework\DataObject(
            [
                'code' => 'cod',
                'base_value' => $codchargesTotal,
                'value' => $this->helper->getCurrentCurrencyPrice($currencyRate, $codchargesTotal),
                'label' => __('Total COD Charges')
            ]
        );
        $this->getParentBlock()->addTotal($codTotal, $after);
        return $this;
    }
}
