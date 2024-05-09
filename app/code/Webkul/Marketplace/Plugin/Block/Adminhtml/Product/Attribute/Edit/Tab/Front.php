<?php
/**
 * @category  Webkul
 * @package   OrionAlliance_NewModule
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace OrionAlliance\NewModule\Plugin\Block\Adminhtml\Product\Attribute\Edit\Tab;

use Magento\Config\Model\Config\Source\Yesno;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

class Front
{
    /**
     * @var Yesno
     */
    protected $_yesNo;
    /**
     * @var Attribute
     */
    protected $attributeFactory;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

   /**
    * @param Yesno $yesNo
    * @param Attribute $attributeFactory
    * @param \Magento\Framework\Registry $coreRegistry
    */
    public function __construct(
        Yesno $yesNo,
        Attribute $attributeFactory,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_yesNo = $yesNo;
        $this->attributeFactory = $attributeFactory;
        $this->_coreRegistry = $coreRegistry;
    }

    /**
     * Get form  HTML
     *
     * @param \Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab\Front $subject
     * @param callable $proceed
     * @return string
     */
    public function aroundGetFormHtml(
        \Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab\Front $subject,
        callable $proceed
    ) {
        $attributeObject = $this->_coreRegistry->registry('entity_attribute');
        $priceType = false;
        $valueToShow = $attributeObject->getVisibleToSeller();
        $postData = $subject->getRequest()->getParams();
        $yesno = $this->_yesNo->toOptionArray();
        $form = $subject->getForm();
        if (is_object($form) && empty($priceType)) {
            $fieldset = $form->getElement('front_fieldset');
            $fieldset->addField(
                'visible_to_seller',
                'select',
                [
                    'name' => 'visible_to_seller',
                    'label' => __('Allow for Seller'),
                    'title' => __('Allow for Seller'),
                    'note' => __('Only configurable attributes will be visible to vendor'),
                    'values' => $yesno,
                    'value' => $valueToShow
                ]
            );
        }

        return $proceed();
    }
}
