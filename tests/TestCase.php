<?php

namespace Tests;


use Mockery;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
   
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}