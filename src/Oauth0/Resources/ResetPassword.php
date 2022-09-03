<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\BaseResource;
use Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface;

/**
 * @property string $client_id
 * @property string $email
 * @property string $connection
 */

class ResetPassword extends BaseResource
{
 
    
    /**
     * @var string
     */
    protected $httpMethod = 'post';


    /**
     * @var string
     */
    protected $apiEndpoint = 'dbconnections/change_password';


    public function __construct(ClientSecretIdInterface $clientSecretId)
    {
        $this->clientSecretId = $clientSecretId;
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
        unset($this->properties['client_secret']);
    }
}