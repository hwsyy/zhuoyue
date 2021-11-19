<?php
/**
 * @copyright (C)2020-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2020年3月8日
 *  留言控制器     
 */
namespace app\home\controller;

use core\basic\Controller;
use app\home\model\ParserModel;
use core\basic\Url;
use core\basic\Db;


class SoleController extends Controller
{

   public function __construct()
    {
        $this->parser = new ParserController();
        $this->htmldir = $this->config('tpl_html_dir') ? $this->config('tpl_html_dir') . '/' : '';
    }

    // 内容搜索
    public function index()
    {
        
        
        $content = parent::parser($this->htmldir . 'sole.html'); // 框架标签解析
        $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
        $pagetitle = get('keyword') ? get('keyword') . '-' : '';
        $content = str_replace('{pboot:pagetitle}', $this->config('search_title') ?: $pagetitle . '搜索结果-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
       /* $content = $this->parser->parserPositionLabel($content, 0, '搜索', Url::home('search')); // CMS当前位置标签解析
        $content = $this->parser->parserSpecialPageSortLabel($content, - 1, '搜索结果', Url::home('search')); // 解析分类标签
        $content = $this->parser->parserSearchLabel($content); // 搜索结果标签*/
        $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
        echo $content; // 搜索页面不缓存
        exit();
    }

    public function info(){
        $id=get('id');
        //var_dump($id);
        
        $content = parent::parser($this->htmldir . 'soleinfo.html'); // 框架标签解析
        $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
        $pagetitle = get('keyword') ? get('keyword') . '-' : '';
        $content = str_replace('{pboot:pagetitle}', $this->config('search_title') ?: $pagetitle . '搜索结果-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
       /* $content = $this->parser->parserPositionLabel($content, 0, '搜索', Url::home('search')); // CMS当前位置标签解析
        $content = $this->parser->parserSpecialPageSortLabel($content, - 1, '搜索结果', Url::home('search')); // 解析分类标签
        $content = $this->parser->parserSearchLabel($content); // 搜索结果标签*/
        $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
        echo $content; // 搜索页面不缓存
        exit();
    }
  
   
   
}