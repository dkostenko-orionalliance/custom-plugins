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
namespace OrionAlliance\NewModule\Model\Product\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status is used tp get the product available status
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \OrionAlliance\NewModule\Model\Product
     */
    protected $marketplaceProduct;

    /**
     * Construct
     *
     * @param \OrionAlliance\NewModule\Model\Product $marketplaceProduct
     */
    public function __construct(\OrionAlliance\NewModule\Model\Product $marketplaceProduct)
    {
        $this->marketplaceProduct = $marketplaceProduct;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->marketplaceProduct->getAvailableStatuses();
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
