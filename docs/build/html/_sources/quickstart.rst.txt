==========
Quickstart
==========

This page provide a quick introduction on how to use Oauth0 library. 
If you have not already installed PHP Oauth0, go check the :ref:`installation` page

Before start using this library, make sure you already have `oauth0 <https://auth0.auth0.com/>`_ account.

.. _request access token:

====================
Request access token
====================

Before you can make any request to Oauth0 server, you would need an access token.
Please take note that this token is different from the access token you would use
after logging/signing in user.

.. code-block:: php

    <?php

    namespace YourNameSpace;

    use Crazymeeks\Oauth0\Oauth0;
    use Crazymeeks\Oauth0\Resources\AccessToken;
    use Crazymeeks\Oauth0\Provider\ClientSecretId;

    class ExampleClass
    {
        public function getAccessToken()
        {
            // set client id, you may retrieve your client_id and client_secret
            // from your oauth0 account
            $client = new ClientSecretId();
            $client->setClientId('1234')
                   ->setClientSecret('myclient-secret');

            // prepare the resource
            $resource = new AccessToken($client);
            $resource->audience = 'api/v2/';
            // grant_type is optional. Default is 'client_credentials'
            $resource->grant_type = 'client_credentials';


            // Send request to Oauth0 server
            $oauth0 = new Oauth0('https://oauth0-test.auth0.com');

            /** @var \Crazymeeks\Oauth0\Resources\AccessToken $resource */
            $resource = $oauth0->setResource($resource)
                           ->execute();

            // Get the access token
            $accessToken = $resource->getAccessToken();
            
            // You may also retrieve the scope by calling getScope() method.
            $scope = $resource->getScope();

        }
    }


===========
Create user
===========

To register a user, an access token. Please check **Request access token** page.

.. code-block:: php

    <?php

    namespace YourNameSpace;

    use Crazymeeks\Oauth0\Resources\CreateUser;

    class ExampleClass
    {
        

        public function createUser()
        {

            //prepare the resource
            $resource = new CreateUser();
            $resource->email = 'john.doe@example.com';
            $resource->email_verified = true;
            $resource->app_metadata = new \stdClass();
            $resource->given_name = 'John';
            $resource->family_name = 'Doe';
            $resource->name = 'John Doe';
            $resource->nickname = 'J';
            $resource->picture = 'https://secure.gravatar.com/avatar/15626c5e0c749cb912f9d1ad48dba440?s=480&r=pg&d=https%3A%2F%2Fssl.gstatic.com%2Fs2%2Fprofiles%2Fimages%2Fsilhouette80.png';
            $resource->connection = 'Database-Connection';
            $resource->password = 'password1234';
            $resource->verify_email = false;

            
            $resource->setHeaders(array(
                "Authorization" => sprintf("Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IlF6WkZSa0UwTkRCR05qRkRNRVJEUXpkRE5VUkdNakEzUmtSQk56UXhSRE5CTkRoQlFqQXhNZyJ9.eyJpc3MiOiJodHRwczovL3NlY3VuYS10ZXN0LmF1dGgwLmNvbS8iLCJzdWIiOiJ4MXZtc2ZoNDVqVDc1QzFVR2VoYVlIdkYxdDhMaEt1RkBjbGllbnRzIiwiYXVkIjoiaHR0cHM6Ly9zZWN1bmEtdGVzdC5hdXRoMC5jb20vYXBpL3YyLyIsImlhdCI6MTY2MTM0NTU5OSwiZXhwIjoxNjYxNDMxOTk5LCJhenAiOiJ4MXZtc2ZoNDVqVDc1QzFVR2VoYVlIdkYxdDhMaEt1RiIsInNjb3BlIjoicmVhZDpjbGllbnRfZ3JhbnRzIGNyZWF0ZTpjbGllbnRfZ3JhbnRzIGRlbGV0ZTpjbGllbnRfZ3JhbnRzIHVwZGF0ZTpjbGllbnRfZ3JhbnRzIHJlYWQ6dXNlcnMgdXBkYXRlOnVzZXJzIGRlbGV0ZTp1c2VycyBjcmVhdGU6dXNlcnMgcmVhZDp1c2Vyc19hcHBfbWV0YWRhdGEgdXBkYXRlOnVzZXJzX2FwcF9tZXRhZGF0YSBkZWxldGU6dXNlcnNfYXBwX21ldGFkYXRhIGNyZWF0ZTp1c2Vyc19hcHBfbWV0YWRhdGEgcmVhZDp1c2VyX2N1c3RvbV9ibG9ja3MgY3JlYXRlOnVzZXJfY3VzdG9tX2Jsb2NrcyBkZWxldGU6dXNlcl9jdXN0b21fYmxvY2tzIGNyZWF0ZTp1c2VyX3RpY2tldHMgcmVhZDpjbGllbnRzIHVwZGF0ZTpjbGllbnRzIGRlbGV0ZTpjbGllbnRzIGNyZWF0ZTpjbGllbnRzIHJlYWQ6Y2xpZW50X2tleXMgdXBkYXRlOmNsaWVudF9rZXlzIGRlbGV0ZTpjbGllbnRfa2V5cyBjcmVhdGU6Y2xpZW50X2tleXMgcmVhZDpjb25uZWN0aW9ucyB1cGRhdGU6Y29ubmVjdGlvbnMgZGVsZXRlOmNvbm5lY3Rpb25zIGNyZWF0ZTpjb25uZWN0aW9ucyByZWFkOnJlc291cmNlX3NlcnZlcnMgdXBkYXRlOnJlc291cmNlX3NlcnZlcnMgZGVsZXRlOnJlc291cmNlX3NlcnZlcnMgY3JlYXRlOnJlc291cmNlX3NlcnZlcnMgcmVhZDpkZXZpY2VfY3JlZGVudGlhbHMgdXBkYXRlOmRldmljZV9jcmVkZW50aWFscyBkZWxldGU6ZGV2aWNlX2NyZWRlbnRpYWxzIGNyZWF0ZTpkZXZpY2VfY3JlZGVudGlhbHMgcmVhZDpydWxlcyB1cGRhdGU6cnVsZXMgZGVsZXRlOnJ1bGVzIGNyZWF0ZTpydWxlcyByZWFkOnJ1bGVzX2NvbmZpZ3MgdXBkYXRlOnJ1bGVzX2NvbmZpZ3MgZGVsZXRlOnJ1bGVzX2NvbmZpZ3MgcmVhZDpob29rcyB1cGRhdGU6aG9va3MgZGVsZXRlOmhvb2tzIGNyZWF0ZTpob29rcyByZWFkOmFjdGlvbnMgdXBkYXRlOmFjdGlvbnMgZGVsZXRlOmFjdGlvbnMgY3JlYXRlOmFjdGlvbnMgcmVhZDplbWFpbF9wcm92aWRlciB1cGRhdGU6ZW1haWxfcHJvdmlkZXIgZGVsZXRlOmVtYWlsX3Byb3ZpZGVyIGNyZWF0ZTplbWFpbF9wcm92aWRlciBibGFja2xpc3Q6dG9rZW5zIHJlYWQ6c3RhdHMgcmVhZDppbnNpZ2h0cyByZWFkOnRlbmFudF9zZXR0aW5ncyB1cGRhdGU6dGVuYW50X3NldHRpbmdzIHJlYWQ6bG9ncyByZWFkOmxvZ3NfdXNlcnMgcmVhZDpzaGllbGRzIGNyZWF0ZTpzaGllbGRzIHVwZGF0ZTpzaGllbGRzIGRlbGV0ZTpzaGllbGRzIHJlYWQ6YW5vbWFseV9ibG9ja3MgZGVsZXRlOmFub21hbHlfYmxvY2tzIHVwZGF0ZTp0cmlnZ2VycyByZWFkOnRyaWdnZXJzIHJlYWQ6Z3JhbnRzIGRlbGV0ZTpncmFudHMgcmVhZDpndWFyZGlhbl9mYWN0b3JzIHVwZGF0ZTpndWFyZGlhbl9mYWN0b3JzIHJlYWQ6Z3VhcmRpYW5fZW5yb2xsbWVudHMgZGVsZXRlOmd1YXJkaWFuX2Vucm9sbG1lbnRzIGNyZWF0ZTpndWFyZGlhbl9lbnJvbGxtZW50X3RpY2tldHMgcmVhZDp1c2VyX2lkcF90b2tlbnMgY3JlYXRlOnBhc3N3b3Jkc19jaGVja2luZ19qb2IgZGVsZXRlOnBhc3N3b3Jkc19jaGVja2luZ19qb2IgcmVhZDpjdXN0b21fZG9tYWlucyBkZWxldGU6Y3VzdG9tX2RvbWFpbnMgY3JlYXRlOmN1c3RvbV9kb21haW5zIHVwZGF0ZTpjdXN0b21fZG9tYWlucyByZWFkOmVtYWlsX3RlbXBsYXRlcyBjcmVhdGU6ZW1haWxfdGVtcGxhdGVzIHVwZGF0ZTplbWFpbF90ZW1wbGF0ZXMgcmVhZDptZmFfcG9saWNpZXMgdXBkYXRlOm1mYV9wb2xpY2llcyByZWFkOnJvbGVzIGNyZWF0ZTpyb2xlcyBkZWxldGU6cm9sZXMgdXBkYXRlOnJvbGVzIHJlYWQ6cHJvbXB0cyB1cGRhdGU6cHJvbXB0cyByZWFkOmJyYW5kaW5nIHVwZGF0ZTpicmFuZGluZyBkZWxldGU6YnJhbmRpbmcgcmVhZDpsb2dfc3RyZWFtcyBjcmVhdGU6bG9nX3N0cmVhbXMgZGVsZXRlOmxvZ19zdHJlYW1zIHVwZGF0ZTpsb2dfc3RyZWFtcyBjcmVhdGU6c2lnbmluZ19rZXlzIHJlYWQ6c2lnbmluZ19rZXlzIHVwZGF0ZTpzaWduaW5nX2tleXMgcmVhZDpsaW1pdHMgdXBkYXRlOmxpbWl0cyBjcmVhdGU6cm9sZV9tZW1iZXJzIHJlYWQ6cm9sZV9tZW1iZXJzIGRlbGV0ZTpyb2xlX21lbWJlcnMgcmVhZDplbnRpdGxlbWVudHMgcmVhZDphdHRhY2tfcHJvdGVjdGlvbiB1cGRhdGU6YXR0YWNrX3Byb3RlY3Rpb24gcmVhZDpvcmdhbml6YXRpb25zX3N1bW1hcnkgcmVhZDpvcmdhbml6YXRpb25zIHVwZGF0ZTpvcmdhbml6YXRpb25zIGNyZWF0ZTpvcmdhbml6YXRpb25zIGRlbGV0ZTpvcmdhbml6YXRpb25zIGNyZWF0ZTpvcmdhbml6YXRpb25fbWVtYmVycyByZWFkOm9yZ2FuaXphdGlvbl9tZW1iZXJzIGRlbGV0ZTpvcmdhbml6YXRpb25fbWVtYmVycyBjcmVhdGU6b3JnYW5pemF0aW9uX2Nvbm5lY3Rpb25zIHJlYWQ6b3JnYW5pemF0aW9uX2Nvbm5lY3Rpb25zIHVwZGF0ZTpvcmdhbml6YXRpb25fY29ubmVjdGlvbnMgZGVsZXRlOm9yZ2FuaXphdGlvbl9jb25uZWN0aW9ucyBjcmVhdGU6b3JnYW5pemF0aW9uX21lbWJlcl9yb2xlcyByZWFkOm9yZ2FuaXphdGlvbl9tZW1iZXJfcm9sZXMgZGVsZXRlOm9yZ2FuaXphdGlvbl9tZW1iZXJfcm9sZXMgY3JlYXRlOm9yZ2FuaXphdGlvbl9pbnZpdGF0aW9ucyByZWFkOm9yZ2FuaXphdGlvbl9pbnZpdGF0aW9ucyBkZWxldGU6b3JnYW5pemF0aW9uX2ludml0YXRpb25zIiwiZ3R5IjoiY2xpZW50LWNyZWRlbnRpYWxzIn0.DOZXmqEQBk4r9oMw6goV8h_QyDDAwhzdW6WaT4Th86asi3glIw3XCgZ6WWPxPIjtld_XxuzONMQyrMd1V1uPZ44zsLFmNsDKgmGRAB74XfBMLjNvgR1W9V8IF94_aFr61mGhQTj6cxvwThsTZTmy5uCDXjD_a9vMNi8JObueBPt1C09VYemno3Hv2r8cb6cXBAzfA2n_eEB62aF3kezHP8D-ME1iL6uFJvyAlM-JN-om1kq6YzZKBxqI8baEKlhP61PJgcTveyfCNBiUrc_eag-cg0trQUbnDbI6QqwJU01D6nncFpGmKaqeUG7Q2TsyXVnFveWZ6EXWXPgaIwAcmw")
            ));

            // Send request to Oauth0 server
            $oauth0 = new Oauth0('https://oauth0-test.auth0.com');

            /** @var \Crazymeeks\Oauth0\Resources\CreateUser $resource */
            $resource = $oauth0->setResource($resource)
                           ->execute();

            // response would exactly look like this
            /*
            {
                "blocked": false,
                "created_at": "2022-07-21T10:02:24.385Z",
                "email": "john.doe@gmail.com",
                "email_verified": true,
                "family_name": "Doe",
                "given_name": "John",
                "identities": [
                    {
                        "connection": "Username-Password-Authentication",
                        "user_id": "62d9243068810179098638724",
                        "provider": "auth0",
                        "isSocial": false
                    }
                ],
                "name": "John Doe",
                "nickname": "j",
                "picture": "https://secure.gravatar.com/avatar/15626c5e0c749cb912f9d1ad48dba440?s=480&r=pg&d=https%3A%2F%2Fssl.gstatic.com%2Fs2%2Fprofiles%2Fimages%2Fsilhouette80.png",
                "updated_at": "2022-07-21T10:02:24.385Z",
                "user_id": "auth0|62d9243068810179098638724",
                "user_metadata": {}
            }
            */
            // You may retrieve each by calling and getResponse() method and chain with attribute
            // you want to retrieve
            $email = $resource->getResponse()->email;
            $familyName = $resource->getResponse()->family_name;
            
        }
    }


==============================
Login User without MFA enabled
==============================

.. code-block:: php

    <?php

    namespace YourNameSpace;

    use Crazymeeks\Oauth0\Oauth0;
    use Crazymeeks\Oauth0\Resources\LoginUser;
    use Crazymeeks\Oauth0\Provider\ClientSecretId;

    class ExampleClass
    {
        public function loginUser()
        {
            // set client id, you may retrieve your client_id and client_secret
            // from your oauth0 account
            $client = new ClientSecretId();
            $client->setClientId('1234')
                   ->setClientSecret('myclient-secret');

            // prepare the resource
            $resource = new LoginUser($client);
            $resource->username = 'john.doe@example.com';
            $resource->password = 'password1234';
            $resource->connection = 'Connetion-Database';


            // Send request to Oauth0 server
            $oauth0 = new Oauth0('https://oauth0-test.auth0.com');

            /** @var \Crazymeeks\Oauth0\Resources\LoginUser $resource */
            $resource = $oauth0->setResource($resource)
                           ->execute();

            // Get the access token
            $accessToken = $resource->getAccessToken();

        }
    }
    

===========================
Login User with MFA enabled
===========================

When MFA is enabled on your oauth0 account, **Crazymeeks\\Oauth0\\Oauth0::execute()** method
will throw **Crazymeeks\\Oauth0\\Exception\\ResourceException**. In this case, you would need
to enrol user to mfa. You can do this by using **Crazymeeks\\Oauth0\\Resources\\EnrolUserToMFA**.


.. code-block:: php

    <?php

    namespace YourNameSpace;

    use Crazymeeks\Oauth0\Oauth0;
    use Crazymeeks\Oauth0\Resources\LoginUser;
    use Crazymeeks\Oauth0\Provider\ClientSecretId;

    class ExampleClass
    {
        public function loginUser()
        {
            // set client id, you may retrieve your client_id and client_secret
            // from your oauth0 account
            $client = new ClientSecretId();
            $client->setClientId('1234')
                   ->setClientSecret('myclient-secret');

            // prepare the resource
            $resource = new LoginUser($client);
            $resource->username = 'john.doe@example.com';
            $resource->password = 'password1234';
            $resource->connection = 'Connetion-Database';


            // Send request to Oauth0 server
            $oauth0 = new Oauth0('https://oauth0-test.auth0.com');

            /** @var \Crazymeeks\Oauth0\Resources\LoginUser $resource */
            try {
                $resource = $oauth0->setResource($resource)
                            ->execute();
            } catch (ResourceException $e) {
                // oauth0 mfa is required
                // extra steps needs to be done by the developer
                // when mfa is required. Dev can actually copy
                // and paste this code

                $response = json_decode($e->getMessage());
                
                $oauth0 = new Oauth0('https://oauth0-test.auth0.com');

                $resource = new EnrolUserToMFA($client);

                $resource->scope = 'enrol'; // optional
                $resource->audience = 'mfa';
                $resource->setHeaders(array(
                    'Authorization' => sprintf("Bearer %s", $response->mfa_token),
                ));

                $resource = $oauth0->setResource($resource)
                            ->execute();


                // barcode uri, you should display this in an <img> tag so user
                // can scan it using google authenticator app
                $barcodeUri = $resource->getBarcodeUri();
                // other methods
                /** @var string */
                $authType = $resource->getAuthenticatorType();
                /** @var string */
                $secret = $resource->getSecret();
                /**
                 * @var array('FDLFOELDOEILRKELFJLKDFDFD', 'LDIOE093043DFDFKLIOERU')
                 */
                $recoveryCodes = $resource->getRecoveryCodes();

            }

        }
    }

==================================
Validate OTP(for MFA enabled only)
==================================

To validate OTP code after user scanned the qr code using authenticator app like google authenticator,
you can validate it using the code below.

.. code-block:: php

    <?php

    namespace YourNameSpace;

    use Crazymeeks\Oauth0\Oauth0;
    use Crazymeeks\Oauth0\Resources\ValidateMFAOTP;
    use Crazymeeks\Oauth0\Provider\ClientSecretId;

    class ExampleClass
    {
        public function loginUser()
        {
            // set client id, you may retrieve your client_id and client_secret
            // from your oauth0 account
            $client = new ClientSecretId();
            $client->setClientId('1234')
                   ->setClientSecret('myclient-secret');

            // prepare the resource
            $resource = new ValidateMFAOTP($client);
            // mfa token retrieve from previous step.
            $resource->mfa_token = "Fe26.2*272dc2*e568ea9b8814975c8c4c2984348c755666f82e40ea57c2e71ce81099fa187d04*c0ix7ZkiR5AN6MkVg-e1OA*3qJ-CJMA6bTOWn4PrmFq0-CEpWPpY__jBAY1CD1uFKxC__tkxs668VErtaOLB21HamhJE2gGQHXfCrdMbhURB-bxt2D_6ICPsXrof9JU_-1YjpFd5tGCciRx7QRk3J0XQaNWoZ3GsmtCml1aWuzxDNfKFdFWF7HJMtwmc_myv2t7wDXs0DKdVGDt34wvU_-Shrh4ZrqcPpZpLeuNZwZiEb_ffjO5Yo4VMW9A7cgaV5Emv293u02l6AVtDGfAemEkz70pMbgLUrR7yqQJ9m8KwHmNbYrbGFVzkvpKtRBG2xK3N6OJYQUkU-iL316zNiM05RsgUe4ttByu8B90K2Vz4Y8-HPvMHG7BXkDKxABJY5vVua4tyPLdreDfn1QafcT4CMVk3-6yXxTnVPsT4fizt3-HSJTldZ4msr8zj8sS6cFBwiJz3leDeX5INstCWi-V-ILmui1m2Wwsx0kb8GtrGAdKZF1lDY3NFoE6i-5z_Rc8NcDQHdFLF-2BlLqKhyzCZj0xIPfqDPYeOziMf2JyMU_WiSYrxcY-lY01aHMMYRJwhSrSUFtKmtZ7Bw7JZHGA5ZkYyNIUNYH8iflxgCGJDrggNu6XcRZDu0oHn-ZcwN7UPeZkwbs_4ext25nKOLqucXraOci0IzqNwoaijyRTsexpKpNaqB7s9imzKOiij3qEFminbSSBVmj8uih3F6pnpVu7pld3wN04zfRQi7-Q07hTnRS7Jj1xDTW2mT21oTQCWzVmBO4NvR161xBe3jAlMC3__s3Adh9dee43KaHQPvOgYfxYp-HM8wlC-QYKcUJH5umeu0RcYW7wT2ktQEwJO2bgdflHCv32vkzyjziuXP9AQ1tpg7tID2Tg52LAX1AH_cwXd0L7PjtEhXR8znJ2cyGcmY8xP-2IHjYG1ulbLd-NHHAdTuo2yTIWSPpgNzE9sN_aprtYRBpLONZJNLvnkqc7ssPlFWyBFZTDhnZeVOoi0LrFJ5_u57NZgeJ_ze99xqU54kca4Ig-LMi_bzvbsXzIHD8EQvDXcGEeBppYGjB56-kNu-QCmNgX7aBtokOUNs87r4Dkptpraeg12cVGd9kUpR6Tu5zIstb3cs4PhT9EYqYUpraIjHxeZBiDzWc-1IjYIebKeHWj2g_ImDtPB6RiB0X8hkvZ_If1IiPJTlEPMiwHqZUJJTiLcadOEcXvPaOnXS065sh0ZT0xxQEzVawq0uR9Ehkxr5YwYZIcg0LC4M-tJdsVgoprW1lCXFwnDXsRNPbjaBOlo-rvExBtMJxulzod-ULlqRlfgZdAFXpSst3lNt-502w1oSPmi6rX8sFSoaPN3LKzmllupaEuzi88nklZzL0ZHBXi5lfKc6SYgFv34oZh47awrWyOKPpAYpotd8Kz9joiU5kAi6w7UZRlisFRd-w8IRjqFbhdgOKTjq1E4KOwtdwMJqjvY5Q*1658440556960*ec6236820f16c26fb6c6b164a7cc4ba20260af1d";
            // OTP entered by user on your app/website
            $resource->otp = "940518";


            // Send request to Oauth0 server
            $oauth0 = new Oauth0('https://oauth0-test.auth0.com');

            /** @var \Crazymeeks\Oauth0\Resources\LoginUser $resource */
            $resource = $oauth0->setResource($resource)
                        ->execute();
            

            $accessToken = $resource->getAccessToken();
            $scope = $resource->getScope();
            $expiresIn = $resource->getExpiresIn();
            $tokenType = $resource->getTokenType();
        }
    }

=================
Reset User's MFA
=================

To reset MFA of a user, an access token. Please check **Request access token** page.
Note that this don't have any content in the response. The status returned by Oauth0
in this action is 204.

.. code-block:: php

    <?php

    namespace YourNameSpace;

    use Crazymeeks\Oauth0\Oauth0;
    use Crazymeeks\Oauth0\Resources\ResetUserMFA;

    class ExampleClass
    {
        public function loginUser()
        {
            
            // prepare the resource
            $resource = new ResetUserMFA();
            $resource->user_id = 'auth0|62d9243068810176e8346c';

            // set access token header
            $resource->setHeaders(
                array(
                    'Authorization' => 'Bearer 4039430493049304'
                )
            );

            // Send request to Oauth0 server
            $oauth0 = new Oauth0('https://oauth0-test.auth0.com');

            /** @var \Crazymeeks\Oauth0\Resources\LoginUser $resource */
            $resource = $oauth0->setResource($resource)
                        ->execute();
            
        }
    }