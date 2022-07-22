<?php

namespace Tests\Unit\Oauth0\Resources;

use Tests\TestCase;

use Crazymeeks\Oauth0\Resources\AccessToken;
use Crazymeeks\Oauth0\Provider\ClientSecretId;

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

        $accessToken->audience = 'https://oauth0-test.auth0.com/api/v2/';
        $accessToken->grant_type = 'client_credentials';


        $properties = $accessToken->get();

        $this->assertSame([
            'audience' => 'https://oauth0-test.auth0.com/api/v2/',
            'grant_type' => 'client_credentials',
            'client_id' => '123445',
            'client_secret' => 'DLK03909',
        ], $properties);
    }

    protected function setUpClientSecretId()
    {
        $clientSecretId = new ClientSecretId();

        $clientSecretId->setClientId('123445')
                       ->setClientSecret('DLK03909');

        $this->clientSecretId = $clientSecretId;
    }
}