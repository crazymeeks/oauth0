<?php


namespace Tests\Unit\Oauth0;

use Mockery;
use Tests\TestCase;
use Ixudra\Curl\CurlService;
use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Resources\LoginUser;
use Crazymeeks\Oauth0\Resources\CreateUser;
use Crazymeeks\Oauth0\Resources\AccessToken;
use Crazymeeks\Oauth0\Resources\ResetUserMFA;
use Crazymeeks\Oauth0\Provider\ClientSecretId;
use Crazymeeks\Oauth0\Resources\EnrolUserToMFA;
use Crazymeeks\Oauth0\Resources\ValidateMFAOTP;
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
        $resource->audience = 'api/v2/';
        $resource->grant_type = 'client_credentials';
        $curl = $this->mockOauthResponse(file_get_contents(__DIR__ . '/MockedResponse/access-token.json'));
        $oauth0 = new Oauth0('https://oauth0-test.auth0.com', $curl);
        $resource = $oauth0->setResource($resource)
                           ->execute();

        $this->assertEquals($resource->getAccessToken(), 'hIGNyZWF0ZTp1c2Vyc19hcHBfbWV0YWRhdGEgcmVhZDp1c2VyX2N1c3RvbV9ibG9ja3MgY3JlYXRlOnVzZXJfY3VzdG9tX2Jsb2NrcyBkZWxldGU6dXNlcl9jdXN0b21fYmxvY2tzIGNyZWF0ZTp1c2VyX3RpY2tldHMgcmVhZDpjbGllbnRzIHVwZGF0ZTpjbGllbnRzIGRlbGV0ZTpjbGllbnRzIGNyZWF0ZTpjbGllbnRzIHJlYWQ6Y2xpZW50X2tleXMgdXBkYXRlOmNsaWVudF9rZXlzIGRlbGV0ZTpjbGllbnRfa2V5cyBjcmVhdGU6Y2xpZW50X2tleXMgcmVhZDpjb25uZWN0aW9ucyB1cGRhdGU6Y29ubmVjdGlvbnMgZGVsZXRlOmNvbm5lY3Rpb25zIGNyZWF0ZTpjb25uZWN0aW9ucyByZWFkOnJlc291cmNlX3Nl');
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

    public function testShouldValidateOTP()
    {
        $resource = new ValidateMFAOTP($this->clientSecretId);
        $resource->mfa_token = "Fe26.2*272dc2*e568ea9b8814975c8c4c2984348c755666f82e40ea57c2e71ce81099fa187d04*c0ix7ZkiR5AN6MkVg-e1OA*3qJ-CJMA6bTOWn4PrmFq0-CEpWPpY__jBAY1CD1uFKxC__tkxs668VErtaOLB21HamhJE2gGQHXfCrdMbhURB-bxt2D_6ICPsXrof9JU_-1YjpFd5tGCciRx7QRk3J0XQaNWoZ3GsmtCml1aWuzxDNfKFdFWF7HJMtwmc_myv2t7wDXs0DKdVGDt34wvU_-Shrh4ZrqcPpZpLeuNZwZiEb_ffjO5Yo4VMW9A7cgaV5Emv293u02l6AVtDGfAemEkz70pMbgLUrR7yqQJ9m8KwHmNbYrbGFVzkvpKtRBG2xK3N6OJYQUkU-iL316zNiM05RsgUe4ttByu8B90K2Vz4Y8-HPvMHG7BXkDKxABJY5vVua4tyPLdreDfn1QafcT4CMVk3-6yXxTnVPsT4fizt3-HSJTldZ4msr8zj8sS6cFBwiJz3leDeX5INstCWi-V-ILmui1m2Wwsx0kb8GtrGAdKZF1lDY3NFoE6i-5z_Rc8NcDQHdFLF-2BlLqKhyzCZj0xIPfqDPYeOziMf2JyMU_WiSYrxcY-lY01aHMMYRJwhSrSUFtKmtZ7Bw7JZHGA5ZkYyNIUNYH8iflxgCGJDrggNu6XcRZDu0oHn-ZcwN7UPeZkwbs_4ext25nKOLqucXraOci0IzqNwoaijyRTsexpKpNaqB7s9imzKOiij3qEFminbSSBVmj8uih3F6pnpVu7pld3wN04zfRQi7-Q07hTnRS7Jj1xDTW2mT21oTQCWzVmBO4NvR161xBe3jAlMC3__s3Adh9dee43KaHQPvOgYfxYp-HM8wlC-QYKcUJH5umeu0RcYW7wT2ktQEwJO2bgdflHCv32vkzyjziuXP9AQ1tpg7tID2Tg52LAX1AH_cwXd0L7PjtEhXR8znJ2cyGcmY8xP-2IHjYG1ulbLd-NHHAdTuo2yTIWSPpgNzE9sN_aprtYRBpLONZJNLvnkqc7ssPlFWyBFZTDhnZeVOoi0LrFJ5_u57NZgeJ_ze99xqU54kca4Ig-LMi_bzvbsXzIHD8EQvDXcGEeBppYGjB56-kNu-QCmNgX7aBtokOUNs87r4Dkptpraeg12cVGd9kUpR6Tu5zIstb3cs4PhT9EYqYUpraIjHxeZBiDzWc-1IjYIebKeHWj2g_ImDtPB6RiB0X8hkvZ_If1IiPJTlEPMiwHqZUJJTiLcadOEcXvPaOnXS065sh0ZT0xxQEzVawq0uR9Ehkxr5YwYZIcg0LC4M-tJdsVgoprW1lCXFwnDXsRNPbjaBOlo-rvExBtMJxulzod-ULlqRlfgZdAFXpSst3lNt-502w1oSPmi6rX8sFSoaPN3LKzmllupaEuzi88nklZzL0ZHBXi5lfKc6SYgFv34oZh47awrWyOKPpAYpotd8Kz9joiU5kAi6w7UZRlisFRd-w8IRjqFbhdgOKTjq1E4KOwtdwMJqjvY5Q*1658440556960*ec6236820f16c26fb6c6b164a7cc4ba20260af1d";
        $resource->otp = "940518";

        

        $curl = $this->mockOauthResponse(file_get_contents(__DIR__ . '/MockedResponse/validate-otp.json'));
        $oauth0 = new Oauth0('https://oauth0-test.auth0.com', $curl);
        $resource = $oauth0->setResource($resource)
                           ->execute();


        $this->assertSame($resource->getAccessToken(), 'eyJhbGciOiJkaXIiLCJlbmMiOiJBMjU2R0NNIiwiaXNzIjoiaHR0cHM6Ly9zZWN1bmEtdGVzdC5hdXRoMC5jb20vIn0..rzhjKqmMreaO611n.ge7VAFROjVY97ETdFp9El-4ObH8ky_PLVZ16B9nh_rThC2clO0_BlywGpn_gFbCY-fwQIr7aRS0LC1Vv-jj62OgRDOGIaO6RFKanJEYzNpAm_UX6rrzMUr9-K7_MLyrnUvdp5DFj6g-D0QphwPgTkxqsTMClT-WC2d3XNFN8HNY77M7lHesDMRYXy004ocpgM09zDUc8mXLIhn84IAk0Zc8p2nOeRxx7FFi4i5tCMX4IxUOmIZRGxfgvLwCt4ILMu_xqfamlZERJ0lYNm6w9NHv9JhjAWHwD4Odf3DvdLl6kNOLUxEDL5v118KGAKvW9d5cI5lArSo2J8nGkhFLQ18L6DHWzyzi4Sw.0Y9I5mqdYDrutq-FmbBxMg');
        $this->assertSame('openid profile email address phone', $resource->getScope());
        $this->assertSame(86400, $resource->getExpiresIn());
        $this->assertSame('Bearer', $resource->getTokenType());
    }

    public function testShouldResetUserMFA()
    {
        $resource = new ResetUserMFA();

        $resource->user_id = 'auth0|62d9243068810176e8346c';

        $resource->setHeaders(
            array(
                'Authorization' => 'Bearer 4039430493049304'
            )
        );
        $this->curl->shouldReceive('to')
                   ->with(Mockery::any())
                   ->andReturnSelf();

        $this->curl->shouldReceive('withData')
                   ->with([])
                   ->andReturnSelf();
        $this->curl->shouldReceive('delete')
                   ->andReturn(json_decode(
                    json_encode(
                        ['status' => 204]
                    )
                   ));
        $this->curl->shouldReceive('withHeaders')
                   ->with(Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('returnResponseObject')
                   ->andReturnSelf();
        $oauth0 = new Oauth0('https://oauth0-test.auth0.com', $this->curl);
        $resource = $oauth0->setResource($resource)
                           ->execute();
    
        $this->assertTrue(true);
    }
}