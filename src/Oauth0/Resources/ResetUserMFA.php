<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\BaseResource;
use Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface;
use Crazymeeks\Oauth0\Contracts\Resources\ValidateMFAOTPInterface;


class ResetUserMFA extends BaseResource
{


    /**
     * @var \Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface
     */
    protected $clientSecretId;

    /**
     * @var string
     */
    protected $httpMethod = 'delete';


    /**
     * @var string
     */
    protected $apiEndpoint = "api/v2/users/%s/authenticators";



    /**
    * @inheritDoc
    */
    public function getApiEndPoint()
    {
        return sprintf($this->apiEndpoint, $this->properties['user_id']);
    }


    /** 
     * @inheritDoc
     */
    public function get(Oauth0 $oauth0)
    {
        return [];
    }


}