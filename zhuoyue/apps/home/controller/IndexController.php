<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年2月14日
 *  首页控制器
 */
namespace app\home\controller;

use core\basic\Controller;
use app\home\model\ParserModel;
use core\basic\Config;
use core\basic\Url;
use core\cache\Redis;
//use Overtrue\Pinyin\Pinyin;






class IndexController extends Controller
{

    protected $parser;

    protected $model;

    protected $htmldir;

    public function __construct()
    {
        $this->parser = new ParserController();

        $this->redis=new Redis();
         
        $this->model = new ParserModel();
      //  $this->pinyin=new Pinyin();
       
        $this->htmldir = $this->config('tpl_html_dir') ? $this->config('tpl_html_dir') . '/' : '';

    }

    // 空拦截器, 实现文章路由转发
    public function _empty()
    {
        // 地址类型
        $url_rule_type = $this->config('url_rule_type') ?: 3;
        
        if (P) { // 采用pathinfo模式及p参数伪静态模式
            if ($url_rule_type == 2 && stripos(URL, $_SERVER['SCRIPT_NAME']) !== false) { // 禁止伪静态时带index.php访问
                _404('您访问的内容不存在，请核对后重试！');
            }
            $path = explode('/', P);
            if (! defined('URL_BIND')) {
                array_shift($path); // 去除模块部分
            }
        } elseif ($url_rule_type == 3 && isset($_SERVER["QUERY_STRING"]) && $qs = $_SERVER["QUERY_STRING"]) { // 采用简短传参模式
            parse_str($qs, $output);
            unset($output['page']); // 去除分页
            if ($output && ! current($output)) { // 第一个路径参数不能有值，否则非标准路径参数
                $path = key($output); // 第一个参数为路径信息，注意PHP数组会自动将key点符号转换下划线
                $path = trim($path, '/'); // 去除两端斜杠
                $url_rule_suffix = substr($this->config('url_rule_suffix'), 1);
                if (! ! $pos = strripos($path, '_' . $url_rule_suffix)) {
                    $path = substr($path, 0, $pos); // 去扩展
                }
                $path = explode('/', $path);
            } elseif (get('tag')) { // 对于兼容模式tag需要自动跳转tag独立页面
                $tag = new TagController();
                $tag->index();
            } elseif (get('keyword')) { // 兼容模式搜索处理
                $search = new SearchController();
                $search->index();
            }
        }

      
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


       // $data=getWeixinInfo("https://mp.weixin.qq.com/s/uYM_aBUsZkDS7xyb2Vj8Vg");

      //  var_dump($data);
       /* $ip=get_user_ip();
        $json=get_url("https://ip.taobao.com/getIpInfo.php?ip=".$ip);
       var_dump($json->data->COUNTRY_CODE);*/

      // $pinyin= new \Overtrue\Pinyin\Pinyin;

      /* $aaa=$this->pinyin->convert('测试汉字转拼音');
       var_dump($aaa);*/
     //  exit;

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
        
        if (isset($path) && is_array($path)) {
            
            // 地址分隔符
            $url_break_char = $this->config('url_break_char') ?: '_';
            
            // 判断第一个参数中组合信息
            if (strpos($path[0], $url_break_char)) {
                $param = explode($url_break_char, $path[0]);
            } else {
                $param[] = $path[0];
            }
            
            // 判断第一个参数是模型还是自定义分类
            if (! !preg_match('/^(list_[0-9]+)|(^about_[0-9]+)/', $path[0])) {
                $scode = $param[1];
                if (isset($param[2])) {
                    $_GET['page'] = $param[2]; // 分页
                }
            } else {
                define('CMS_PAGE_CUSTOM', true); // 自定义名称后分页比正常少了一个参数 (list_1_1=>product_1)
                $scode = $param[0];
                if (isset($param[1])) {
                    $_GET['page'] = $param[1]; // 分页
                }
            }
            
            // 路由
            switch ($param[0]) {
                case 'search':
                case 'keyword':
                    $search = new SearchController();
                    $search->index();
                    break;
                case 'sole':
                    $sole = new SoleController();
                    $sole->index();
                    break;
                case 'message':
                    $msg = new MessageController();
                    $msg->index();
                    break;
                case 'moban':
                    $moban = new MobanController();
                    $moban->index();
                    break;
                
                case 'form':
                    $_GET['fcode'] = $path[1];
                    $form = new FormController();
                    $form->index();
                    break;
                case 'sitemap':
                case 'Sitemap':
                    $sitemap = new SitemapController();
                    $sitemap->index();
                    break;
                case 'tag':
                    $tag = new TagController();
                    $tag->index();
                    break;
                case 'member':
                    $member = new MemberController();
                    $member->{$path[1]}();
                    break;
                case 'comment':
                    $comment = new CommentController();
                    $comment->{$path[1]}();
                    break;
                default:
                    if (get($param[0])) {
                        $this->getIndex();
                    } else {
                        
                        if (count($path) > 1) {
                            define('CMS_PAGE', false); // 使用普通分页处理模型
                            if (! ! ($data = $this->model->getContent($path[1])) && ($data->scode == $scode || $data->sortfilename == $scode) && $data->type == 2) {
                                if ($data->acode != get_lg() && Config::get('lgautosw') !== '0') {
                                    cookie('lg', $data->acode); // 调用内容语言与当前语言不一致时，自动切换语言
                                }
                                $this->getContent($data);
                            } else {
                                _404('您访问的内容不存在，请核对后重试！');
                            }
                        } else {
                            define('CMS_PAGE', true); // 使用cms分页处理模型
                            if (! ! $sort = $this->model->getSort($scode)) {
                                if ($sort->acode != get_lg() && Config::get('lgautosw') !== '0') {
                                    cookie('lg', $sort->acode); // 调用栏目语言与当前语言不一致时，自动切换语言
                                }
                                if ($sort->type == 1) {
                                    $this->getAbout($sort);
                                } else {
                                    $this->getList($sort);
                                }
                            } else {
                                _404('您访问的栏目不存在，请核对后重试！');
                            }
                        }
                    }
            }
        } else {
            $this->getIndex();
        }
    }

    // 首页
    private function getIndex()
    {
        $content = parent::parser($this->htmldir . 'index.html'); // 框架标签解析

        $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
        $content = str_replace('{pboot:pagetitle}', $this->config('index_title') ?: '{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
        $content = $this->parser->parserPositionLabel($content, - 1, '首页', SITE_INDEX_DIR . '/'); // CMS当前位置标签解析
        $content = $this->parser->parserSpecialPageSortLabel($content, 0, '', SITE_INDEX_DIR . '/'); // 解析分类标签
        $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
        $this->cache($content, true);
    }

    // 列表
    private function getList($sort)
    {   
        
        if ($sort->listtpl) {
            $this->checkPageLevel($sort->gcode, $sort->gtype, $sort->gnote);
            //单独页（后台无法添加的模板页）
           /* if(($page=get('page'))=='duibi'){
                $content = parent::parser($this->htmldir . 'pro_duibi.html'); // 框架标签解析

            }else{
                 $content = parent::parser($this->htmldir . $sort->listtpl); // 框架标签解析
            }*/
            $content = parent::parser($this->htmldir . $sort->listtpl); // 框架标签解析
            $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
            $pagetitle = $sort->title ? "{sort:title}" : "{sort:name}"; // 页面标题
            $content = str_replace('{pboot:pagetitle}', $pagetitle, $content);
            $content = str_replace('{pboot:pagekeywords}', '{sort:keywords}', $content);
            $content = str_replace('{pboot:pagedescription}', '{sort:description}', $content);
            $content = $this->parser->parserPositionLabel($content, $sort->scode); // CMS当前位置标签解析
            $content = $this->parser->parserSortLabel($content, $sort); // CMS分类信息标签解析
            $content = $this->parser->parserListLabel($content, $sort->scode); // CMS分类列表标签解析
            $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
        } else {
            error('请到后台设置分类栏目列表页模板！');
        }
        $this->cache($content, true);
    }

    // 详情页
    private function getContent($data)
    {
        // 读取模板
        if (! ! $sort = $this->model->getSort($data->scode)) {
            if ($sort->contenttpl) {
                $this->checkPageLevel($sort->gcode, $sort->gtype, $sort->gnote); // 检查栏目权限
                $this->checkPageLevel($data->gcode, $data->gtype, $data->gnote); // 检查内容权限
                $template=get('template');
                if($template){
                  $content = parent::parser($this->htmldir . $template.'.html');  
                }else{
                  $content = parent::parser($this->htmldir . $sort->contenttpl); // 框架标签解析  
                }
            //    $content = parent::parser($this->htmldir . $sort->contenttpl); // 框架标签解析
                $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
                $content = str_replace('{pboot:pagetitle}', '{content:subtitle}', $content);
                $content = str_replace('{pboot:pagekeywords}', '{content:keywords}', $content);
                $content = str_replace('{pboot:pagedescription}', '{content:description}', $content);
                $content = $this->parser->parserPositionLabel($content, $sort->scode); // CMS当前位置标签解析
                $content = $this->parser->parserSortLabel($content, $sort); // CMS分类信息标签解析
                $content = $this->parser->parserCurrentContentLabel($content, $sort, $data); // CMS内容标签解析
                $content = $this->parser->parserCommentLabel($content); // 文章评论
                $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
            } else {
                error('请到后台设置分类栏目内容页模板！');
            }
        } else {
            _404('您访问内容的分类已经不存在，请核对后再试！');
        }
        $this->cache($content, true);
    }

    // 单页
    private function getAbout($sort)
    {
        // 读取数据
       
        if (! $data = $this->model->getAbout($sort->scode)) {
            _404('您访问的内容不存在，请核对后重试！');
        }
       
        if ($sort->contenttpl) {
            $this->checkPageLevel($sort->gcode, $sort->gtype, $sort->gnote);
            $content = parent::parser($this->htmldir . $sort->contenttpl); // 框架标签解析
            $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
            $pagetitle = $sort->title ? "{sort:title}" : "{content:title}"; // 页面标题
            $content = str_replace('{pboot:pagetitle}', $pagetitle, $content);
            $content = str_replace('{pboot:pagekeywords}', '{sort:keywords}', $content);
            $content = str_replace('{pboot:pagedescription}', '{sort:description}', $content);
            $content = $this->parser->parserPositionLabel($content, $sort->scode); // CMS当前位置标签解析
            $content = $this->parser->parserSortLabel($content, $sort); // CMS分类信息标签解析
            $content = $this->parser->parserCurrentContentLabel($content, $sort, $data); // CMS内容标签解析
            $content = $this->parser->parserCommentLabel($content); // 文章评论
            $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
        } else {
            error('请到后台设置分类栏目内容页模板！');
        }
        
        $this->cache($content, true);
    }

    // 检查页面权限
    private function checkPageLevel($gcode, $gtype, $gnote)
    {
        if ($gcode) {
            $deny = false;
            $gtype = $gtype ?: 4;
            switch ($gtype) {
                case 1:
                    if ($gcode <= session('pboot_gcode')) {
                        $deny = true;
                    }
                    break;
                case 2:
                    if ($gcode < session('pboot_gcode')) {
                        $deny = true;
                    }
                    break;
                case 3:
                    if ($gcode != session('pboot_gcode')) {
                        $deny = true;
                    }
                    break;
                case 4:
                    if ($gcode > session('pboot_gcode')) {
                        $deny = true;
                    }
                    break;
                case 5:
                    if ($gcode >= session('pboot_gcode')) {
                        $deny = true;
                    }
                    break;
            }
            if ($deny) {
                $gnote = $gnote ?: '您的权限不足，无法浏览本页面！';
                if (session('pboot_uid')) { // 已经登录
                    error($gnote);
                } else {
                    if ($this->config('login_no_wait')) {
                        location(Url::home('member/login', null, "backurl=" . urlencode(get_current_url())));
                    } else {
                        error($gnote, Url::home('member/login', null, "backurl=" . urlencode(get_current_url())));
                    }
                }
            }
        }
    }
}