<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年3月8日
 *  
 */
namespace app\api\controller;

use core\basic\Controller;
use app\api\model\DoModel;
use app\api\model\CmsModel;
use core\basic\Url;
use core\sms\AliyunSms;

class DoController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new DoModel();
        $this->modelcms = new CmsModel();
        $this->modelsms=new AliyunSms();
    }

    // 点赞
    public function likes()
    {
        if (! ! $id = request('id', 'int')) {
            $this->model->addLikes($id);
            json(1, '点赞成功');
        } else {
            json(0, '点赞失败');
        }
    }

    // 反对
    public function oppose()
    {
        if (! ! $id = request('id', 'int')) {
            $this->model->addOppose($id);
            json(1, '反对成功');
        } else {
            json(0, '反对失败');
        }
    }
    
    
    public function pro()
    {
        // 获取参数
        $acode = request('acode', 'var') ?: get_default_lg();
        $scode = request('scode', 'var') ?: '';
        $pro = request('pro', 'var') ?: '';
        $doc = request('doc', 'var') ?: '';
        $news = request('news', 'var') ?: '';
        $solution = request('solution', 'var') ?: '';
        $sort = request('sort', 'var') ?: '';

        $num = request('num', 'int') ?: 1000;
        $rorder = request('order');
        if (! preg_match('/^[\w\-,\s]+$/', $rorder)) {
            $order = 'a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.sorting ASC,a.date DESC,a.id DESC';
        } else {
            switch ($rorder) {
                case 'id':
                    $order = 'a.id DESC,a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.sorting ASC,a.date DESC';
                    break;
                case 'date':
                    $order = 'a.date DESC,a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.sorting ASC,a.id DESC';
                    break;
                case 'sorting':
                    $order = 'a.sorting ASC,a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.date DESC,a.id DESC';
                    break;
                case 'istop':
                    $order = 'a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.sorting ASC,a.date DESC,a.id DESC';
                    break;
                case 'isrecommend':
                    $order = 'a.isrecommend DESC,a.istop DESC,a.isheadline DESC,a.sorting ASC,a.date DESC,a.id DESC';
                    break;
                case 'isheadline':
                    $order = 'a.isrecommend DESC,a.istop DESC,a.isheadline DESC,a.sorting ASC,a.date DESC,a.id DESC';
                    break;
                case 'visits':
                case 'likes':
                case 'oppose':
                    $order = $rorder . ' DESC,a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.sorting ASC,a.date DESC,a.id DESC';
                    break;
                case 'random': // 随机取数
                    $db_type = get_db_type();
                    if ($db_type == 'mysql') {
                        $order = "RAND()";
                    } elseif ($db_type == 'sqlite') {
                        $order = "RANDOM()";
                    }
                    break;
                default:
                    if ($rorder) {
                        $orders = explode(',', $rorder);
                        foreach ($orders as $k => $v) {
                            if (strpos($v, 'ext_') === 0) {
                                $orders[$k] = 'e.' . $v;
                            } else {
                                $orders[$k] = 'a.' . $v;
                            }
                        }
                        $order = implode(',', $orders);
                        $order .= ',a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.sorting ASC,a.date DESC,a.id DESC';
                    }
            }
        }
        
        // 读取数据
        $data = $this->modelcms->getLitpro($acode, $scode, $num, $order);
        $url_break_char = $this->config('url_break_char') ?: '_';
        
        foreach ($data as $key => $value) {
            $data[$key]->content = str_replace(STATIC_DIR . '/upload/', get_http_url() . STATIC_DIR . '/upload/', $value->content);
            if ($pro) {
              $pro_link = \core\basic\Db::table('ay_content')->where("id='$pro'")->value('pro_link');
            }
            if ($doc) {
              $pro_link = \core\basic\Db::table('ay_content')->where("id='$doc'")->value('doc_link');
            }
            if ($news) {
              $pro_link = \core\basic\Db::table('ay_content')->where("id='$news'")->value('news_link');
            }
            if ($solution) {
              $pro_link = \core\basic\Db::table('ay_content')->where("id='$solution'")->value('solution_link');
            }

            if($sort){
               $pro_link = \core\basic\Db::table('ay_content_sort')->where("id='$sort'")->value('pro_link'); 

            }
            
             $pro_link_arr=explode(',',$pro_link);

            if(in_array($value->value,$pro_link_arr)){
                $value->selected =true;
            }

            /* if(strstr($doc_link,$value->value)){
                $value->selected =true;
            }*/
        }
        
        // 输出数据
        if (request('page') <= PAGECOUNT) {
            json(1, $data);
        } else {
            return json(0, '已经到底了！');
        }
    }

    
    
    
    
    
}



