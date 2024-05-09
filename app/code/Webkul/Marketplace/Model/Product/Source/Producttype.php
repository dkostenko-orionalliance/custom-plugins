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
 * Class Producttype is used tp get the product type options
 */
class Producttype implements OptionSourceInterface
{

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $manager;

    /**
     * Construct
     *
     * @param \Magento\Framework\Module\Manager $manager
     */
    public function __construct(
        \Magento\Framework\Module\Manager $manager
    ) {
        $this->manager = $manager;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            ['value' => 'simple', 'label' => __('Simple')],
            ['value' => 'downloadable', 'label' => __('Downloadable')],
            ['value' => 'virtual', 'label' => __('Virtual')],
            ['value' => 'configurable', 'label' => __('Configurable')]
        ];
        if ($this->manager->isEnabled('Webkul_MpBundleProduct')) {
            array_push($options, ['value' => 'bundle', 'label' => __('Bundle Product')]);
        }
        if ($this->manager->isEnabled('Webkul_MpGroupedProduct')) {
            array_push($options, ['value' => 'grouped', 'label' => __('Grouped Product')]);
        }
        return $options;
    }
}
