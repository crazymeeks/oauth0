<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\BaseResource;

/**
 * @property string $email
 * @property bool $email_verified
 * @property object $app_metadata
 * @property string $given_name
 * @property string $family_name
 * @property string $name
 * @property string $nickname
 * @property string $picture
 * @property string $connection
 * @property string $password
 * @property string $verify_email
 */

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