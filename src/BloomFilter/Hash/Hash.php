<?php

namespace Igoreus\BloomFilter\Hash;

interface Hash
{
    /**
     * @param $value
     * @return mixed
     */
    public function hash($value);
}
