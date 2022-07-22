<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Resources\BaseResource;
use Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface;

class CreateUser extends BaseResource
{


        /**
     * @var string
     */
    protected $httpMethod = 'post';


    /**
     * @var string
     */
    protected $apiEndpoint = 'api/v2/users';



    /**
     * Get response return by oauth0
     *
     * @return object
     */
    public function getResponse()
    {
        return $this->oauthResponse;
    }

    /** 
     * @inheritDoc
     */
    public function get()
    {

        return $this->properties;
    }
}