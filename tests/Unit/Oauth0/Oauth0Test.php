<?php


namespace Tests\Unit\Oauth0;

use Mockery;
use Tests\TestCase;
use Ixudra\Curl\Builder;
use Ixudra\Curl\CurlService;
use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\CreateUser;
use Crazymeeks\Oauth0\Resources\AccessToken;
use Crazymeeks\Oauth0\Provider\ClientSecretId;

class Oauth0Test extends TestCase
{

    protected $clientSecretId;
    protected $curl;

    public function setUp(): void
    {
        parent::setUp();
        $this->curl = Mockery::mock(CurlService::class);
        $this->setClientSecretId();
    }

    protected function setClientSecretId()
    {
        $clientSecretId = new ClientSecretId();
        $clientSecretId->setClientId('1234')
                       ->setClientSecret('EYmF0nJef');
        $this->clientSecretId = $clientSecretId;
    }

    protected function mockOauthResponse(string $mockResponse)
    {
        
        $curl = $this->curl;
        $response = new \stdClass();
        $response->content = $mockResponse;
        $response->status = 200;
        $response->contentType = 'application/json';


        $curl->shouldReceive('to')
             ->with(Mockery::any())
             ->andReturnSelf();
        $curl->shouldReceive('withData')
             ->with(Mockery::any())
             ->andReturnSelf();
        $curl->shouldReceive('returnResponseObject')
             ->andReturnSelf();
        $curl->shouldReceive('post')
             ->andReturn($response);

        return $curl;
    }


    public function testShouldRequestAccessToken()
    {
        $accessToken = new AccessToken($this->clientSecretId);
        $accessToken->audience = 'https://oauth0-test.auth0.com/api/v2/';
        $accessToken->grant_type = 'client_credentials';
        $curl = $this->mockOauthResponse(file_get_contents(__DIR__ . '/MockedResponse/access-token.json'));
        $oauth0 = new Oauth0('https://oauth0-test.auth0.com', $curl);
        $resource = $oauth0->setResource($accessToken)
                           ->execute();

        $this->assertEquals($resource->getToken(), 'hIGNyZWF0ZTp1c2Vyc19hcHBfbWV0YWRhdGEgcmVhZDp1c2VyX2N1c3RvbV9ibG9ja3MgY3JlYXRlOnVzZXJfY3VzdG9tX2Jsb2NrcyBkZWxldGU6dXNlcl9jdXN0b21fYmxvY2tzIGNyZWF0ZTp1c2VyX3RpY2tldHMgcmVhZDpjbGllbnRzIHVwZGF0ZTpjbGllbnRzIGRlbGV0ZTpjbGllbnRzIGNyZWF0ZTpjbGllbnRzIHJlYWQ6Y2xpZW50X2tleXMgdXBkYXRlOmNsaWVudF9rZXlzIGRlbGV0ZTpjbGllbnRfa2V5cyBjcmVhdGU6Y2xpZW50X2tleXMgcmVhZDpjb25uZWN0aW9ucyB1cGRhdGU6Y29ubmVjdGlvbnMgZGVsZXRlOmNvbm5lY3Rpb25zIGNyZWF0ZTpjb25uZWN0aW9ucyByZWFkOnJlc291cmNlX3Nl');
        $this->assertEquals($resource->getScope(), 'read:client_grants create:client_grants');

    }


    public function testShouldCreateUser()
    {
        $resource = new CreateUser();
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


        $curl = $this->mockOauthResponse(file_get_contents(__DIR__ . '/MockedResponse/create-user.json'));
        $oauth0 = new Oauth0('https://oauth0-test.auth0.com', $curl);
        $resource = $oauth0->setResource($resource)
                           ->execute();

        $this->assertSame('john.doe@gmail.com', $resource->getResponse()->email);
    }
}