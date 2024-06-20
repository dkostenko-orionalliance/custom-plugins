<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorAttributeManager
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\MpVendorAttributeManager\Model\VendorAttribute\Metadata\Form;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\Filter\DateTime;

class Date extends \Magento\Customer\Model\Metadata\Form\Date
{
    /**
     * @inheritdoc
     */
    public function extractValue(\Magento\Framework\App\RequestInterface $request)
    {
        $value = $this->_getRequestValue($request);
        
        if ($value) {
            $attributeCode = $this->getAttribute()->getAttributeCode();
            $explodedValue = explode('_', $attributeCode);
            if (is_array($explodedValue)) {
                if ('wkv' == $explodedValue[0]) {
                    $validate = ((new \DateTime)->createFromFormat('Y-m-d', $value))? true: false;
                    if ($validate) {
                        $value = date('Y-m-d h:i:s', strtotime($value));
                    } else {
                        throw new LocalizedException(
                            __('Invalid Date Format')
                        );
                    }
                }
            }
        }
        return $this->_applyInputFilter($value);
    }
}
