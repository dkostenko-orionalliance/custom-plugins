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
namespace OrionAlliance\NewModule\Model\Seller\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status is used tp get the seller available status
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \OrionAlliance\NewModule\Model\Seller
     */
    protected $marketplaceSeller;

    /**
     * Construct
     *
     * @param \OrionAlliance\NewModule\Model\Seller $marketplaceSeller
     */
    public function __construct(\OrionAlliance\NewModule\Model\Seller $marketplaceSeller)
    {
        $this->marketplaceSeller = $marketplaceSeller;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->marketplaceSeller->getAvailableStatuses();
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
