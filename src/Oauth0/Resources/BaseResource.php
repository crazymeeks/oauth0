<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Oauth0;
use Crazymeeks\Oauth0\Contracts\Resources\ResourceInterface;
use Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface;

/**
 * @property array $properties
 * @property string $client_id
 * @property string $client_secret
 */

abstract class BaseResource implements ResourceInterface
{

    /**
     * Auth0 resource' endpoint
     *
     * @var string
     */
    protected $apiEndpoint;

    /**
     * Auth0 client secret id
     *
     * @var \Crazymeeks\Oauth0\Contracts\Provider\ClientSecretIdInterface
     */
    protected $clientSecretId;

    /**
     * Resource' http method
     *
     * @var string
     */
    protected $httpMethod;

    /**
     * Resource header
     *
     * @var array<array<string, string>>
     */
    protected $headers = [];

    /**
     * Oauth0 response
     *
     * @var object
     */
    protected $oauthResponse = null;


    /**
     * Store our overloaded properties
     *
     * @var array<string, mixed>
     */
    protected $properties = [];


    /** 
     * @inheritDoc
     */
    public function setHttpMethod(string $httpMethod)
    {
        $this->httpMethod = $httpMethod;
        return $this;
    }

    /** 
     * @inheritDoc
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }


    /**
     * Dynamically set property
     * 
     * @param string $name
     * @param mixed $value
     * 
     * @return void
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }


    /**
     * Dynamically get property
     * 
     * @param string $name
     * 
     * @return mixed
     */
    public function __get($name)
    {
        return array_key_exists($name, $this->properties) ? $this->properties[$name] : null;
    }


    /**
     * Check if dynamic property has been set
     * 
     * @param string $name
     * 
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * Unset dynamic property
     * 
     * @param string $name
     * 
     * @return void
     */
    public function __unset($name)
    {
        unset($this->properties[$name]);
    }

    /**
    * @inheritDoc
    */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
    * @inheritDoc
    */
    public function getHeaders()
    {
        return $this->headers;
    }

   /**
    * @inheritDoc
    */
    public function hasHeaders()
    {
        return count($this->getHeaders()) > 0;
    }

    /**
     * @inheritDoc
     */
    public function setResponse(string $json = null)
    {

        $json_validator = function($data) {
            if (!empty($data) || !$data === null) {
                return is_string($data) && 
                  is_array(json_decode($data, true)) ? true : false;
            }
            return false;
        };

        $this->oauthResponse = $json_validator($json) ? json_decode($json) : $json;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getResponse()
    {
        return $this->oauthResponse;
    }


    /**
    * @inheritDoc
    */
    public function setApiEndPoint(string $apiEndpoint)
    {
        $this->apiEndpoint = $apiEndpoint;
        return $this;
    }

    /**
    * @inheritDoc
    */
    public function getApiEndPoint()
    {
        return $this->apiEndpoint;
    }

    /**
     * Create default props
     *
     * @param \Crazymeeks\Oauth0\Oauth0 $oauth0
     * 
     * @return void
     */
    protected function createDefaultProps(Oauth0 $oauth0)
    {
        $this->client_id = $this->clientSecretId->getClientId();
        $this->client_secret = $this->clientSecretId->getClientSecret();
    }
}