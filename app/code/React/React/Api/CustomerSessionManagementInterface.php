<?php
namespace React\React\Api;

interface CustomerSessionManagementInterface
{
    /**
     * Sets the customer session by token.
     *
     * @param string $token
     * @return bool
     */
    public function setCustomerSession($token);
}
