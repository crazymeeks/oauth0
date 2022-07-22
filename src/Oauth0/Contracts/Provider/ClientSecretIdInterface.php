<?php

namespace Crazymeeks\Oauth0\Contracts\Provider;

interface ClientSecretIdInterface
{

    /**
     * Set tenant client id
     *
     * @param string $clientId
     * 
     * @return $this
     */
    public function setClientId(string $clientId);

    /**
     * Set tenant secret
     *
     * @param string $clientSecret
     * 
     * @return $this
     */
    public function setClientSecret(string $clientSecret);

    /**
     * Get tenant client id
     *
     * @return string
     */
    public function getClientId();

    /**
     * Get tenant secret
     *
     * @return string
     */
    public function getClientSecret();
}