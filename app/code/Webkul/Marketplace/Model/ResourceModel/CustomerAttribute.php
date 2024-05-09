<?php
namespace Webkul\Marketplace\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerAttribute extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('customer_custom_attributes', 'id');
    }
}
