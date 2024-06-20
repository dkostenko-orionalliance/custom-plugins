<?php
namespace Webkul\Marketplace\Model;

use Magento\Framework\Model\AbstractModel;

class CustomerAttribute extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Webkul\Marketplace\Model\ResourceModel\CustomerAttribute');
    }
}
