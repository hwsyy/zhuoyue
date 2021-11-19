<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年04月17日
 *  公司设置控制器 
 */
namespace app\admin\controller\content;

use core\basic\Controller;
use app\admin\model\content\CompanyModel;

use core\basic\Config;

class CompanyController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new CompanyModel();
        
    }

    // 显示公司设置
    public function index()
    {
        // 获取公司配置
        $company_description = explode(',',$this->config('company_description'));
      //  var_dump($company_description);
        $company_column = explode(',',$this->config('company_column'));
        $company_leixing = explode(',',$this->config('company_leixing'));
      //  var_dump($company_column);
        $company_config=array();
        foreach($company_description as $k=>$v){
          //  var_dump($v);
            $company_config[$k]->description=$v;
            $company_config[$k]->column=$company_column[$k];
            $company_config[$k]->leixing=$company_leixing[$k];
        }
      //  var_dump($company_config);
        $this->assign('company_config', $company_config);
        $this->assign('companys', $this->model->getList());
        $this->display('content/company.html');
    }

    // 修改公司设置
    public function mod()
    {
        if (! $_POST) {
            return;
        }
        $data = array(
            'name' => post('name'),
            'flogo'=>post('flogo'),
            'statistical'=>post('statistical'),
            'icp'=>post('icp'),
            'address' => post('address'),
            'postcode' => post('postcode'),
            'contact' => post('contact'),
            'mobile' => post('mobile'),
            'phone' => post('phone'),
            'fax' => post('fax'),
            'email' => post('email'),
            'qq' => post('qq'),
            'weixin' => post('weixin'),
            'weibo'=>post('weibo'),
            'blicense' => post('blicense'),
            'other' => post('other'),
            'content'=>post('content')
        );
        
        if ($this->model->checkCompany()) {
            if ($this->model->modCompany($data)) {
                $this->log('修改公司信息成功！');
                success('修改成功！', - 1);
            } else {
                location(- 1);
            }
        } else {
            $data['acode'] = session('acode');
            if ($this->model->addCompany($data)) {
                $this->log('修改公司信息成功！');
                success('修改成功！', - 1);
            } else {
                location(- 1);
            }
        }
    }
}