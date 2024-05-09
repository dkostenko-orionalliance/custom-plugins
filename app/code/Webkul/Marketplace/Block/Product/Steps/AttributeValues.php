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
/**
 * Marketplace block for fieldset of configurable product.
 */

namespace OrionAlliance\NewModule\Block\Product\Steps;

class AttributeValues extends \Magento\Ui\Block\Component\StepsWizard\StepAbstract
{
    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return __('Attribute Values');
    }
}
