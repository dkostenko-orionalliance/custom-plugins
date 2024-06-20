<?php

namespace Webkul\MpAdvancedBookingSystem\Model\Source\Product\Attribute\Backend;

use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\Exception\LocalizedException;

class LanguageSeniority extends AbstractBackend
{
    public function beforeSave($object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());
        
        if (is_array($value)) {
            // Serialize the array to store in a single attribute
            $object->setData($this->getAttribute()->getAttributeCode(), json_encode($value));
        } elseif (!is_string($value)) {
            throw new LocalizedException(__('Invalid value for Language and Seniority attribute'));
        }
        
        return parent::beforeSave($object);
    }

    public function afterLoad($object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());
        
        if (is_string($value)) {
            // Unserialize the value to get the array back
            $object->setData($this->getAttribute()->getAttributeCode(), json_decode($value, true));
        }
        
        return parent::afterLoad($object);
    }
}
