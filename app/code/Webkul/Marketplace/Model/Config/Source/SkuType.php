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

namespace OrionAlliance\NewModule\Model\Config\Source;

/**
 * Used in creating product for getting sku type value.
 */
class SkuType
{
    /**
     * Options getter.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $data = [
            ['value' => 'static', 'label' => __('Static')],
            ['value' => 'dynamic', 'label' => __('Dynamic')],
        ];

        return $data;
    }
}
