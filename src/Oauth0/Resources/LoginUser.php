<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\BaseResource;
use Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface;

class LoginUser extends BaseResource
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
     * Get authenticator type
     *
     * @return string
     */
    public function getAuthenticatorType()
    {
        return $this->getResponse()->authenticator_type;
    }

    /**
     * Get secret
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->getResponse()->secret;
    }

    /**
     * Get barcode uri with link so can be displayed in <img> tag
     *
     * @return string
     */
    public function getBarcodeUri()
    {
        return 'https://chart.googleapis.com/chart?chs=166x166&chld=L|0&cht=qr&chl=' . $this->getResponse()->barcode_uri;
    }

    /**
     * Get real barcode uri
     *
     * @return string
     */
    public function getRealBarcodeUri()
    {
        return $this->getResponse()->barcode_uri;
    }

    /**
     * Get recovery codes
     *
     * @return array
     */
    public function getRecoveryCodes()
    {
        return $this->getResponse()->recovery_codes;
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