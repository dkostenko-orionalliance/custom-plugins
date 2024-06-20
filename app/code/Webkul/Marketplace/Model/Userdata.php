<?php
namespace Webkul\Marketplace\Model;

use Magento\Framework\Model\AbstractModel;
use Webkul\Marketplace\Model\ResourceModel\Userdata as ResourceModel;

class Userdata extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class); // Initialize the resource model
    }
}
