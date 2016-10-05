<?php

namespace Igoreus\BloomFilter\Test\Hash;

use Igoreus\BloomFilter\Hash\Fnv;

class VnvTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function hash()
    {
        $hash = new Fnv();
        $value = 'test value';
        $expected =  hexdec(hash('fnv132', $value));

        $this->assertEquals($expected, $hash->hash($value));
    }
}
