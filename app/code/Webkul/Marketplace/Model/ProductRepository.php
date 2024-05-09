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

use OrionAlliance\NewModule\Api\Data\ProductSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

/**
 * Marketplace ProductRepository Class
 */
class ProductRepository implements \OrionAlliance\NewModule\Api\ProductRepositoryInterface
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
     * ProductRepository constructor.
     * @param \OrionAlliance\NewModule\Model\ProductFactory $modelFactory
     * @param \OrionAlliance\NewModule\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param SearchResultFactory $searchResultFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        \OrionAlliance\NewModule\Model\ProductFactory $modelFactory,
        \OrionAlliance\NewModule\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        SearchResultFactory $searchResultFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultFactory=$searchResultFactory;
        $this->collectionProcessor=$collectionProcessor;
    }

    /**
     * Get marketplace product by id
     *
     * @param int $id
     * @return \OrionAlliance\NewModule\Model\Product
     */
    public function getById($id)
    {
        $model = $this->modelFactory->create()->load($id);
        if (!$model->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('The product with the "%1" ID doesn\'t exist.', $id)
            );
        }
        return $model;
    }

    /**
     * Save marketplace product
     *
     * @param \OrionAlliance\NewModule\Model\Product $product
     * @return \OrionAlliance\NewModule\Model\Product
     */
    public function save(\OrionAlliance\NewModule\Model\Product $product)
    {
        try {
            $product->save();
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __($exception->getMessage())
            );
        }
        return $product;
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
     * Delete product
     *
     * @param \OrionAlliance\NewModule\Model\Product $product
     * @return boolean
     */
    public function delete(\OrionAlliance\NewModule\Model\Product $product)
    {
        try {
            $product->delete();
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __($exception->getMessage())
            );
        }
        return true;
    }

    /**
     * Delete product by id
     *
     * @param int $id
     * @return boolean
     */
    public function deleteById($id)
    {
        $product = $this->get($id);

        return $this->delete($product);
    }
}
