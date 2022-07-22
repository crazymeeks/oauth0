<?php

namespace Crazymeeks\Oauth0\Provider;

use Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface;

class ClientSecretId implements ClientSecretIdInterface
{

    /**
     * Tenant client id
     *
     * @var string
     */
    protected $clientId;

    /**
     * Tenant secret
     *
     * @var string
     */
    protected $clientSecret;

    /** @inheritDoc */
    public function setClientId(string $clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /** @inheritDoc */
    public function setClientSecret(string $clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /** @inheritDoc */
    public function getClientId()
    {
        return $this->clientId;
    }

    /** @inheritDoc */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }
}