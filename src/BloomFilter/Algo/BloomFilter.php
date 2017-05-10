<?php

namespace Igoreus\BloomFilter\Algo;

/**
 * @author Igor Veremchuk igor.veremchuk@gmail.com
 */
interface BloomFilter
{
    /**
     * @param string $value
     */
    public function add($value);

    /**
     * @param array $valueList
     */
    public function addBulk(array $valueList);

    /**
     * @param string $value
     * @return bool
     */
    public function has($value);
}
