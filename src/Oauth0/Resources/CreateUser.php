<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\BaseResource;

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
     * @inheritDoc
     */
    public function get(Oauth0 $oauth0)
    {
        return $this->properties;
    }

    /**
     * @inheritDoc
     */
    protected function createDefaultProps(Oauth0 $oauth0)
    {
        
    }
}