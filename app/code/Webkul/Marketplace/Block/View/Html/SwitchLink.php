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
namespace OrionAlliance\NewModule\Block\View\Html;

class SwitchLink extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * @var \OrionAlliance\NewModule\Helper\Data
     */
    private $helper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \OrionAlliance\NewModule\Helper\Data                  $helper
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \OrionAlliance\NewModule\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->helper->getIsSeparatePanel()) {
            if (false != $this->getTemplate()) {
                return parent::_toHtml();
            }
            $label = $this->escapeHtml($this->getLabel());
            return '<li><a ' . $this->getLinkAttributes() . ' >' . $label . '</a></li>';
        }
    }
}
