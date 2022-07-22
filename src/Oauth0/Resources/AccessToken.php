<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface;

class AccessToken
{

    /**
     * @var \Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface
     */
    protected $clientSecretId;

    public function __construct(ClientSecretIdInterface $clientSecretId)
    {
        $this->clientSecretId = $clientSecretId;
    }

    /**
     * Store our overloaded properties
     *
     * @var array
     */
    protected $properties = [];


    /**
     * @var string
     */
    protected $apiEndpoint = '/oauth/token';

    /**
     * Set api endpoint for this resource
     *
     * @param string $apiEndpoint
     * 
     * @return $this
     */
    public function setApiEndPoint(string $apiEndpoint)
    {
        $this->apiEndpoint = $apiEndpoint;
        return $this;
    }

    /**
     * Get api endpoint for this resource
     *
     * @return string
     */
    public function getApiEndPoint()
    {
        return $this->apiEndpoint;
    }


    /**
     * @inheritDoc
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }


    /**
     * @inheritDoc
     */
    public function __get($name)
    {
        return array_key_exists($name, $this->properties) ? $this->properties[$name] : null;
    }

    /**
     * Return properties
     *
     * @return array
     */
    public function get()
    {

        $this->client_id = $this->clientSecretId->getClientId();
        $this->client_secret = $this->clientSecretId->getClientSecret();
        return $this->properties;
    }
}
