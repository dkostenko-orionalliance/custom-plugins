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
 * SaleslistRepository CRUD Interface
 */
interface SaleslistRepositoryInterface
{
    /**
     * Get record by id.
     *
     * @param int $id
     * @return \OrionAlliance\NewModule\Model\Saleslist
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Save record.
     *
     * @param \OrionAlliance\NewModule\Model\Saleslist $subject
     * @return \OrionAlliance\NewModule\Model\Saleslist
     */
    public function save(\OrionAlliance\NewModule\Model\Saleslist $subject);

    /**
     * Get list.
     *
     * @param Magento\Framework\Api\SearchCriteriaInterface $creteria
     * @return Magento\Framework\Api\SearchResults
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $creteria);

    /**
     * Delete record.
     *
     * @param \OrionAlliance\NewModule\Model\Saleslist $subject
     * @return boolean
     */
    public function delete(\OrionAlliance\NewModule\Model\Saleslist $subject);

    /**
     * Delete record by id.
     *
     * @param int $id
     * @return boolean
     */
    public function deleteById($id);
}
