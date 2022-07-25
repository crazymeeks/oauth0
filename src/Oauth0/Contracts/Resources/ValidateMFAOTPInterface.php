<?php


namespace Crazymeeks\Oauth0\Contracts\Resources;

interface ValidateMFAOTPInterface
{



    /**
     * Get access token
     *
     * @return string
     */
    public function getAccessToken();

    /**
     * Get id token
     *
     * @return string
     */
    public function getIdToken();

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope();

    /**
     * Get expires in
     *
     * @return int
     */
    public function getExpiresIn();

    /**
     * Get token type
     *
     * @return string
     */
    public function getTokenType();
}