<?php
namespace Webkul\Marketplace\Api\Data;

interface BecomeSellerResponseInterface
{
    /**
     * Get message
     *
     * @return string
     */
    public function getMessage();

    /**
     * Set message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message);
}
