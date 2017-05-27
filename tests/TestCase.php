<?php

use LGC\Msgpack\MsgpackConcern;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
	use MsgpackConcern;
	
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
