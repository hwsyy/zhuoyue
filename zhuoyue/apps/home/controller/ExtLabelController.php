<?php
/**
 * @author 9029855@qq.com
 */
namespace app\home\controller;

use core\basic\Controller;
use core\basic\Url;
use app\home\model\ParserModel;

class ExtLabelController extends Controller
{

    protected $content;

    protected $model;

    public function __construct()
    {
        $this->model = new ParserModel();
    }

    /* 必备启动函数 */
    public function run($content)
    {
        // 接收数据
        $this->content = $content;

        // 执行个人自定义标签函数
        $this->diylabel();

        //快速标签
        // $this->fastUrl();

        //智能模板路径、自动更新CSS,JS版本号，不用再让客户强制刷新啦
        $this->smartURL();

        //广告词替换
        // $this->disablewords();

        //转换日期
        // $this->transtime();

        // 返回数据
        return $this->content;
    }

    // 扩展单个标签
    private function diylabel()
    {
        //{pboot:walle} 每日一图的图片URL 一般用不到，注释掉了
        //$this->content = str_replace('{pboot:walle}', $this->getBingImage(), $this->content);
        // $this->content = str_replace('%pboot:walle%',$a, $this->content);
        
    }

    //抓取必应每日一图
    private function getBingImage(){
        $url = 'https://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1';
        $data = json_decode(get_url($url));
        $result = $data->images[0];
        $image = 'https://www.bing.com'.$result->url;
        return $image;
    }

    //解析快速URL{@about.1}{@list.2}{@content.3} 为啥要用@，因为$用不了了，咱们就用@召唤神兽吧
    private function fastUrl(){
        $url_break_char = $this->config('url_break_char') ?: '_';
        $url_rule_suffix = $this->config('url_rule_suffix') ?: '.html';
        $url_rule_sort_suffix = $this->config('url_rule_sort_suffix') ? $url_rule_suffix : '/';

        $pattern = '/\{\@(about|list|content)\.([a-z0-9_]+)\}/';
        if (preg_match($pattern, $this->content, $matches)) {
            $this->content = preg_replace_callback(
                $pattern,
                function($matches) use ( $url_break_char, $url_rule_suffix, $url_rule_sort_suffix ){
                    switch ($matches[1]){
                        case 'about';
                            $data = $this->model->getAbout($matches[2]);
                            $data->urlname = $data->urlname ?: 'about';
                            if ($data->sortfilename) {
                                $link = Url::home($data->sortfilename, $url_rule_sort_suffix);
                            } else {
                                $link = Url::home($data->urlname . $url_break_char . $data->scode, $url_rule_sort_suffix);
                            }
                            return $link;
                            break;
                        case 'list';
                            $data = $this->model->getSort($matches[2]);
                            $data->urlname = $data->urlname ?: 'list';
                            if ($data->filename) {
                                $link = Url::home($data->filename, $url_rule_sort_suffix);
                            } else {
                                $link = Url::home($data->urlname . $url_break_char . $data->scode, $url_rule_sort_suffix);
                            }
                            return $link;
                            break;
                        case 'content';
                            $data = $this->model->getContent($matches[2]);
                            $data->urlname = $data->urlname ?: 'list';
                            if ($data->sortfilename && $data->filename) {
                                $link = Url::home($data->sortfilename . '/' . $data->filename, true);
                            } elseif ($data->sortfilename) {
                                $link = Url::home($data->sortfilename . '/' . $data->id, true);
                            } elseif ($data->filename) {
                                $link = Url::home($data->urlname . $url_break_char . $data->scode . '/' . $data->filename, true);
                            } else {
                                $link = Url::home($data->urlname . $url_break_char . $data->scode . '/' . $data->id, true);
                            }
                            return $link;
                            break;
                    }
                },
                $this->content);
        }
    }

    //智能路径
    private function smartURL(){
        $pattern = '/<(.*?)(src=|href=|value=|background=)[\"|\'](images\/|img\/|css\/|js\/|style\/|swiper\/)(.*?)[\"|\'](.*?)>/';
        if (preg_match($pattern, $this->content, $matches)) {
            $this->content = preg_replace_callback(
                $pattern,
                function($matches){
                    if( strstr($matches[4], '.js') || strstr($matches[4],'.css') ){
                        return '<'.$matches[1].$matches[2].'"'.$this->auto_version(APP_THEME_DIR.'/'.$matches[3].$matches[4]).'"'. $matches[5] .'>';
                    }else{
                        return '<'.$matches[1].$matches[2].'"'.APP_THEME_DIR.'/'.$matches[3].$matches[4].'"'. $matches[5] .'>';
                    }
                },
                $this->content);
        }
    }

    //禁用词
    private function disablewords(){
        $words = \core\basic\Db::table('ay_label')->field('value')->where("name='disablewords'")->find();
        if(!!$words){
            $textlist = explode(PHP_EOL, $words);
            foreach ($textlist as $k => $v) {
                $jg = strpos($v, "，") ? "，" : ',';
                $ciar = explode($jg, $v);
                $this->content = str_replace($ciar[0], (isset($ciar[1]) ? $ciar[1] : ''), $this->content);
            }
        }
    }

    //转换日期
    private function transtime(){
        $pattern = '/\{transtime\s?\(([^\}]+)\)\}/';
        if (preg_match($pattern, $this->content, $matches)) {
            $this->content = preg_replace_callback(
                $pattern,
                function($matches){
                    $time = strtotime($matches[1]);
                    $otime = date("Y-m-d H:i",$time);
                    $rtime = date("m-d H:i",$time);
                    $htime = date("H:i",$time);
                    $time = time() - $time;
                    if ($time < 60){
                        $str = '刚刚';
                    }
                    elseif ($time < 60 * 60){
                        $min = floor($time/60);
                        $str = $min.'分钟前';
                    }elseif ($time < 60 * 60 * 24){
                        $h = floor($time/(60*60));
                        $str = $h.'小时前 '.$htime;
                    }elseif ($time < 60 * 60 * 24 * 3){
                        $d = floor($time/(60*60*24));
                        if($d==1)
                            $str = '昨天 '.$rtime;
                        else
                            $str = '前天 '.$rtime;
                    }else{
                        $str = $otime;
                    }
                    return $str;
                },
                $this->content);
        }
    }

    //自动更新时间版本号
    public function auto_version($url){
        $ver = filemtime(DOC_PATH . $url);
        return $url.'?v='.date("YmdHis",$ver);
    }



}