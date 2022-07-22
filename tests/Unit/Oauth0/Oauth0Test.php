<?php


namespace Tests\Unit\Oauth0;

use Mockery;
use Tests\TestCase;
use Ixudra\Curl\CurlService;
use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\LoginUser;
use Crazymeeks\Oauth0\Resources\CreateUser;
use Crazymeeks\Oauth0\Resources\AccessToken;
use Crazymeeks\Oauth0\Provider\ClientSecretId;
use Crazymeeks\Oauth0\Resources\EnrolUserToMFA;
use Crazymeeks\Oauth0\Exception\ResourceException;

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

    protected function mockOauthResponse(string $mockResponse, int $statusCode = 200, $curl = null)
    {
        
        $curl = $curl ? $curl : $this->curl;
        $response = new \stdClass();
        $response->content = $mockResponse;
        $response->status = $statusCode;
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
        $resource = new AccessToken($this->clientSecretId);
        $resource->audience = 'https://oauth0-test.auth0.com/api/v2/';
        $resource->grant_type = 'client_credentials';
        $curl = $this->mockOauthResponse(file_get_contents(__DIR__ . '/MockedResponse/access-token.json'));
        $oauth0 = new Oauth0('https://oauth0-test.auth0.com', $curl);
        $resource = $oauth0->setResource($resource)
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

    public function testShouldLoginUserWith2FA()
    {
        $resource = new LoginUser($this->clientSecretId);

        $resource->username = 'john.doe@example.com';
        $resource->password = 'password1234';
        $resource->connection = 'Connetion-Database';


        $curl = $this->mockOauthResponse(file_get_contents(__DIR__ . '/MockedResponse/login-user-with-mfa-required.json'), 403);
        
        $oauth0 = new Oauth0('https://oauth0-test.auth0.com', $curl);

        try {
            $resource = $oauth0->setResource($resource)
                           ->execute();
        } catch (ResourceException $e) {
            // oauth0 mfa is required
            // extra steps needs to be done by the developer
            // when mfa is required. Dev can actually copy
            // and paste this code

            $response = json_decode($e->getMessage());
            $curl = Mockery::mock(CurlService::class);
            $curl = $this->mockOauthResponse(file_get_contents(__DIR__ . '/MockedResponse/user-enrol-to-mfa.json'), 200, $curl);
            $curl->shouldReceive('withHeaders')
                 ->with(Mockery::any())
                 ->andReturnSelf();
            $oauth0 = new Oauth0('https://oauth0-test.auth0.com', $curl);

            $resource = new EnrolUserToMFA($this->clientSecretId);

            $resource->scope = 'enrol'; // optional
            $resource->audience = 'mfa';
            $resource->setHeaders(array(
                'Authorization' => sprintf("Bearer %s", $response->mfa_token),
            ));

            $resource = $oauth0->setResource($resource)
                           ->execute();

            $this->assertSame('otp', $resource->getAuthenticatorType());
            $this->assertSame('DFDKLFJSDKFJLDFJLDFJE', $resource->getSecret());
            $this->assertSame('https://chart.googleapis.com/chart?chs=166x166&chld=L|0&cht=qr&chl=otpauth://totp/oauth-test:john.doe%2B3%40gmail.com?secret=KDIELEIODEM4F4RTY&issuer=oauth-test&algorithm=SHA1&digits=6&period=30', $resource->getBarcodeUri());
            $this->assertSame('otpauth://totp/oauth-test:john.doe%2B3%40gmail.com?secret=KDIELEIODEM4F4RTY&issuer=oauth-test&algorithm=SHA1&digits=6&period=30', $resource->getRealBarcodeUri());
            $this->assertSame([
                'FDLFOELDOEILRKELFJLKDFDFD'
            ], $resource->getRecoveryCodes());


        }
        

        

    }
}