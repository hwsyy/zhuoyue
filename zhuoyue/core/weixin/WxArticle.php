<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2019年05月27日
 *  微信网页授权
 */
namespace core\weixin;

use core\basic\Config;

class WxArticle
{

    
/**
     * 测试获取公众号文章
     * 登录个人订阅号
     */
    public function test()
    {
        $cookie = 'appmsglist_action_3867176726=card; RK=NUC1CsgiPm; ptcz=c63cbaefaf3e8eb8849053f2928981277e13bf3c600f3c4eb2a6af8501a03f16; pgv_pvid=4260454570; ua_id=860tE5irhaVvgvIkAAAAABGkypL-7yy6kuuqCMgKzNQ=; wxuin=17869232846262; mm_lang=zh_CN; eas_sid=L146n2F2y7x7f0C4U6Z2a0J267; tvfe_boss_uuid=5c8fa4f5aa66de0c; o_cookie=1449036595; AMCV_248F210755B762187F000101%40AdobeOrg=-1712354808%7CMCIDTS%7C18804%7CMCMID%7C48930264323460109631096819571940684307%7CMCAAMLH-1625216270%7C11%7CMCAAMB-1625216270%7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y%7CMCOPTOUT-1624618670s%7CNONE%7CMCAID%7CNONE%7CvVersion%7C4.3.0; sensorsdata2015jssdkcross=%7B%22distinct_id%22%3A%2217a75d181f0c00-0ef0bdcf70b355-3c3b590c-2073600-17a75d181f1af4%22%2C%22first_id%22%3A%22%22%2C%22props%22%3A%7B%22%24latest_traffic_source_type%22%3A%22%E8%87%AA%E7%84%B6%E6%90%9C%E7%B4%A2%E6%B5%81%E9%87%8F%22%2C%22%24latest_search_keyword%22%3A%22%E6%9C%AA%E5%8F%96%E5%88%B0%E5%80%BC%22%2C%22%24latest_referrer%22%3A%22https%3A%2F%2Fwww.baidu.com%2Flink%22%7D%2C%22%24device_id%22%3A%2217a75d181f0c00-0ef0bdcf70b355-3c3b590c-2073600-17a75d181f1af4%22%7D; pac_uid=1_1449036595; iip=0; LW_sid=S12652h793H7l30626b87101K9; LW_uid=z1I6m2M793k7U326b6d8a1X250; ptui_loginuin=1449036595; uuid=b2c04db72b998533a1a02d52613d5404; rand_info=CAESIAPbEQoEqHvoECQ7meSWtRFdUTm5D5dK00zdrS6t8uzi; slave_bizuin=3867176726; data_bizuin=3867176726; bizuin=3867176726; data_ticket=0ikuXNBg5g1ID1eB1ZOVQEK3r34tkqyJvCAuPtfFQIhiLEdCaxcpMDBPSfJCvyp6; slave_sid=NFlFN1pPcUVsaTBKSDRzdzN6YjN6SERMQkNtMHlra1paNXRxOGhVazAxQmdYUkFRNGNZdFlGVkc2M0VCRUJFRkRZVmhTYTZ3NzI4dlM1T3Vxem5WNFI0bDFDQVBEazlHZzhVTWFrOXZQc2dRZHhNbnJhWXlWYkxjRnlOM21VVFBWUWMwbDdzQ1BSNlRsb01M; slave_user=gh_7f522bbd4055; xid=cea0105861c51a82146a411b00e8e294';
        $key    = '天天向上';//  农产品  家电等关键词
        $res    = $this->query($key, $cookie);
//        $res    = $this->getList($fakeid, $cookie); // 获取公众号列表  fakeid 公众号标识  
//        $res    = $this->getInfo($url); // 微信文章链接
        var_dump($res);die;
        
    }

    /**
     * @param $key
     * @param $cookie
     * @return mixed
     * 根据关键词获取公众号
     */
    public function query($key, $cookie)
    {
        $url = 'https://mp.weixin.qq.com/cgi-bin/searchbiz?action=search_biz&begin=0&count=5&query=' . $key . '&token=1017040970&lang=zh_CN&f=json&ajax=1';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_COOKIE, $cookie); // 从证书中检查SSL加密算法是否存在
        $userAgent = 'user-agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }

    /**
     * @param $fakeid
     * @param $cookie
     * @return mixed
     * 获取微信公众号文章列表
     */
    public function getList($fakeid, $cookie)
    {
        $url = "https://mp.weixin.qq.com/cgi-bin/appmsg?action=list_ex&begin=0&count=5&fakeid=$fakeid&type=9&query=&token=743327251&lang=zh_CN&f=json&ajax=1";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_COOKIE, $cookie); // 从证书中检查SSL加密算法是否存在
        $userAgent = 'user-agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $output2 = curl_exec($ch);
        curl_close($ch);
        return json_decode($output2, true);
    }

    /**
     * @param $url
     * @return mixed
     * 获取微信文章
     */
    public function getInfo($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $re = curl_exec($ch);
        curl_close($ch);
        return $re;
    }
}
