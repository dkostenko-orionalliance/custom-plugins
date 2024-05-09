<?php
/**
 * Webkul Software.
 *
 * @category Webkul
 * @package OrionAlliance_NewModule
 * @author Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license https://store.webkul.com/license.html
 */

namespace OrionAlliance\NewModule\Api;

/**
 * ProductRepository CRUD Interface
 */
interface ProductRepositoryInterface
{
    /**
     * Get by id.
     *
     * @param int $id
     * @return \OrionAlliance\NewModule\Model\Product
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Save Record.
     *
     * @param \OrionAlliance\NewModule\Model\Product $subject
     * @return \OrionAlliance\NewModule\Model\Product
     */
    public function save(\OrionAlliance\NewModule\Model\Product $subject);

    /**
     * Get list
     *
     * @param Magento\Framework\Api\SearchCriteriaInterface $creteria
     * @return Magento\Framework\Api\SearchResults
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $creteria);

    /**
     * Delete Record.
     *
     * @param \OrionAlliance\NewModule\Model\Product $subject
     * @return boolean
     */
    public function delete(\OrionAlliance\NewModule\Model\Product $subject);

    /**
     * Delete recod by id.
     *
     * @param int $id
     * @return boolean
     */
    public function deleteById($id);
}
