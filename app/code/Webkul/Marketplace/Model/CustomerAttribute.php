<?php
namespace OrionAlliance\NewModule\Model;

use Magento\Framework\Model\AbstractModel;

class CustomerAttribute extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('OrionAlliance\NewModule\Model\ResourceModel\CustomerAttribute');
    }
}
