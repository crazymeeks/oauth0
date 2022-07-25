<?php


namespace Crazymeeks\Oauth0\Contracts\Resources;


interface EnrolUserToMFAInterface
{


    /**
     * Get authenticator type
     *
     * @return string
     */
    public function getAuthenticatorType();

    /**
     * Get secret
     *
     * @return string
     */
    public function getSecret();

    /**
     * Get barcode uri with link so can be displayed in <img> tag
     *
     * @return string
     */
    public function getBarcodeUri();

    /**
     * Get real barcode uri
     *
     * @return string
     */
    public function getRealBarcodeUri();

    /**
     * Get recovery codes
     *
     * @return array
     */
    public function getRecoveryCodes();
}