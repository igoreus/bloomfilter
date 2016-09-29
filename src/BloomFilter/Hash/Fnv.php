<?php


namespace Igoreus\BloomFilter\Hash;

class Fnv implements Hash
{

    /**
     * @inheritdoc
     */
    public function hash($value)
    {
        return sprintf('%u', hexdec(hash('fnv132', $value)));
    }
}
