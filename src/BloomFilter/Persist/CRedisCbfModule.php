<?php

namespace Igoreus\BloomFilter\Persist;

/**
 * @author Igor Veremchuk igor.veremchuk@gmail.com
 */
class CRedisCbfModule implements RedisCbfModulePersister
{
    const DEFAULT_HOST = 'localhost';
    const DEFAULT_PORT = 6379;
    const DEFAULT_DB = 0;
    const DEFAULT_KEY = 'counting_bloom_filter';

    /** @var string */
    protected $key;

    /** @var \Credis_Module */
    protected $redisModule;

    /**
     * @param \Credis_Module $redisModule
     * @param $key
     */
    public function __construct(\Credis_Module $redisModule, $key)
    {
        $redisModule->setModule(\Credis_Module::MODULE_COUNTING_BLOOM_FILTER);
        $this->redisModule = $redisModule;
        $this->key = $key;
    }

    /**
     * @param array $params
     * @return CRedisCbfModule
     */
    public static function create(array $params = [])
    {
        $host = isset($params['host']) ? $params['host'] : self::DEFAULT_HOST;
        $port = isset($params['port']) ? $params['port'] :self::DEFAULT_PORT;
        $db = isset($params['db']) ? $params['db'] : self::DEFAULT_DB;
        $key = isset($params['key']) ? $params['key'] : self::DEFAULT_KEY;

        $redis = new \Credis_Client($host, $port, null, '', $db);

        return new self(new \Credis_Module($redis), $key);
    }

    /**
     * @inheritdoc
     */
    public function init($capacity, $errorRate)
    {
        return (bool) $this->redisModule->init($this->key, $capacity, $errorRate);
    }

    /**
     * @inheritdoc
     */
    public function add(array $elements)
    {
        return call_user_func_array([$this->redisModule, 'add'], array_merge([$this->key], $elements));
    }

    /**
     * @inheritdoc
     */
    public function remove(array $elements)
    {
        return call_user_func_array([$this->redisModule, 'rem'], array_merge([$this->key], $elements));
    }

    /**
     * @inheritdoc
     */
    public function check($element)
    {
        return (bool) $this->redisModule->check($this->key, $element);
    }


}
