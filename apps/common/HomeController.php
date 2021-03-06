<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年04月12日
 *  前台公共控制类
 */
namespace app\common;

use core\basic\Controller;
use core\basic\Config;

class HomeController extends Controller
{

    public function __construct()
    {
        // 自动缓存基础信息
        cache_config();
        
        // 站点关闭检测
        if (! ! $close_site = Config::get('close_site')) {
            $close_site_note = Config::get('close_site_note');
            error($close_site_note ?: '本站维护中，请稍后再访问，带来不便，敬请谅解！');
        }

        // 自动跳转HTTPS
        if (! is_https() && ! ! $tohttps = Config::get('to_https')) {
            header("Location: https://" . $_SERVER['HTTP_HOST'], true, 301);
        }
        
        // IP访问黑白名单检测
        $user_ip = get_user_ip(); // 获取用户IP
        if (filter_var($user_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // ip黑名单
            $ip_deny = Config::get('ip_deny', true);
            foreach ($ip_deny as $key => $value) {
                if (network_match($user_ip, $value)) {
                    error('本站启用了黑名单功能，您的IP(' . $user_ip . ')不允许访问！');
                }
            }
            // ip白名单
            $ip_allow = Config::get('ip_allow', true);
            foreach ($ip_allow as $key => $value) {
                if (network_match($user_ip, $value)) {
                    $allow = true;
                }
            }
            
            // 如果设置了白名单，IP不在白名单内，则阻止访问
            if ($ip_allow && ! isset($allow)) {
                error('本站启用了白名单功能，您的IP(' . $user_ip . ')不在允许范围！');
            }
        }
        
        // 语言绑定域名检测， 如果匹配到多语言绑定则自动设置当前语言
        $lgs = Config::get('lgs');
        if (count($lgs) > 1) {
            $domain = get_http_host();
            foreach ($lgs as $value) {
                if ($value['domain'] == $domain) {
                    cookie('lg', $value['acode']);
                    break;
                }
            }
        }
        
        // 未设置语言时使用默认语言
        if (! isset($_COOKIE['lg'])) {
            cookie('lg', get_default_lg());
        }
        
        // 手机自适应主题
        if ($this->config('open_wap')) {
            if ($this->config('wap_domain') && $this->config('wap_domain') == get_http_host()) {
                $this->setTheme(get_theme() . '/wap'); // 已绑域名并且一致则自动手机版本
            } elseif (is_mobile() && $this->config('wap_domain') && $this->config('wap_domain') != get_http_host()) {
                if (is_https()) {
                    $pre = 'https://';
                } else {
                    $pre = 'http://';
                }
                header('Location:' . $pre . $this->config('wap_domain') . URL); // 手机访问并且绑定了域名，但是访问域名不一致则跳转
            } elseif (is_mobile()) { // 其他情况手机访问则自动手机版本
                $this->setTheme(get_theme() . '/wap');
            } else { // 其他情况，电脑版本
                $this->setTheme(get_theme());
            }
        } else { // 未开启手机，则一律电脑版本
            $this->setTheme(get_theme());
        }
    }
}