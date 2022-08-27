<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\BaseResource;
use Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface;


/**
 * @property string $audience
 * @property string $grant_type
 */
class AccessToken extends BaseResource
{


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
    public function getAccessToken()
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
    public function get(Oauth0 $oauth0)
    {

        $this->createDefaultProps($oauth0);

        return $this->properties;
    }

    /**
     * @inheritDoc
     */
    protected function createDefaultProps(Oauth0 $oauth0)
    {
        parent::createDefaultProps($oauth0);
        $audience = rtrim(ltrim($this->audience, '/'), '/') . '/';
        $this->audience = sprintf("%s/%s", $oauth0->getHost(), $audience);

        if (!isset($this->grant_type)) {
            $this->grant_type = 'client_credentials';
        }
    }
}
