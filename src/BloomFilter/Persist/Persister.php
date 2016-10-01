<?php

namespace Igoreus\BloomFilter\Persist;

/**
 * @author Igor Veremchuk igor.veremchuk@gmail.com
 */
interface Persister
{
    /**
     * @param array $bits
     * @return array
     */
    public function getBulk(array $bits);

    /**
     * @param int $bit
     * @return int
     */
    public function get($bit);

    /**
     * @param array $bits
     * @return void
     */
    public function setBulk(array $bits);

    /**
     * @param int $bit
     * @return void
     */
    public function set($bit);

}