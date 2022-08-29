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

class UpdateUser extends BaseResource
{


    /**
     * @var string
     */
    protected $httpMethod = 'patch';


    /**
     * @var string
     */
    protected $apiEndpoint = "api/v2/users/%s";

    /**
     * Oauth0 user id
     *
     * @var string
     */
    protected $userId;


    /**
     * Set user id
     *
     * @param string $userId
     * 
     * @return $this
     */
    public function setId(string $userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Get user id
     *
     * @return string
     */
    public function getId()
    {
        return $this->userId;
    }

    /**
    * @inheritDoc
    */
    public function getApiEndPoint()
    {
        return sprintf($this->apiEndpoint, $this->getId());
    }

    /** 
     * @inheritDoc
     */
    public function get(Oauth0 $oauth0)
    {
        unset($this->properties['email']);// email must be updated
        return $this->properties;
    }

    /**
     * @inheritDoc
     */
    protected function createDefaultProps(Oauth0 $oauth0)
    {
        
    }
}