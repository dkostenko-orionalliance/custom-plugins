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
namespace OrionAlliance\NewModule\Block\Component;

use Magento\Framework\View\Element\Template\Context;

/**
 * Seller Product's Collection Block.
 */
class StepsWizard extends \Magento\Ui\Block\Component\StepsWizard
{
    /**
     * @var string
     */
    protected $_template = 'OrionAlliance_NewModule::stepswizard.phtml';

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;
     /**
      * Construct
      * @param Context                             $context
      * @param \Magento\Framework\Json\Helper\Data $jsonHelper
      * @param array                               $data
      */
    public function __construct(
        Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        array $data = []
    ) {
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context, $data);
    }

    /**
     * Function Json Encode
     *
     * @param  Array $data
     * @return json
     */
    public function jsonEncode($data)
    {
        return $this->jsonHelper->jsonEncode($data);
    }
}
