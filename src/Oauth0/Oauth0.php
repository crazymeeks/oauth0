<?php

namespace Crazymeeks\Oauth0;

use Ixudra\Curl\CurlService;
use Crazymeeks\Oauth0\Exception\ResourceException;
use Crazymeeks\Oauth0\Contracts\Resources\ResourceInterface;

class Oauth0
{
    

    /**
     * Oauth0 tenant's host domain
     *
     * @var string
     */
    protected $host;

    /**
     * @var \Ixudra\Curl\CurlService
     */
    protected $curl;

    /**
     * @var \Crazymeeks\Oauth0\Contracts\Resources\ResourceInterface
     */
    protected $oauthResource;

    /**
     * Constructor
     *
     * @param string $host
     * @param \Ixudra\Curl\CurlService|null $curl
     */
    public function __construct(string $host, CurlService $curl = null)
    {
        $this->curl = is_null($curl) ? new CurlService() : $curl;
        $this->setHost($host);
    }

    /**
     * Set tenant's oauth0 host
     *
     * @param string $host
     * 
     * @return void
     */
    private function setHost(string $host)
    {
        preg_match('/(https:)/', $host, $matches);

        if (count($matches) <= 0) {
            $host = str_replace('http', 'https', $host);
        }

        $this->host = rtrim($host, '/');
        
    }

    /**
     * Get tenant's host domain
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set oauth0 resource
     *
     * @param \Crazymeeks\Oauth0\Contracts\Resources\ResourceInterface $resourceInterface
     * 
     * @return $this
     */
    public function setResource(ResourceInterface $resourceInterface)
    {
        $this->oauthResource = $resourceInterface;
        return $this;
    }


    /**
     * Execute request on the resource
     *
     * @return \Crazymeeks\Oauth0\Contracts\Resources\ResourceInterface $resourceInterface
     */
    public function execute()
    {

        $to = sprintf("%s/%s", $this->host, $this->oauthResource->getApiEndPoint());
        
        $curl = $this->curl->to($to);

        $curl = $this->mergeHeaders($curl);
        $method = $this->oauthResource->getHttpMethod();

        $response = $curl->withData($this->oauthResource->get($this))
                         ->asJsonRequest()
                         ->returnResponseObject()
                         ->{$method}();
        
        if (!in_array($response->status, [200, 201, 204])) {
            throw new ResourceException($response->content);
        }
        if (!property_exists($response, 'content')) {
            return $this->oauthResource->setResponse(null);
        }
        return $this->oauthResource->setResponse($response->content);
    }

    protected function mergeHeaders($builder)
    {
        if ($this->oauthResource->hasHeaders()) {
            $builder->withHeaders($this->oauthResource->getHeaders());
        }

        return $builder;
    }
}