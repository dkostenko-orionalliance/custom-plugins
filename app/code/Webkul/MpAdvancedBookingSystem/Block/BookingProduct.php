<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpAdvancedBookingSystem
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\MpAdvancedBookingSystem\Block;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as OptionCollectionFactory;

/*
 * Webkul MpAdvancedBookingSystem BookingProduct Block
 */
class BookingProduct extends \Magento\Framework\View\Element\Template
{

    protected $attributeFactory;
    protected $optionCollectionFactory;
    protected $logger;

    public function __construct(
        Context $context,
        Attribute $attributeFactory,
        OptionCollectionFactory $optionCollectionFactory,
        \Psr\Log\LoggerInterface $logger, // Add this line
        array $data = []
    ) {
        $this->attributeFactory = $attributeFactory;
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->logger = $logger; // Add this line
        parent::__construct($context, $data);
    }

    /**
     * PrepareLayout
     *
     * @return Widget
     */
    protected function _prepareLayout()
    {
        $this->addChild('options_box', \Webkul\MpAdvancedBookingSystem\Block\Options\Option::class);

        $this->addChild(
            'import_button',
            \Magento\Backend\Block\Widget\Button::class,
            [
                'label' => __('Import Options'),
                'class' => 'add',
                'id' => 'import_new_defined_option'
            ]
        );

        return parent::_prepareLayout();
    }

    /**
     * GetAddButtonHtml
     *
     * @return string
     */
    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    /**
     * GetOptionsBoxHtml
     *
     * @return string
     */
    public function getOptionsBoxHtml()
    {
        return $this->getChildHtml('options_box');
    }

    public function getLanguagesOptions()
    {
        $options = $this->getOptionsJson('language_options');
        // Add this line to check if the data is correct
        $this->_logger->debug_print_backtrace('Languages Options 2: ' . print_r($options, true));
        return $options;
    }


    public function getSkillsOptions()
    {
        $options = $this->getOptionsJson('skill_options');
        // Add this line to check if the data is correct
        $this->_logger->debug_print_backtrace('Skills Options 2: ' . print_r($options, true));
        return $options;
    }

    public function getLanguageSenioritiesOptions()
    {
        $options = $this->getOptionsJson('language_seniority_options');
        // Add this line to check if the data is correct
        $this->_logger->debug_print_backtrace('Language Seniorities Options 2: ' . print_r($options, true));
        return $options;
    }

    public function getSkillSenioritiesOptions()
    {
        $options = $this->getOptionsJson('skill_seniority_options');
        // Add this line to check if the data is correct
        $this->_logger->debug_print_backtrace('Skill Seniorities Options 2: ' . print_r($options, true));
        return $options;
    }

    protected function getOptionsJson($attributeCode)
    {
        $attribute = $this->attributeFactory->loadByCode(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
        $this->_logger->debug_print_backtrace('Json Options 2 attribute: ' . print_r($attribute, true));
        $options = $this->optionCollectionFactory->create()->setAttributeFilter($attribute->getId())->setStoreFilter()->load();
        $this->_logger->debug_print_backtrace('Json Options 2 options: ' . print_r($options, true));
        $result = [];
        foreach ($options as $option) {
            $result[] = ['label' => $option->getValue(), 'value' => $option->getValue()];
        }
        return json_encode($result);
    }
}
