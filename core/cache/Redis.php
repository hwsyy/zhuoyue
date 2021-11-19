<?php
namespace core\cache;
class Redis{

    static private $redis = NULL;

    private $_red = NULL;

    private $_return_data = NULL;

    static public function create() {
        if(self::$redis) {
            return Redis::$redis;
        }

        self::$redis = new self;
        return self::$redis;
    }

    // 当调用的方法不存在会调用call方法,当方法不存在就去访问redis自身的方法
    public function __call($func, $params) {
        if ($func == 'multi') {
            $this->_return_data = $this->_red->multi($params[0]);

        } else {
            // 调用red对象下的方法，并传递参数
            $this->_return_data = call_user_func_array(array(&$this->_red, $func), $params);

        }
        return $this->_return_data;
    }

    public function __construct() {

        $this->_red = new \Redis();
        $this->_red->connect('127.0.0.1','6379');
       // $this->_red->select(C('REDIS_DB') ?: 0);
        return Redis::$redis;
    }

    public function ttl($key)
    {
        $new_key = $key;
        return $this->_red->ttl($new_key);
    }

    public function del($key)
    {
        $new_key = $key;
        return $this->_red->del($new_key);
    }

    public function delete($key)
    {
        $new_key = $key;
        return $this->_red->delete($new_key);
    }

    public function rename($key1, $key2)
    {
        $new_key1 =$key1;
        $new_key2 = $key2;
        return $this->_red->rename($new_key1, $new_key2);
    }

    public function exists($key)
    {
        $new_key = $key;
        return $this->_red->exists($new_key);
    }

    public function setex($key, $time ,$value)
    {
        $new_key = $key;
        return $this->_red->setex($new_key, $time, $value);
    }

    public function set($key, $value)
    {
        $new_key =  $key;
        return $this->_red->set($new_key, $value);
    }

    public function get($key)
    {
        $new_key = $key;
        $a = $this->_red->get($new_key);
        return $a;
    }
}