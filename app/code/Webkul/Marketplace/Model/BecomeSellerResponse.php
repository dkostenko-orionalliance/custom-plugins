<?php
namespace Webkul\Marketplace\Model;

use Webkul\Marketplace\Api\Data\BecomeSellerResponseInterface;

class BecomeSellerResponse implements BecomeSellerResponseInterface
{
    /**
     * @var string
     */
    protected $message;

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}
