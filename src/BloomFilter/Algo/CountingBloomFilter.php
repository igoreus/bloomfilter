<?php

namespace Igoreus\BloomFilter\Algo;

/**
 * @author Igor Veremchuk igor.veremchuk@gmail.com
 */
interface CountingBloomFilter extends BloomFilter
{
    /**
     * @param string $value
     *
     * @return bool
     */
    public function remove($value);
}
