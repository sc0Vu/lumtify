<?php

use Symfony\Component\HttpFoundation\Response;
use App\Http\Response\MessagePackResponse;

class MessagePackResponseTest extends TestCase
{
    /**
     * Message pack response
     * 
     * @var \App\Http\Response\MessagePackResponse
     */
    protected $response;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        $this->response = new MessagePackResponse([
            'hello' => 'lumtify'
        ]);
    }
    
    /**
     * Test Extends Response
     * 
     * @return void
     */
    public function testExtendsResponse()
    {
        $this->assertTrue($this->response instanceof Response);
    }

    /**
     * Test setData.
     *
     * @return void
     */
    public function testSetData()
    {
        $origin = $this->response->getContent();
        $data = $this->response->setData([
            'a' => 'ha'
        ]);

        $this->assertNotEquals($data->getContent(), $origin);
    }

    /**
     * Test getData.
     * 
     * @return void
     */
    public function testGetData()
    {
        $data = $this->response->getData();

        $this->assertEquals([
            'hello' => 'lumtify'
        ], $data);
    }
}
