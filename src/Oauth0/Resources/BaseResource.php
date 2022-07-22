<?php

namespace Crazymeeks\Oauth0\Resources;

use Crazymeeks\Oauth0\Contracts\Resources\ResourceInterface;

abstract class BaseResource implements ResourceInterface
{

    /**
     * Resource header
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Oauth0 response
     *
     * @var object
     */
    protected $oauthResponse;


    /**
     * Store our overloaded properties
     *
     * @var array
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
     * @inheritDoc
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }


    /**
     * @inheritDoc
     */
    public function __get($name)
    {
        return array_key_exists($name, $this->properties) ? $this->properties[$name] : null;
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
     * Set response data returned by oauth0
     * 
     * @param string json
     *
     * @return $this
     */
    public function setResponse(string $json)
    {
        $this->oauthResponse = json_decode($json);
        return $this;
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
}