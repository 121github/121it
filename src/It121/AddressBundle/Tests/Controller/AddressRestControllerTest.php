<?php

namespace It121\BackendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressRestControllerTest extends WebTestCase
{
    public function testGetAddress()
	{
        $client   = static::createClient();
        $crawler  = $client->request('GET', '/api/address/');

        $response = $client->getResponse();

        $this->assertJsonResponse($response, 200);
	}
}
