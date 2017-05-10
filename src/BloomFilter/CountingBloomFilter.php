<?php

namespace Igoreus\BloomFilter;

use \Igoreus\BloomFilter\Algo\CountingBloomFilter as CountingBloomFilterInterface;

use Igoreus\BloomFilter\Persist\RedisCbfModulePersister;

/**
 * @author Igor Veremchuk igor.veremchuk@gmail.com
 */
class CountingBloomFilter implements CountingBloomFilterInterface
{

    /** @var RedisCbfModulePersister */
    private $redisPersister;

    /**
     * @param RedisCbfModulePersister $redisPersister
     */
    public function __construct(RedisCbfModulePersister $redisPersister)
    {
        $this->redisPersister = $redisPersister;
    }

    /**
     * @param RedisCbfModulePersister $redisPersister
     * @param $approximateSize
     * @param float $falsePositiveProbability
     * @return CountingBloomFilter
     */
    public static function createFromApproximateSize(
        RedisCbfModulePersister $redisPersister,
        $approximateSize,
        $falsePositiveProbability = 0.001

    ) {
        if ($falsePositiveProbability <= 0 || $falsePositiveProbability >= 1) {
            throw new \RangeException('False positive probability must be between 0 and 1');
        }

        $redisPersister->init($approximateSize, $falsePositiveProbability);

        return new self($redisPersister);
    }

    /**
     * @inheritdoc
     */
    public function add($value)
    {
        return $this->redisPersister->add([$value]);
    }
    /**
     * @inheritdoc
     */
    public function addBulk(array $valueList)
    {
        return $this->redisPersister->add($valueList);
    }

    /**
     * @inheritdoc
     */
    public function has($value)
    {
        return $this->redisPersister->check($value);
    }

    /**
     * @inheritdoc
     */
    public function remove($value)
    {
        return $this->redisPersister->remove([$value]);
    }
}
