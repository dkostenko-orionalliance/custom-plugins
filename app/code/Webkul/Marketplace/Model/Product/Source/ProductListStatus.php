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
 * Class ProductListStatus is used tp get product list status
 */
class ProductListStatus implements OptionSourceInterface
{
    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $productStatus;

    /**
     * @var \OrionAlliance\NewModule\Helper\Data
     */
    protected $marketplaceHelper;

    /**
     * Construct
     *
     * @param \OrionAlliance\NewModule\Helper\Data $marketplaceHelper
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
     */
    public function __construct(
        \OrionAlliance\NewModule\Helper\Data $marketplaceHelper,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
    ) {
        $this->marketplaceHelper = $marketplaceHelper;
        $this->productStatus = $productStatus;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->productStatus->getOptionArray();
        $helper = $this->marketplaceHelper;
        if ($helper->getIsProductApproval() || $helper->getIsProductEditApproval()) {
            $enabledStatusText = __('Approved');
            $disabledStatusText = __('Pending');
        } else {
            $enabledStatusText = __('Enabled');
            $disabledStatusText = __('Disabled');
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
                } else {
                    $options[] = [
                        'label' =>  $disabledStatusText,
                        'row_label' => "<span class='wk-mp-grid-status
                        wk-mp-grid-status-".$key."'>".$disabledStatusText."</span>",
                        'value' => $key,
                    ];
                }
            }
        }
        return $options;
    }
}
