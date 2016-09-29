<?php

namespace Igoreus\BloomFilter\Persist;

/**
 * @author Igor Veremchuk igor.veremchuk@gmail.com
 */
class Redis implements Persister
{
    const DEFAULT_HOST = 'localhost';
    const DEFAULT_PORT = 6379;
    const DEFAULT_DB = 0;
    const DEFAULT_KEY = 'bloom_filter';
    /** @var string */
    private $key;
    /** @var \Redis */
    private $redis;

    /**
     * @param array $params
     * @return Redis
     */
    public static function create(array $params = [])
    {
        $redis = new \Redis();

        $host = isset($params['host']) ? $params['host'] : self::DEFAULT_HOST;
        $port = isset($params['port']) ? $params['port'] :self::DEFAULT_PORT;
        $db = isset($params['db']) ? $params['db'] : self::DEFAULT_DB;
        $key = isset($params['key']) ? $params['key'] : self::DEFAULT_KEY;

        $redis->connect($host, $port);
        $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
        $redis->select($db);

        return new self($redis, $key);
    }

    /**
     * @param \Redis $redis
     * @param string $key
     */
    public function __construct(\Redis $redis, $key)
    {
        $this->key = $key;
        $this->redis = $redis;
    }

    /**
     * @inheritdoc
     */
    public function getBulk(array $bits)
    {
        $pipe = $this->redis->pipeline();

        foreach ($bits as $bit) {
            $pipe->getBit($this->key, $bit);
        }

        return $pipe->exec();
    }

    /**
     * @inheritdoc
     */
    public function setBulk(array $bits)
    {
        $pipe = $this->redis->pipeline();

        foreach ($bits as $bit) {
            $pipe->setBit($this->key, $bit, 1);
        }

        $pipe->exec();
    }

    /**
     * @inheritdoc
     */
    public function get($bit)
    {
        return $this->redis->getBit($this->key, $bit);
    }

    public function set($bit)
    {
        return $this->redis->setBit($this->key, $bit, 1);
    }


}