<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date  2017年12月15日
 *  文章控制器
 */
namespace app\admin\controller\content;

use core\basic\Controller;
use app\admin\model\content\RecycleModel;

class RecycleController extends Controller
{

    private $model;

    private $blank;

    public function __construct()
    {
        $this->model = new RecycleModel();
    }

    // 文章列表
    public function index()
    {
        if ((! ! $id = get('id', 'int')) && $result = $this->model->getContent($id)) {
            $this->assign('more', true);
            $this->assign('content', $result);
        } else {
            $this->assign('list', true);
           /* if (! $mcode = get('mcode', 'var')) {
                error('传递的模型编码参数有误，请核对后重试！');
            }*/
            
            if (isset($_GET['keyword'])) {
                if (! ! $scode = get('scode', 'var')) {
                    $result = $this->model->findContent($mcode, $scode, get('keyword', 'vars'));
                } else {
                    $result = $this->model->findContentAll($mcode, get('keyword', 'vars'));
                }
            } else {
                $result = $this->model->getRecycle();
            }
            $this->assign('contents', $result);
            
            // 文章分类下拉列表
            $sort_model = model('admin.content.ContentSort');
            $sort_select = $sort_model->getListSelect($mcode);
            $list_select=$this->model->getListBelong(1);
          //  var_dump($list_select);
           
            // 模型名称
            $this->assign('model_name', model('admin.content.Model')->getName($mcode));
            
            // 扩展字段
            $this->assign('extfield', model('admin.content.ExtField')->getModelField($mcode));
            
            $this->assign('baidu_zz_token', $this->config('baidu_zz_token'));
            $this->assign('baidu_ks_token', $this->config('baidu_ks_token'));
            
            // 前端地址连接符判断
            $url_break_char = $this->config('url_break_char') ?: '_';
            $this->assign('url_break_char', $url_break_char);
            
            // 获取会员分组
            $this->assign('groups', model('admin.member.MemberGroup')->getSelect());
        }
        
        $this->display('content/recycle.html');
    }

   

    

    // 文章放入回收站
    public function del()
    {
        // 执行批量删除
        if ($_POST) {
            if (! ! $list = post('list')) {
                if ($this->model->delContentList($list)) {
                  //  $this->model->delContentExtList($list);
                    $this->log('批量删除文章成功！');
                    success('批量删除成功！', - 1);
                } else {
                    $this->log('批量删除文章失败！');
                    error('批量删除失败！', - 1);
                }
            } else {
                alert_back('请选择要删除的内容！');
            }
        }
        
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        if ($this->model->delContent($id)) {
         //   $this->model->delContentExt($id);
            $this->log('删除文章' . $id . '成功！');
            success('删除成功！', - 1);
        } else {
            $this->log('删除文章' . $id . '失败！');
            error('删除失败！', - 1);
        }
    }


    // 把文章还原
    public function recycle()
    {
        // 执行批量删除
        if ($_POST) {
            if (! ! $list = post('list')) {
                if ($this->model->RecycleContentList($list)) {
                  //  $this->model->delContentExtList($list);
                    $this->log('批量还原文章成功！');
                    success('批量还原成功！', - 1);
                } else {
                    $this->log('批量还原文章失败！');
                    error('批量还原失败！', - 1);
                }
            } else {
                alert_back('请选择要还原的内容！');
            }
        }
        
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        if ($this->model->recycleContent($id)) {
          //  $this->model->recycleContentExt($id);
            $this->log('还原文章' . $id . '成功！');
            success('还原文章成功！', - 1);
        } else {
            $this->log('还原文章' . $id . '失败！');
            error('还原文章失败！', - 1);
        }
    }
    
    public function clearRecycle()
    {
        if ($this->model->clearRecycle()) {
            alert_location('清空成功！', url('/admin/Recycle/index'));
        } else {
            alert_location('清空失败！', url('/admin/Recycle/index'));
        }
    }
    
    public function restoreRecycle()
    {
        if ($this->model->restoreRecycle()) {
            alert_location('一键还原成功！', url('/admin/Recycle/index'));
        } else {
            alert_location('一键还原失败！', url('/admin/Recycle/index'));
        }
    }


   

  
}