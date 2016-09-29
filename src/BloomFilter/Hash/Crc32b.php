<?php


namespace Igoreus\BloomFilter\Hash;

class Crc32b implements Hash
{

    /**
     * @inheritdoc
     */
    public function hash($value)
    {
        return sprintf('%u', hexdec(hash('crc32b', $value)));
    }
}
