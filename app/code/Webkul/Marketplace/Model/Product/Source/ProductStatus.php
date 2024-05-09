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
 * Class ProductStatus is used tp get Marketplace product status
 */
class ProductStatus implements OptionSourceInterface
{
    /**
     * @var \OrionAlliance\NewModule\Model\Product
     */
    protected $marketplaceProduct;

    /**
     * @var \OrionAlliance\NewModule\Helper\Data
     */
    protected $marketplaceHelper;

    /**
     * @param \OrionAlliance\NewModule\Model\Product $marketplaceProduct
     * @param \OrionAlliance\NewModule\Helper\Data   $marketplaceHelper
     */
    public function __construct(
        \OrionAlliance\NewModule\Model\Product $marketplaceProduct,
        \OrionAlliance\NewModule\Helper\Data $marketplaceHelper
    ) {
        $this->marketplaceProduct = $marketplaceProduct;
        $this->marketplaceHelper = $marketplaceHelper;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->marketplaceProduct->getAvailableStatuses();
        $helper = $this->marketplaceHelper;
        if ($helper->getIsProductApproval() || $helper->getIsProductEditApproval()) {
            $enabledStatusText = __('Approved');
            $disabledStatusText = __('Pending');
            $deniedStatusText = __('Denied');
        } else {
            $enabledStatusText = __('Approved');
            $disabledStatusText = __('Disapproved');
            $deniedStatusText = __('Denied');
        }
        $options = [];
        foreach ($availableOptions as $key => $value) {
            if ($helper->getIsProductApproval() || $helper->getIsProductEditApproval()) {
                $options[] = [
                    'label' =>  $value,
                    'row_label' =>  "<span class='wk-mp-grid-status wk-mp-grid-status-".$key."'>".$value."</span>",
                    'value' => $key,
                ];
            } else {
                if ($key == 1) {
                    $options[] = [
                        'label' =>  $enabledStatusText,
                        'row_label' => "<span class='wk-mp-grid-status
                        wk-mp-grid-status-".$key."'>".$enabledStatusText."</span>",
                        'value' => $key,
                    ];
                } elseif ($key == 2) {
                    $options[] = [
                        'label' =>  $disabledStatusText,
                        'row_label' => "<span class='wk-mp-grid-status
                        wk-mp-grid-status-".$key."'>".$disabledStatusText."</span>",
                        'value' => $key,
                    ];
                } else {
                    $options[] = [
                        'label' =>  $deniedStatusText,
                        'row_label' => "<span class='wk-mp-grid-status
                        wk-mp-grid-status-".$key."'>".$deniedStatusText."</span>",
                        'value' => $key,
                    ];
                }
            }
        }
        return $options;
    }
}
