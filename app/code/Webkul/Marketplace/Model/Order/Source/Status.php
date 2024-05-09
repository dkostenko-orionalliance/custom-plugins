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
namespace OrionAlliance\NewModule\Model\Order\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status is used tp get the order available status
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \OrionAlliance\NewModule\Model\Orders
     */
    protected $marketplaceOrder;

   /**
    * Construct
    *
    * @param \OrionAlliance\NewModule\Model\Orders $marketplaceOrder
    */
    public function __construct(\OrionAlliance\NewModule\Model\Orders $marketplaceOrder)
    {
        $this->marketplaceOrder = $marketplaceOrder;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->marketplaceOrder->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
