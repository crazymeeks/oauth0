<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Resources\BaseResource;
use Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface;

class AccessToken extends BaseResource
{

    /**
     * @var \Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface
     */
    protected $clientSecretId;

    /**
     * @var string
     */
    protected $httpMethod = 'post';


    /**
     * @var string
     */
    protected $apiEndpoint = 'oauth/token';

    public function __construct(ClientSecretIdInterface $clientSecretId)
    {
        $this->clientSecretId = $clientSecretId;
    }


    /**
     * Get access token returned by oauth0
     *
     * @return string
     */
    public function getToken()
    {
        return $this->oauthResponse->access_token;
    }

    /**
     * Get scope for the requested access token
     *
     * @return string
     */
    public function getScope()
    {
        return $this->oauthResponse->scope;
    }


    /** 
     * @inheritDoc
     */
    public function get()
    {

        $this->client_id = $this->clientSecretId->getClientId();
        $this->client_secret = $this->clientSecretId->getClientSecret();
        return $this->properties;
    }
}
