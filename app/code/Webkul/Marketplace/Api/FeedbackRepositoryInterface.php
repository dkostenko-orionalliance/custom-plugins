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
 * FeedbackRepository CRUD Interface
 */
interface FeedbackRepositoryInterface
{
    /**
     * Retrieve feedback by id.
     *
     * @param int $id
     * @return \OrionAlliance\NewModule\Model\Feedback
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Save Feedback.
     *
     * @param \OrionAlliance\NewModule\Model\Feedback $subject
     * @return \OrionAlliance\NewModule\Model\Feedback
     */
    public function save(\OrionAlliance\NewModule\Model\Feedback $subject);

    /**
     * Retrieve all feedbacks.
     *
     * @param Magento\Framework\Api\SearchCriteriaInterface $creteria
     * @return Magento\Framework\Api\SearchResults
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $creteria);

    /**
     * Delete feedback.
     *
     * @param \OrionAlliance\NewModule\Model\Feedback $subject
     * @return boolean
     */
    public function delete(\OrionAlliance\NewModule\Model\Feedback $subject);

    /**
     * Delete feedback by id.
     *
     * @param int $id
     * @return boolean
     */
    public function deleteById($id);
}
