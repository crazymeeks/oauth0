<?php


namespace Tests\Unit\Oauth0\Resources;

use Tests\TestCase;
use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\ResetUserMFA;

class ResetUserMFATest extends TestCase
{


    protected $clientSecretId;

    public function setUp(): void
    {
        parent::setUp();

        
    }

    

    public function testShouldResetUserMFA()
    {
        $resource = new ResetUserMFA();

        $resource->user_id = 'auth0|62d9243068810176e8346c';
        
        $resource->setHeaders(array(
            'Authorization' => 'Bearer 4039430493049304'
        ));
        
        $params = $resource->get(new Oauth0('https://oauth0-test.auth0.com'));
        
        $this->assertSame('api/v2/users/auth0|62d9243068810176e8346c/authenticators', $resource->getApiEndPoint());
    }
}