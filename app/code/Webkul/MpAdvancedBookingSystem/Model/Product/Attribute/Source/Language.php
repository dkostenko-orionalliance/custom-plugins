<?php

namespace Webkul\MpAdvancedBookingSystem\Model\Product\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Language extends AbstractSource
{
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('English'), 'value' => 'english'],
                ['label' => __('French'), 'value' => 'french'],
                ['label' => __('Spanish'), 'value' => 'spanish'],
                // Add more languages as needed
            ];
        }
        return $this->_options;
    }
}
