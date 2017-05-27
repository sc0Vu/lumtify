<?php

namespace App\Testing\Concerns;

use MessagePack\Packer;
use MessagePack\BufferUnpacker;
use PHPUnit\Framework\Assert as PHPUnit;

trait MessagePack
{
    /**
     * Assert that the response contains msgpack.
     *
     * @param  array|null  $data
     * @return $this
     */
    protected function shouldReturnMsgPack(array $data = null)
    {
        return $this->receiveMsgPack($data);
    }

    /**
     * Assert that the response contains msgpack.
     *
     * @param  array|null  $data
     * @return $this|null
     */
    protected function receiveMsgPack($data = null)
    {
        return $this->seeMsgPack($data);
    }

    /**
     * Assert that the response contains an exact msgpack array.
     *
     * @param  array  $data
     * @return $this
     */
    public function seeMsgPackEquals(array $data)
    {
        $packer = new Packer();

        try {
            $packed = $packer->pack($data);
            $hex = implode("", unpack("H*", $packed));

            if ((mb_strlen($hex) % 2) === 0) {
                $str = implode(" ", str_split($hex, 2));
            }

            PHPUnit::assertEquals($str, $this->response->getContent());

        } catch (\MessagePack\Exception\PackingFailedException $e) {

            PHPUnit::assertEquals(true, false);
        }

        return $this;
    }

    /**
     * Assert that the response contains msgpack.
     *
     * @param  array|null  $data
     * @return $this
     */
    public function seeMsgPack(array $data = null)
    {
        if (is_null($data)) {
            $data = $this->response->getData();

            PHPUnit::assertNotNull($data);
            
            return $this;
        }

        return $this->seeMsgPackEquals($data);
    }
}
