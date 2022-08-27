<?php

namespace Tests;


use Mockery;

use Crazymeeks\Oauth0\Provider\ClientSecretId;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{

    /** @var \Crazymeeks\Oauth0\Provider\ClientSecretId */
    protected $clientSecretId;

    /**
     * Setup auth0 secret and id
     *
     * @return void
     */
    protected function setUpClientSecretId()
    {
        $clientSecretId = new ClientSecretId();

        $clientSecretId->setClientId('123445')
                       ->setClientSecret('DLK03909');

        $this->clientSecretId = $clientSecretId;
    }
   
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}