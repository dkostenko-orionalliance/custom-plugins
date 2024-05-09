<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   OrionAlliance_NewModule
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace OrionAlliance\NewModule\Model\ResourceModel;

class WysiwygImage extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
     /**
      * Initialize resource model.
      */
    protected function _construct()
    {
        $this->_init('wk_mp_wysiwyg_image', 'entity_id');
    }
}
