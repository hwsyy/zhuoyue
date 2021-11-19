<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年3月21日
 *  站点设置控制器
 */
namespace app\admin\controller\content;

use core\basic\Controller;
use app\admin\model\content\SiteModel;
use core\basic\Config;

class SiteController extends Controller
{

    public function __construct()
    {
        $this->model = new SiteModel();
    }

    // 显示站点信息
    public function index()
    {
         // 获取公司配置
        $site_description = explode(',',$this->config('site_description'));
      //  var_dump($site_description);
        $site_column = explode(',',$this->config('site_column'));
        $site_leixing = explode(',',$this->config('site_leixing'));
      //  var_dump($site_column);
        $site_config=array();
        foreach($site_description as $k=>$v){
          //  var_dump($v);
            $site_config[$k]->description=$v;
            $site_config[$k]->column=$site_column[$k];
            $site_config[$k]->leixing=$site_leixing[$k];
        }
         $this->assign('site_config', $site_config);
        // 获取主题列表
        $themes = dir_list(ROOT_PATH . current($this->config('tpl_dir')));
        $this->assign('themes', $themes);
        
        // 获取系统配置
        $this->assign('sites', $this->model->getList());
     /*  $sites=$this->model->getList();
      // var_dump($sites->logo);
       $file=get_http_url().$sites->logo;
       $size=getimagesize($file);
       var_dump($size);*/
        
        // 显示
        $this->display('content/site.html');
    }

    // 修改站点信息
    public function mod()
    {
        if (! $_POST) {
            return;
        }
        
        $data = array(
            'title' => post('title'),
            'subtitle' => post('subtitle'),
            'domain' => post('domain'),
            'logo' => post('logo'),
            'flogo'=>post('flogo'),
            'favicon'=>post('favicon'),
            'flogo_alt'=>post('flogo_alt'),
            'weixin'=>post('weixin'),
            'email'=>post('email'),
            'phone'=>post('phone'),
            'logo_alt'=>post('logo_alt'),
            'keywords' => post('keywords'),
            'description' => post('description'),
            'icp' => post('icp'),
            'icpnumber'=>post('icpnumber'),
            'theme' => basename(post('theme')) ?: 'default',
            'statistical' => post('statistical'),
            'copyright' => post('copyright')
        );
        
        path_delete(RUN_PATH . '/config'); // 清理缓存的配置文件
        if ($this->model->checkSite()) {
            if ($this->model->modSite($data)) {
                $this->log('修改站点信息成功！');
                success('修改成功！', - 1);
            } else {
                location(- 1);
            }
        } else {
            $data['acode'] = session('acode');
            if ($this->model->addSite($data)) {
                $this->log('修改站点信息成功！');
                success('修改成功！', - 1);
            } else {
                location(- 1);
            }
        }
    }

    // 服务器基础信息
    public function server()
    {
        $this->assign('server', get_server_info());
        $this->display('system/server.html');
    }
}

