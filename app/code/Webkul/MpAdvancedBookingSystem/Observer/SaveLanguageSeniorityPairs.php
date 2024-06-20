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

class SaveLanguageSeniorityPairs implements ObserverInterface
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
            $languageSeniority = $request->getParam('language_seniority');
            $skillSeniority = $request->getParam('skill_seniority');

            if ($languageSeniority) {
                $product->setData('language_seniority', $languageSeniority);
            }

            if ($skillSeniority) {
                $product->setData('skill_seniority', $skillSeniority);
            }

            $this->_productRepository->save($product);
        }
    }
}
