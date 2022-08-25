<?php


namespace Tests\Unit\Oauth0\Resources;

use Tests\TestCase;
use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\RetrieveUserByEmail;

class RetrieveUserByEmailTest extends TestCase
{


    protected $clientSecretId;

    public function setUp(): void
    {
        parent::setUp();

        
    }


    public function testShouldRetrieveUserByEmail()
    {
        $resource = new RetrieveUserByEmail();
        

        $resource->email = 'test@test.com';
        
        $resource->setHeaders(array(
            'Authorization' => 'Bearer 4039430493049304'
        ));
        
        $params = $resource->get(new Oauth0('https://oauth0-test.auth0.com'));
        
        $this->assertSame('test@test.com', $params['email']);
        
    }
}