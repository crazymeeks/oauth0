<?php


namespace Tests\Unit\Oauth0\Resources;

use Tests\TestCase;
use Crazymeeks\Oauth0\Resources\LoginUser;
use Crazymeeks\Oauth0\Provider\ClientSecretId;

class LoginUserTest extends TestCase
{


    protected $clientSecretId;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpClientSecretId();
    }

    protected function setUpClientSecretId()
    {
        $clientSecretId = new ClientSecretId();

        $clientSecretId->setClientId('123445')
                       ->setClientSecret('DLK03909');

        $this->clientSecretId = $clientSecretId;
    }
    

    public function testShouldLoginUser()
    {
        $resource = new LoginUser($this->clientSecretId);

        $resource->username = 'john.doe@example.com';
        $resource->password = 'password1234';
        $resource->connection = 'Connetion-Database';
        
        $params = $resource->get();
        $this->assertSame('password', $params['grant_type']);
        $this->assertSame('openid', $params['scope']);
    }
}