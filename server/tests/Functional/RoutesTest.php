<?php

namespace Tests\Functional;

/**
 * every route that calls an upstream API is expected to return a 500 in this
 * test scenario
 */
class RoutesTest extends BaseTestCase
{
    public function testAuthenticateEndpoint()
    {
        $response = $this->runApp('GET', '/v1/authenticate');
        $this->assertEquals(405, $response->getStatusCode());

        $response = $this->runApp('POST', '/v1/authenticate');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testLocalAuthoritiesEndpoint()
    {
        $response = $this->runApp('GET', '/v1/local-authorities');
        $this->assertEquals(500, $response->getStatusCode());

        $response = $this->runApp('POST', '/v1/local-authorities');
        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testEstablishmentsProfileEndpoint()
    {
        $response = $this->runApp('GET', '/v1/establishments-profile/195');
        $this->assertEquals(500, $response->getStatusCode());

        $response = $this->runApp('GET', '/v1/establishments-profile/teststring');
        $this->assertEquals(405, $response->getStatusCode());
    }
}
