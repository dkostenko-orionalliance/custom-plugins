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
namespace OrionAlliance\NewModule\Model;

use OrionAlliance\NewModule\Api\Data\SaleslistSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

/**
 * Marketplace SaleslistRepository Class
 */
class SaleslistRepository implements \OrionAlliance\NewModule\Api\SaleslistRepositoryInterface
{
    /**
     * @var \OrionAlliance\NewModule\Model\SaleslistFactory
     */
    protected $modelFactory;

    /**
     * @var \OrionAlliance\NewModule\Model\ResourceModel\Saleslist\CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var SearchResultFactory
     */
    protected $searchResultFactory;
    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * SaleslistRepository constructor.
     *
     * @param \OrionAlliance\NewModule\Model\SaleslistFactory $modelFactory
     * @param \OrionAlliance\NewModule\Model\ResourceModel\Saleslist\CollectionFactory $collectionFactory
     * @param SearchResultFactory $searchResultFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        \OrionAlliance\NewModule\Model\SaleslistFactory $modelFactory,
        \OrionAlliance\NewModule\Model\ResourceModel\Saleslist\CollectionFactory $collectionFactory,
        SearchResultFactory $searchResultFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->modelFactory = $modelFactory;
        $this->searchResultFactory=$searchResultFactory;
        $this->collectionProcessor=$collectionProcessor;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get saleslist by id
     *
     * @param int $id
     * @return \OrionAlliance\NewModule\Model\Saleslist
     */
    public function getById($id)
    {
        $model = $this->modelFactory->create()->load($id);
        if (!$model->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('The record with the "%1" ID doesn\'t exist.', $id)
            );
        }
        return $model;
    }

    /**
     * Save record
     *
     * @param \OrionAlliance\NewModule\Model\Saleslist $subject
     * @return \OrionAlliance\NewModule\Model\Saleslist
     */
    public function save(\OrionAlliance\NewModule\Model\Saleslist $subject)
    {
        try {
            $subject->save();
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __($exception->getMessage())
            );
        }
        return $subject;
    }

    /**
     * Get list
     *
     * @param Magento\Framework\Api\SearchCriteriaInterface $creteria
     * @return Magento\Framework\Api\SearchResults
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $creteria)
    {
        /** @var \OrionAlliance\NewModule\Model\ResourceModel\Saleslist\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($creteria, $collection);

        $collection->load();

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($creteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * Delete record
     *
     * @param \OrionAlliance\NewModule\Model\Saleslist $subject
     * @return boolean
     */
    public function delete(\OrionAlliance\NewModule\Model\Saleslist $subject)
    {
        try {
            $subject->delete();
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __($exception->getMessage())
            );
        }
        return true;
    }

    /**
     * Delete record by id
     *
     * @param int $id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }
}
