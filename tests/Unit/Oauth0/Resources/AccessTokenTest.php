<?php

namespace Tests\Unit\Oauth0\Resources;

use Tests\TestCase;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\AccessToken;


class AccessTokenTest extends TestCase
{

    protected $clientSecretId;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpClientSecretId();
    }

    public function testShouldRequestForAccessToken()
    {
        $accessToken = new AccessToken($this->clientSecretId);

        $accessToken->audience = 'api/v2/';
        $accessToken->grant_type = 'client_credentials';


        $properties = $accessToken->get(new Oauth0('https://oauth0-test.auth0.com'));

        $this->assertSame([
            'audience' => 'https://oauth0-test.auth0.com/api/v2/',
            'grant_type' => 'client_credentials',
            'client_id' => '123445',
            'client_secret' => 'DLK03909',
        ], $properties);
    }

}