<!-- redis -->
       // $redis = \Red::create();
       
        $this->redis->set('name','fengzhishang');
        $name=$this->redis->get('name');
        var_dump($name);
      // var_dump(phpinfo());

        /*$redis = new Redis();
   $redis->connect('127.0.0.1', 6379);
   echo "Connection to server successfully";
   //设置 redis 字符串数据
   $redis->set("tutorial-name", "Redis tutorial");
   // 获取存储的数据并输出
   echo "Stored string in redis:: " . $redis->get("tutorial-name");*/

<!-- 微信采集 -->
       // $data=getWeixinInfo("https://mp.weixin.qq.com/s/uYM_aBUsZkDS7xyb2Vj8Vg");

      //  var_dump($data);
       /* $ip=get_user_ip();
        $json=get_url("https://ip.taobao.com/getIpInfo.php?ip=".$ip);
       var_dump($json->data->COUNTRY_CODE);*/


<!-- 汉字转拼音 -->
      // $pinyin= new \Overtrue\Pinyin\Pinyin;

      /* $aaa=$this->pinyin->convert('测试汉字转拼音');
       var_dump($aaa);*/
     //  exit;

<!-- 输入屏蔽 -->
       /*$str="bbbbb";
       $str=strip_tags($str);
       $reg='echo|return|alert|eval';
       $pattern = '/'.$reg.'/Ui';
             // $preg = "/<script[\s\S]*?<\/script>/i";
            //   var_dump($pattern);
       $str=preg_replace($pattern,'',$str);
       var_dump($str);*/
      
     //  var_dump('aaa');
      /* var_dump(session('aaa'));
       session('aaa','1');*/