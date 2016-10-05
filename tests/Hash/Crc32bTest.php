<?php

namespace Igoreus\BloomFilter\Test\Hash;

use Igoreus\BloomFilter\Hash\Crc32b;

class Crc32bTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function hash()
    {
        $hash = new Crc32b();
        $value = 'test value';
        $expected =  hexdec(hash('crc32b', $value));

        $this->assertEquals($expected, $hash->hash($value));
    }
}
