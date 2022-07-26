<?php


namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\BaseResource;
use Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface;
use Crazymeeks\Oauth0\Contracts\Resources\ValidateMFAOTPInterface;

/**
 * @property string $grant_type
 * @property string $mfa_token
 * @property string $otp
 */

class ValidateMFAOTP extends BaseResource implements ValidateMFAOTPInterface
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

        $this->grant_type = "http://auth0.com/oauth/grant-type/mfa-otp";
    }
}