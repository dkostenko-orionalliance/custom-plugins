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
 * Class AttributeSets is used tp get attribute sets
 */
class AttributeSets implements OptionSourceInterface
{
    /**
     * @var \OrionAlliance\NewModule\Helper\Data
     */
    protected $marketplaceHelper;

    /**
     * Constructor
     *
     * @param \OrionAlliance\NewModule\Helper\Data $marketplaceHelper
     */
    public function __construct(
        \OrionAlliance\NewModule\Helper\Data $marketplaceHelper
    ) {
        $this->marketplaceHelper = $marketplaceHelper;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->marketplaceHelper->getAllowedSets();
        return $availableOptions;
    }
}
