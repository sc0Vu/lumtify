<?php

namespace App\Http\Response;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\ResponseTrait;
use MessagePack\Packer;
use MessagePack\BufferUnpacker;

class MessagePackResponse extends Response
{
    use ResponseTrait;

    /**
     * Constructor.
     *
     * @param  mixed  $data
     * @param  int    $status
     * @param  array  $headers
     */
    public function __construct($data = null, $status = 200, $headers = [])
    {
        parent::__construct('', $status, $headers);

        $this->setData($data);
    }

    /**
     * Get the message pack unpacked data from the response.
     *
     * @return mixed
     */
    public function getData()
    {
        try {
            $unpacker = new BufferUnpacker();
            $unpacker->reset($this->data);
            $unpacked = $unpacker->unpack();

        } catch (\MessagePack\Exception\UnpackingFailedException $e) {

            throw new InvalidArgumentException($e->getMessage());
        }

        return $unpacked;
    }

    /**
     * Set the data.
     *
     * @return mixed
     */
    public function setData($data = [])
    {
        $this->original = $data;
        // $packer = new Packer(Packer::FORCE_STR);
        $packer = new Packer();

        try {
            $this->data = $packer->pack($data);

        } catch (\MessagePack\Exception\PackingFailedException $e) {

            throw new InvalidArgumentException($e->getMessage());
        }

        return $this->update();
    }

    /**
     * Updates the content and headers according to the JSON data and callback.
     *
     * @return $this
     */
    protected function update()
    {
        // Only set the header when there is none or when it equals 'text/javascript' (from a previous update with callback)
        // in order to not overwrite a custom definition.
        if (!$this->headers->has('Content-Type') || 'text/javascript' === $this->headers->get('Content-Type')) {
            $this->headers->set('Content-Type', 'application/x-msgpack');
        }
        $hex = implode("", unpack("H*", $this->data));

        if ((mb_strlen($hex) % 2) === 0) {
            return $this->setContent(implode(" ", str_split($hex, 2)));
        }
        throw new InvalidArgumentException('The hex string length that packed data transforms should be divided by two.');
    }
}
