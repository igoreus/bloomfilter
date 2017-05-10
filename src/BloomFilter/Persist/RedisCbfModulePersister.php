<?php

namespace Igoreus\BloomFilter\Persist;

/**
 * @author Igor Veremchuk igor.veremchuk@gmail.com
 */
interface RedisCbfModulePersister
{
    /**
     * @param int $capacity
     * @param float $errorRate
     * @return bool
     */
    public function init($capacity, $errorRate);

    /**
     * @param array $elements
     * @return bool
     */
    public function add(array $elements);

    /**
     * @param array $elements
     * @return bool
     */
    public function remove(array $elements);

    /**
     * @param string $element
     * @return bool
     */
    public function check($element);
}