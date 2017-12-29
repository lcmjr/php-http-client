<?php

namespace SendGrid\Test;

use SendGrid\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var MockClient */
    private $client;
    /** @var string */
    private $host;
    /** @var array */
    private $headers;

    protected function setUp()
    {
        $this->host = 'https://localhost:4010';
        $this->headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer SG.XXXX'
        );
        $this->client = new MockClient($this->host, $this->headers);
    }

    public function testConstructor()
    {
        $this->assertAttributeEquals($this->host, 'host', $this->client);
        $this->assertAttributeEquals($this->headers, 'headers', $this->client);
        $this->assertAttributeEquals('/v3', 'version', $this->client);
        $this->assertAttributeEquals(array(), 'path', $this->client);
        $this->assertAttributeEquals(array(), 'curlOptions', $this->client);
        $this->assertAttributeEquals(false, 'retryOnLimit', $this->client);
        $this->assertAttributeEquals(array('get', 'post', 'patch',  'put', 'delete'), 'methods', $this->client);
    }

    public function test_()
    {
        $client = new MockClient($this->host, $this->headers, '/v3');
        $client->setCurlOptions(array('foo' => 'bar'));
        $client = $client->_('test');

        $this->assertAttributeEquals(array('test'), 'path', $client);
        $this->assertAttributeEquals(array('foo' => 'bar'), 'curlOptions', $client);
    }

    public function test__call()
    {
        $client = $this->client->get();
        $this->assertAttributeEquals('https://localhost:4010/v3/', 'url', $client);

        $queryParams = array('limit' => 100, 'offset' => 0);
        $client = $this->client->get(null, $queryParams);
        $this->assertAttributeEquals('https://localhost:4010/v3/?limit=100&offset=0', 'url', $client);

        $requestBody = array('name' => 'A New Hope');
        $client = $this->client->get($requestBody);
        $this->assertAttributeEquals($requestBody, 'requestBody', $client);

        $requestHeaders = array('X-Mock: 200');
        $client = $this->client->get(null, null, $requestHeaders);
        $this->assertAttributeEquals($requestHeaders, 'requestHeaders', $client);

        $client = $this->client->version('/v4');
        $this->assertAttributeEquals('/v4', 'version', $client);

        $client = $this->client->path_to_endpoint();
        $this->assertAttributeEquals(array('path_to_endpoint'), 'path', $client);
        $client = $client->one_more_segment();
        $this->assertAttributeEquals(array('path_to_endpoint', 'one_more_segment'), 'path', $client);
    }

    public function testGetHost()
    {
        $client = new Client('https://localhost:4010');
        $this->assertSame('https://localhost:4010', $client->getHost());
    }

    public function testGetHeaders()
    {
        $client = new Client('https://localhost:4010', array('Content-Type: application/json', 'Authorization: Bearer SG.XXXX'));
        $this->assertSame(array('Content-Type: application/json', 'Authorization: Bearer SG.XXXX'), $client->getHeaders());

        $client2 = new Client('https://localhost:4010');
        $this->assertSame(array(), $client2->getHeaders());
    }

    public function testGetVersion()
    {
        $client = new Client('https://localhost:4010', array(), '/v3');
        $this->assertSame('/v3', $client->getVersion());

        $client = new Client('https://localhost:4010');
        $this->assertSame('/v3', $client->getVersion());
    }

    public function testGetPath()
    {
        $client = new Client('https://localhost:4010', array(), null, array('/foo/bar'));
        $this->assertSame(array('/foo/bar'), $client->getPath());

        $client = new Client('https://localhost:4010');
        $this->assertSame(array(), $client->getPath());
    }

    public function testGetCurlOptions()
    {
        $client = new Client('https://localhost:4010');
        $client->setCurlOptions(array(CURLOPT_PROXY => '127.0.0.1:8080'));
        $this->assertSame(array(CURLOPT_PROXY => '127.0.0.1:8080'), $client->getCurlOptions());

        $client = new Client('https://localhost:4010');
        $this->assertSame(array(), $client->getCurlOptions());
    }

    public function testCurlMulti()
    {
        $client = new Client('https://localhost:4010');
        $client->setIsConcurrentRequest(true);
        $client->get(array('name' => 'A New Hope'));
        $client->get(null, null, array('X-Mock: 200'));
        $client->get(null, array('limit' => 100, 'offset' => 0));

        // returns 3 response object
        $this->assertEquals(3, count($client->send()));
    }
}
