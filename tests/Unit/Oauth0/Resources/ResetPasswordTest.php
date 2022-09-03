<?php

namespace Tests\Unit\Oauth0\Resources;

use Tests\TestCase;
use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\ResetPassword;


class ResetPasswordTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpClientSecretId();
    }

    

    public function testShouldResetPassword()
    {
        $resource = new ResetPassword($this->clientSecretId);

        $resource->email = 'test@email.com';
        $resource->connection = 'Username-Password-Authentication';

        $params = $resource->get(new Oauth0('https://oauth0-test.auth0.com'));
        
        $this->assertArrayHasKey('client_id', $params);
        $this->assertArrayHasKey('email', $params);
        $this->assertArrayHasKey('connection', $params);
    }
}