<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\BaseResource;
use Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface;
use Crazymeeks\Oauth0\Contracts\Resources\EnrolUserToMFAInterface;

class EnrolUserToMFA extends BaseResource implements EnrolUserToMFAInterface
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
    protected $apiEndpoint = 'mfa/associate';


    public function __construct(ClientSecretIdInterface $clientSecretId)
    {
        $this->clientSecretId = $clientSecretId;
    }

    /**
     * @inheritDoc
     */
    public function getAuthenticatorType()
    {
        return $this->getResponse()->authenticator_type;
    }

    /**
     * @inheritDoc
     */
    public function getSecret()
    {
        return $this->getResponse()->secret;
    }

    /**
     * @inheritDoc
     */
    public function getBarcodeUri()
    {
        return 'https://chart.googleapis.com/chart?chs=166x166&chld=L|0&cht=qr&chl=' . $this->getResponse()->barcode_uri;
    }

    /**
     * @inheritDoc
     */
    public function getRealBarcodeUri()
    {
        return $this->getResponse()->barcode_uri;
    }

    /**
     * @inheritDoc
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

        $audience = rtrim(ltrim($this->audience, '/'), '/') . '/';

        $this->audience = sprintf("%s/%s", $oauth0->getHost(), $audience);

        if (!isset($this->scope)) {
            $this->scope = 'enrol';
        }

    }
}