<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\BaseResource;

/**
 * @property string $email
 */


class RetrieveUserByEmail extends BaseResource
{


    /**
     * @var \Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface
     */
    protected $clientSecretId;

    /**
     * @var string
     */
    protected $httpMethod = 'get';


    /**
     * @var string
     */
    protected $apiEndpoint = 'api/v2/users-by-email';


    /** 
     * @inheritDoc
     */
    public function get(Oauth0 $oauth0)
    {
        return $this->properties;
    }

    /**
     * @inheritDoc
     */
    public function getResponse()
    {
        $response = parent::getResponse();
        
        if (count($response) > 0) {
            return $response[0];
        }

        return [];
    }


}