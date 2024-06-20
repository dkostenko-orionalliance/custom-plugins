<?php

namespace Webkul\MpAdvancedBookingSystem\Model\Product\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Seniority extends AbstractSource
{
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('Junior'), 'value' => 'junior'],
                ['label' => __('Mid'), 'value' => 'mid'],
                ['label' => __('Senior'), 'value' => 'senior'],
                // Add more seniority levels as needed
            ];
        }
        return $this->_options;
    }
}
