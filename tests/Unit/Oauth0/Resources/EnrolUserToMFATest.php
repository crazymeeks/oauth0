<?php


namespace Tests\Unit\Oauth0\Resources;

use Tests\TestCase;
use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Provider\ClientSecretId;
use Crazymeeks\Oauth0\Resources\EnrolUserToMFA;

class EnrolUserToMFATest extends TestCase
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

    public function testEnrolUserToMFA()
    {
        $resource = new EnrolUserToMFA($this->clientSecretId);

        $resource->authenticator_types = ['otp'];
        $resource->scope = 'enrol';
        $resource->audience = 'mfa';

        $oauth0 = new Oauth0('https://oauth0-test.auth0.com');

        $params = $resource->get($oauth0);

        $this->assertArrayHasKey('client_id', $params);
        $this->assertArrayHasKey('client_secret', $params);
    }
}