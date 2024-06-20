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

namespace Webkul\MpAdvancedBookingSystem\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class SaveIndustryExpertise implements ObserverInterface
{
    protected $_productRepository;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->_productRepository = $productRepository;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        $request = $observer->getEvent()->getRequest();
        if ($request) {
            // $industryExpertise = $request->getParam('product')['industry_expertise']; // Ensure correct field path
            $industryExpertise = $request->getParam('industry_expertise');

            if (is_array($industryExpertise)) {
                $product->setData('industry_expertise', implode(',', $industryExpertise));
            }

            $this->_productRepository->save($product);
        }
    }
}
