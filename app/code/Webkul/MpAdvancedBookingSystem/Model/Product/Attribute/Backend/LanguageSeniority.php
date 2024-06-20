<?php
namespace Webkul\MpAdvancedBookingSystem\Model\Product\Attribute\Backend;

use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\Exception\LocalizedException;

class LanguageSeniority extends AbstractBackend
{
    public function beforeSave($object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());
        if (!is_array($value)) {
            throw new LocalizedException(__('The value of the attribute must be an array.'));
        }

        // Ensure that each pair has both language and seniority
        foreach ($value as $pair) {
            if (empty($pair['language']) || empty($pair['seniority'])) {
                throw new LocalizedException(__('Each pair must have both language and seniority.'));
            }
        }

        // Encode value as JSON before saving
        $object->setData($this->getAttribute()->getAttributeCode(), json_encode($value));

        return parent::beforeSave($object);
    }

    public function afterLoad($object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());
        if ($value) {
            $object->setData($this->getAttribute()->getAttributeCode(), json_decode($value, true));
        }

        return parent::afterLoad($object);
    }
}
