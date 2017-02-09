<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/test');

        $this->assertEquals(
            "hello lumen" . $this->app->version(), $this->response->getContent()
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExampleApi()
    {
        $this->get('/api/test');

        $this->assertEquals(
            "hello api", $this->response->getContent()
        );
    }
}
