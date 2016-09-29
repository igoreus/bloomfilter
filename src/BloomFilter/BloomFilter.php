<?php

namespace Igoreus\BloomFilter;

use Igoreus\BloomFilter\Hash\Hash;
use Igoreus\BloomFilter\Persist\Persister;

/**
 * @author Igor Veremchuk igor.veremchuk@gmail.com
 */
class BloomFilter
{
    /** @var int */
    private $size;
    /** @var Persister */
    private $persister;
    /** @var Hash[]  */
    private $hashes;
    /** @var array */
    private $availableHashes = ['Crc32b', 'Fnv'];

    /**
     * @param Persister $persister
     * @param int $approximateSize
     * @param float $falsePositiveProbability
     * @return BloomFilter
     */
    public static function create(Persister $persister, $approximateSize, $falsePositiveProbability = 0.001)
    {
        $bitSize = self::optimalBitSize($approximateSize, $falsePositiveProbability);
        $hashCount = self::optimalHashCount($approximateSize, $bitSize);


        return new self($persister, $bitSize, $hashCount);
    }

    /**
     * @param Persister $persister
     * @param int $size
     * @param int $hashCount
     */
    public function __construct(Persister $persister, $size, $hashCount)
    {
        $this->persister = $persister;
        $this->size = $size;
        for ($i = 0; $i < $hashCount; $i++) {
            $hash = $this->availableHashes[$i % count($this->availableHashes)];
            $className = 'Igoreus\\BloomFilter\\Hash\\' . $hash;
            $this->hashes[] = new $className;
        }
    }

    /**
     * @param string $value
     */
    public function add($value)
    {
        $this->persister->setBulk($this->getBits($value));
    }

    /**
     * @param string $value
     * @return bool
     */
    public function has($value)
    {
        $bits = $this->persister->getBulk($this->getBits($value));

        return !in_array(0, $bits);
    }

    /**
     * @param string $value
     * @return array
     */
    private function getBits($value)
    {
        $bits = [];
        /** @var Hash $hash */
        foreach ($this->hashes as $index => $hash) {
            $bits[] = $this->hash($hash, $value, $index);
        }

        return $bits;
    }


    /**
     * @param int $setSize
     * @param float $falsePositiveProbability
     * @return int
     */
    private static function optimalBitSize($setSize, $falsePositiveProbability = 0.001)
    {
        return (int) round((($setSize * log($falsePositiveProbability)) / pow(log(2), 2)) * -1);
    }

    /**
     * @param int $setSize
     * @param int $bitSize
     * @return int
     */
    private static function optimalHashCount($setSize, $bitSize)
    {
        return (int) round(($bitSize / $setSize) * log(2));
    }

    /**
     * @param Hash $hash
     * @param string $value
     * @param int $index
     * @return int
     */
    private function hash(Hash $hash, $value, $index)
    {
        return crc32($hash->hash($value . $index)) % $this->size;
    }
}
