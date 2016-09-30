<?php

namespace Igoreus\BloomFilter\Persist;

/**
 * @author Igor Veremchuk igor.veremchuk@gmail.com
 */
class BitArray implements Persister
{
    const BITS_IN_BYTE = 8;
    /** @var int */
    private $size;
    /** @var string */
    private $bytes;

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = (int) $size;
        if ($this->size  < 0) {
            throw new \RangeException('Value must be greater than zero.');
        }

        $this->bytes = str_repeat(chr(0), $this->size);
    }

    /**
     * @param int $value
     */
    private function assertOffset($value)
    {
        if (null == $this->bytes) {
            throw new \LogicException('Size must be set.');
        }

        if (!is_int($value)) {
            throw new \UnexpectedValueException('Value must be an integer.');
        }

        if ($value < 0) {
            throw new \RangeException('Value must be greater than zero.');
        }
    }

    /**
     * @inheritdoc
     */
    public function getBulk(array $bits)
    {
        $resultBits = [];
        foreach ($bits as $bit) {
            $resultBits[] = $this->get($bit);
        }

        return $resultBits;
    }

    /**
     * @inheritdoc
     */
    public function setBulk(array $bits)
    {
        foreach ($bits as $bit) {
            $this->set($bit);
        }
    }

    /**
     * @inheritdoc
     */
    public function get($bit)
    {
        $this->assertOffset($bit);
        $byte = $this->offsetToByte($bit);
        $byte = ord($this->bytes[$byte]);
        $bit = (bool) ($this->bitPos($bit) & $byte);

        return $bit;
    }

    /**
     * @inheritdoc
     */
    public function set($bit)
    {
        $this->assertOffset($bit);
        $offsetByte = $this->offsetToByte($bit);
        $byte = ord($this->bytes[$offsetByte]);
        $pos = $this->bitPos($bit);

        $byte |= $pos;
        $this->bytes[$offsetByte] = chr($byte);
    }

    /**
     * @param int $offset
     * @return int
     */
    private function offsetToByte($offset)
    {
        return (int) floor($offset / self::BITS_IN_BYTE);
    }

    /**
     * @param int $offset
     * @return int
     */
    private function bitPos($offset)
    {
        return (int) pow(2, $offset % self::BITS_IN_BYTE);
    }
}
