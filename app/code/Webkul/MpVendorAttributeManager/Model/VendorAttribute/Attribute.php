<?php

namespace Webkul\MpVendorAttributeManager\Model\VendorAttribute;

class Attribute extends \Magento\Eav\Model\Entity\Attribute
{
    public function beforeSave()
    {
        parent::beforeSave();

        // Add logic here to handle composite attributes
        if ($this->getFrontendInput() == 'composite') {
            $this->setBackendType('text'); // Or another appropriate type
        }

        return $this;
    }
}