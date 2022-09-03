<?php


namespace Tests\Unit\Oauth0\Resources;

use Tests\TestCase;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Provider\ClientSecretId;
use Crazymeeks\Oauth0\Resources\ValidateMFAOTP;

class ValidateMFAOTPTest extends TestCase
{



    /**
     * @var \Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface
     */
    protected $clientSecretId;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpClientSecretId();
    }
    

    public function testShouldValidateMFAOtp()
    {
        $mfa = new ValidateMFAOTP($this->clientSecretId);

        $mfa->mfa_token = "Fe26.2*272dc2*e568ea9b8814975c8c4c2984348c755666f82e40ea57c2e71ce81099fa187d04*c0ix7ZkiR5AN6MkVg-e1OA*3qJ-CJMA6bTOWn4PrmFq0-CEpWPpY__jBAY1CD1uFKxC__tkxs668VErtaOLB21HamhJE2gGQHXfCrdMbhURB-bxt2D_6ICPsXrof9JU_-1YjpFd5tGCciRx7QRk3J0XQaNWoZ3GsmtCml1aWuzxDNfKFdFWF7HJMtwmc_myv2t7wDXs0DKdVGDt34wvU_-Shrh4ZrqcPpZpLeuNZwZiEb_ffjO5Yo4VMW9A7cgaV5Emv293u02l6AVtDGfAemEkz70pMbgLUrR7yqQJ9m8KwHmNbYrbGFVzkvpKtRBG2xK3N6OJYQUkU-iL316zNiM05RsgUe4ttByu8B90K2Vz4Y8-HPvMHG7BXkDKxABJY5vVua4tyPLdreDfn1QafcT4CMVk3-6yXxTnVPsT4fizt3-HSJTldZ4msr8zj8sS6cFBwiJz3leDeX5INstCWi-V-ILmui1m2Wwsx0kb8GtrGAdKZF1lDY3NFoE6i-5z_Rc8NcDQHdFLF-2BlLqKhyzCZj0xIPfqDPYeOziMf2JyMU_WiSYrxcY-lY01aHMMYRJwhSrSUFtKmtZ7Bw7JZHGA5ZkYyNIUNYH8iflxgCGJDrggNu6XcRZDu0oHn-ZcwN7UPeZkwbs_4ext25nKOLqucXraOci0IzqNwoaijyRTsexpKpNaqB7s9imzKOiij3qEFminbSSBVmj8uih3F6pnpVu7pld3wN04zfRQi7-Q07hTnRS7Jj1xDTW2mT21oTQCWzVmBO4NvR161xBe3jAlMC3__s3Adh9dee43KaHQPvOgYfxYp-HM8wlC-QYKcUJH5umeu0RcYW7wT2ktQEwJO2bgdflHCv32vkzyjziuXP9AQ1tpg7tID2Tg52LAX1AH_cwXd0L7PjtEhXR8znJ2cyGcmY8xP-2IHjYG1ulbLd-NHHAdTuo2yTIWSPpgNzE9sN_aprtYRBpLONZJNLvnkqc7ssPlFWyBFZTDhnZeVOoi0LrFJ5_u57NZgeJ_ze99xqU54kca4Ig-LMi_bzvbsXzIHD8EQvDXcGEeBppYGjB56-kNu-QCmNgX7aBtokOUNs87r4Dkptpraeg12cVGd9kUpR6Tu5zIstb3cs4PhT9EYqYUpraIjHxeZBiDzWc-1IjYIebKeHWj2g_ImDtPB6RiB0X8hkvZ_If1IiPJTlEPMiwHqZUJJTiLcadOEcXvPaOnXS065sh0ZT0xxQEzVawq0uR9Ehkxr5YwYZIcg0LC4M-tJdsVgoprW1lCXFwnDXsRNPbjaBOlo-rvExBtMJxulzod-ULlqRlfgZdAFXpSst3lNt-502w1oSPmi6rX8sFSoaPN3LKzmllupaEuzi88nklZzL0ZHBXi5lfKc6SYgFv34oZh47awrWyOKPpAYpotd8Kz9joiU5kAi6w7UZRlisFRd-w8IRjqFbhdgOKTjq1E4KOwtdwMJqjvY5Q*1658440556960*ec6236820f16c26fb6c6b164a7cc4ba20260af1d";
        $mfa->otp = "940518";

        $params = $mfa->get(new Oauth0('https://oauth0-test.auth0.com'));
        $this->assertSame([
            'mfa_token' => $mfa->mfa_token,
            'otp' => $mfa->otp,
            'client_id' => '123445',
            'client_secret' => 'DLK03909',
            'grant_type' => 'http://auth0.com/oauth/grant-type/mfa-otp'
        ], $params);
    }


    protected function setUpClientSecretId()
    {
        $clientSecretId = new ClientSecretId();

        $clientSecretId->setClientId('123445')
                       ->setClientSecret('DLK03909');

        $this->clientSecretId = $clientSecretId;
    }
}