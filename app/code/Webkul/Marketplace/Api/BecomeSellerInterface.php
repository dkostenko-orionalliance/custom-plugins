<?php
namespace Webkul\Marketplace\Api;

use Webkul\Marketplace\Api\Data\BecomeSellerResponseInterface;


interface BecomeSellerInterface
{
    /**
     * Register a customer as a seller.
     *
     * @param string $profileUrl
     * @param string $country
     * @param string $number
     * @return BecomeSellerResponseInterface
     */
    public function execute($profileUrl, $country, $number);
}
