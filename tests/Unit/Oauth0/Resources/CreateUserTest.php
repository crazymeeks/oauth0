<?php

namespace Tests\Unit\Oauth0\Resources;

use Tests\TestCase;
use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\CreateUser;

class CreateUserTest extends TestCase
{

    /**
     * @var \Crazymeeks\Oauth0\Resources\CreateUser
     */
    protected $userResource;

    public function setUp(): void
    {
        parent::setUp();
        $this->userResource = new CreateUser();
    }

    public function testShouldCreateUser()
    {
        $resource = $this->userResource;
        $resource->email = 'john.doe@example.com';
        $resource->email_verified = true;
        $resource->app_metadata = [];
        $resource->given_name = 'John';
        $resource->family_name = 'Doe';
        $resource->name = 'John Doe';
        $resource->nickname = 'J';
        $resource->picture = 'https://secure.gravatar.com/avatar/15626c5e0c749cb912f9d1ad48dba440?s=480&r=pg&d=https%3A%2F%2Fssl.gstatic.com%2Fs2%2Fprofiles%2Fimages%2Fsilhouette80.png';
        $resource->connection = 'Database-Connection';
        $resource->password = 'password1234';
        $resource->verify_email = false;


        $resource->setHeaders(array(
            'Authorization' => 'Bearer 4039430493049304'
        ));

        $this->assertArrayHasKey('email', $resource->get(new Oauth0('https://oauth0-test.auth0.com')));
    }
}