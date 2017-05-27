<?php

namespace App\Testing\Concerns;

use Illuminate\Support\Str;
use MessagePack\Packer;
use MessagePack\BufferUnpacker;
use PHPUnit\Framework\Assert as PHPUnit;

trait MessagePack
{
    /**
     * Assert that the response contains messagepack.
     *
     * @return $this
     */
    protected function shouldReturnMessagePack()
    {
        return $this->seeMessagePack();
    }

    /**
     * Assert that the response contains an exact messagepack array.
     *
     * @param  array  $data
     * @return $this
     */
    public function seeMessagePackEquals(array $data)
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

            PHPUnit::fail('Messagepack is not equal.');
        }

        return $this;
    }

    /**
     * Assert that the response contains messagepack.
     *
     * @param  array|null  $data
     * @return $this
     */
    public function seeMessagePack(array $data = null)
    {
        if (is_null($data)) {
            $data = $this->response->getData();

            PHPUnit::assertNotNull($data);
            
            return $this;
        }

        return $this->seeMessagePackContains($data);
    }

    /**
     * Assert that the response contains the given messagepack array.
     *
     * @param  array  $data
     * @return $this
     */
    protected function seeMessagePackContains(array $data)
    {
        $actual = $this->response->getData();

        if (is_null($actual)) {
            return PHPUnit::fail('Invalid messagepack data was returned from the route.');
        }

        $actual = json_encode(array_sort_recursive(
            (array) $actual
        ));

        foreach (array_sort_recursive($data) as $key => $value) {
            $expected = $this->formatToExpectedJson($key, $value);

            call_user_func(['PHPUnit_Framework_Assert', 'assertTrue'],
                Str::contains($actual, $expected),
                "Unable to find Messagepack fragment [{$expected}] within [{$actual}]."
            );
        }

        return $this;
    }
}
