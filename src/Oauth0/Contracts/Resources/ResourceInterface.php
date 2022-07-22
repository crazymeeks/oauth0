<?php

namespace Crazymeeks\Oauth0\Contracts\Resources;

interface ResourceInterface
{


    /**
     * Set http method for this resource
     *
     * @param string $httpMethod
     * 
     * @return $this
     */
    public function setHttpMethod(string $httpMethod);

    /**
     * Get http method for this resource
     *
     * @return string
     */
    public function getHttpMethod();


    /**
     * Set api endpoint for this resource
     *
     * @param string $apiEndpoint
     * 
     * @return $this
     */
    public function setApiEndPoint(string $apiEndpoint);

    /**
     * Get api endpoint for this resource
     *
     * @return string
     */
    public function getApiEndPoint();


    /**
     * Set headers for this resource
     * 
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers);

    /**
     * Get headers for this resource
     *
     * @return array
     */
    public function getHeaders();


    /**
     * Determine if this headers has header
     *
     * @return boolean
     */
    public function hasHeaders();


    /**
     * Return properties of the resource
     *
     * @return array
     */
    public function get();
}