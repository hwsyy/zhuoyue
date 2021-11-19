<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2016年11月5日
 *  用户前端入口文件
 */

// 定义为入口文件
define('IS_INDEX', true);

// 入口文件地址绑定
define('URL_BIND', 'home');

// PHP版本检测
if (PHP_VERSION < '5.3') {
    header('Content-Type:text/html; charset=utf-8');
    exit('您服务器PHP的版本太低，程序要求PHP版本不小于5.3');
}

  
  $redis = new Redis();
   $redis->connect('127.0.0.1', 6379);
   echo "Connection to server successfully";
   //存储数据到列表中
   $redis->lpush("tutorial-list", "Redis");
   $redis->lpush("tutorial-list", "Mongodb");
   $redis->lpush("tutorial-list", "Mysql");
   // 获取存储的数据并输出
   $arList = $redis->lrange("tutorial-list", 0 ,5);
   echo "Stored string in redis";
   print_r($arList);
   //exit;


// 引用内核启动文件
require dirname(__FILE__) . '/core/start.php';




