<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\BaseResource;
use Crazymeeks\Oauth0\Contracts\Resources\ValidateMFAOTPInterface;
use Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface;

/**
 * @property string $grant_type
 * @property string $scope
 */

class LoginUser extends BaseResource implements ValidateMFAOTPInterface
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
     * Get access token
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->getResponse()->access_token;
    }

    /**
     * Get id token
     *
     * @return string
     */
    public function getIdToken()
    {
        return $this->getResponse()->id_token;
    }

    /**
     * @inheritDoc
     */
    public function getScope()
    {
        return $this->getResponse()->scope;
    }

    /**
     * @inheritDoc
     */
    public function getExpiresIn()
    {
        return $this->getResponse()->expires_in;
    }

    /**
     * @inheritDoc
     */
    public function getTokenType()
    {
        return $this->getResponse()->token_type;
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
        if (!isset($this->grant_type)) {
            $this->grant_type = 'password';
        }

        if (!isset($this->scope)) {
            $this->scope = 'openid';
        }
    }

}