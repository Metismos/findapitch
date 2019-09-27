<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class OurWebTestCase extends WebTestCase
{
    /** @var  Client */
    protected $client;

    protected static function createClient(array $options = array(), array $server = array())
    {
        static::bootKernel($options);

        $server = [
            'HTTP_Accept' => 'application/json',
        ];

        $client = static::$container->get('test.client');
        $client->catchExceptions(false);
        $client->setServerParameters($server);

        return $client;
    }

    protected function setUp()
    {
        $this->client = static::createClient();
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
