<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年3月13日
 *  默认主页
 */
namespace app\admin\controller\content;

use core\basic\Controller;
use app\admin\model\IndexModel;

class CommonController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new IndexModel();

        session('ucode', $login->ucode);
        $ucode=session('ucode');

        $menus = $this->model->getUserMenu($ucode); // 用户菜单
        $menus = get_tree($menus, 0, 'mcode', 'pcode');
        session('menu_tree', $menus);

        

    }

   
}