<?php
namespace Webkul\Marketplace\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Userdata extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('marketplace_userdata', 'entity_id'); // Initialize the table and the primary key
    }
}
