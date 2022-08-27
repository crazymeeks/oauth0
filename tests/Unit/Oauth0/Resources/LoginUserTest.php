<?php


namespace Tests\Unit\Oauth0\Resources;

use Tests\TestCase;
use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\LoginUser;

class LoginUserTest extends TestCase
{



    public function setUp(): void
    {
        parent::setUp();

        $this->setUpClientSecretId();
    }


    public function testShouldLoginUser()
    {
        $resource = new LoginUser($this->clientSecretId);

        $resource->username = 'john.doe@example.com';
        $resource->password = 'password1234';
        $resource->connection = 'Connetion-Database';
        
        $params = $resource->get(new Oauth0('https://oauth0-test.auth0.com'));
        $this->assertSame('password', $params['grant_type']);
        $this->assertSame('openid', $params['scope']);
    }
}