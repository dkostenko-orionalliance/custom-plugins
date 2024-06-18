<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorAttributeManager
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
declare(strict_types=1);

namespace Webkul\MpVendorAttributeManager\Model\Resolver\Admin;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Webkul\MpVendorAttributeManager\Api\VendorAttributeRepositoryInterface;

/**
 * VendorAttributeList resolver, used for GraphQL request processing
 */
class VendorAttributeList implements ResolverInterface
{
    public const ADMIN_USER_TYPE = 2;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var vendorAttributeRepositoryInterface
     */
    protected $vendorAttributeRepo;

    /**
     * @var \Webkul\MpVendorAttributeManager\Helper\Data
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param vendorAttributeRepositoryInterface $vendorAttributeRepo
     * @param \Webkul\MpVendorAttributeManager\Helper\Data $helper
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        vendorAttributeRepositoryInterface $vendorAttributeRepo,
        \Webkul\MpVendorAttributeManager\Helper\Data $helper
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->vendorAttributeRepo = $vendorAttributeRepo;
        $this->helper = $helper;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        /** @var ContextInterface $context */
        if ($context->getUserType() != self::ADMIN_USER_TYPE) {
            throw new GraphQlAuthorizationException(__('Unauthorized access. Only admin can access this information.'));
        }

        if (!isset($args['filter'])) {
            throw new GraphQlInputException(
                __("'filter' input argument is required.")
            );
        }
        $fieldName = key($args['filter']);
        $filterType = key($args['filter'][$fieldName]);
        $fieldValue = $args['filter'][$fieldName][$filterType];
        $searchCriteria = $this->searchCriteriaBuilder->addFilter($fieldName, $fieldValue, $filterType)->create();
        return $this->vendorAttributeRepo->getJoinedList($searchCriteria)->__toArray();
    }
}