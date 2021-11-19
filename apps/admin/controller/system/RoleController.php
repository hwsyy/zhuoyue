<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年4月3日
 *  角色控制器
 */
namespace app\admin\controller\system;

use core\basic\Controller;
use app\admin\model\system\RoleModel;

class RoleController extends Controller{

    private $blank;

    private $model;

    public function __construct()
    {
        $this->model = new RoleModel();
    }

    // 角色列表
    public function index()
    {
        $this->assign('list', true);
        $this->assign('roles', $this->model->getList());
        
        // 数据区域选择
        $area_model = model('admin.system.Area');
        $area_tree = $area_model->getSelect();
        $area_checkbox = $this->makeAreaCheckbox($area_tree);
        $this->assign('area_checkbox', $area_checkbox);
        
        // 菜单权限表
        $menu_model = model('admin.system.Menu');
        $menu_level = $menu_model->getMenuLevel();
    //   var_dump($menu_level);
        $menus = $menu_model->getSelect();
     //   var_dump($menus);
        $menu_list = $this->makeLevelList($menus, $menu_level);
   //     var_dump($menu_list);
        $this->assign('menu_list', $menu_list);
        
        $this->display('system/role.html');
    }

    // 角色增加
    public function add()
    {
        if ($_POST) {
            // 获取数据
            $rcode = get_auto_code($this->model->getLastCode()); // 自动编码
            $name = post('name');
            $description = post('description');
            $acodes = post('acodes', 'array', false, '角色数据区域', array()); // 区域
            $levels = post('levels', 'array', false, '角色权限', array()); // 权限
            
            if (! $rcode) {
                alert_back('编码不能为空！');
            }
            
            if (! $name) {
                alert_back('角色名不能为空！');
            }
            
            // 检查编码
            if ($this->model->checkRole("rcode='$rcode'")) {
                alert_back('该角色编号已经存在，不能再使用！');
            }
            
            // 构建数据
            $data = array(
                'rcode' => $rcode,
                'name' => $name,
                'description' => $description,
                'create_user' => session('username'),
                'update_user' => session('username')
            );
            
            // 执行添加
            if ($this->model->addRole($data, $acodes, $levels)) {
                $this->log('修改角色' . $rcode . '成功！');
                if (! ! $backurl = get('backurl')) {
                    success('新增成功！', base64_decode($backurl));
                } else {
                    success('新增成功！', url('admin/Role/index'));
                }
            } else {
                $this->log('修改角色' . $rcode . '失败！');
                error('新增失败！', - 1);
            }
        } else {
            $this->assign('add', true);
            
            // 数据区域选择
            $area_model = model('admin.system.Area');
            $area_tree = $area_model->getSelect();
            $area_checkbox = $this->makeAreaCheckbox($area_tree);
            $this->assign('area_checkbox', $area_checkbox);
            
            // 菜单权限表
            $menu_model = model('admin.system.Menu');
            $menu_level = $menu_model->getMenuLevel();
            $menus = $menu_model->getSelect();
            $menu_list = $this->makeLevelList($menus, $menu_level);
            $this->assign('menu_list', $menu_list);
            
            $this->display('system/role.html');
        }
    }

    // 生成区域选择，无限制
    private function makeAreaCheckbox($tree, $checkeds = array())
    {
        $list_html = '';
        foreach ($tree as $values) {
            if (in_array($values->acode, $checkeds)) {
                $checked = 'checked="checked"';
            } else {
                $checked = '';
            }
            if (! $values->son) { // 没有子类才显示选择框
                $list_html .= "<input type='checkbox' $checked name='acodes[]' value='{$values->acode}' title='{$values->acode}-{$values->name}'>";
            } else {
                $list_html .= $this->makeAreaCheckbox($values->son, $checkeds);
            }
        }
        return $list_html;
    }

    // 生成无限级菜单权限列表
    private function makeLevelList($menus, $menu_level, $checkeds = array())
    {
        $menu_html = '';
       // var_dump($menus);
        foreach ($menus as $value) {
            $string = '';
            // 根据是否有子栏目生成图标
            if ($value->son) {
                $ico = "<i class='fa fa-folder-open-o' aria-hidden='true'></i>";
            } else {
                $ico = "<i class='fa fa-folder-o' aria-hidden='true'></i>";
            }
            
            // 选中状态
            if (in_array($value->url, $checkeds)) {
                $checked = 'checked="checked"';
            } else {
                $checked = '';
            }
           // var_dump($value->url);
            // 获取模块及控制器路径
            if ($value->url) {
                $pre_url = substr($value->url, 0, get_strpos($value->url, '/', 3) + 1);
                $mcode_arr=explode('/',$value->url);
                $mcode_arr_new=array($mcode_arr[4],$mcode_arr[5]);
                $mcode_url=implode('/',$mcode_arr_new);
            //    var_dump($mcode_url);
            } else {
                error('"' . $value->name . '"菜单地址为空，请核对！');
            }
            
            $string = "<input type='checkbox' $checked class='checkbox' lay-skin='primary'  name='levels[]' value='" . $value->url . "' title='浏览'>";
           
            $mcode = $value->mcode;
         //   var_dump($value);
            if (array_key_exists($mcode, $menu_level)) {
                foreach ($menu_level[$mcode] as $key2 => $value2) {
                    if(strpos($pre_url,'/Single/') || strpos($pre_url,'/Content/') || strpos($pre_url,'/ContentList/')){
                        $url = $pre_url . $value2->value.'/'.$mcode_url;
                    }else{
                        $url = $pre_url . $value2->value;
                    }
                    
                    if (in_array($url, $checkeds)) {
                        $checked = 'checked="checked"';
                    } else {
                        $checked = '';
                    }
                    
                    $string .= "<input type='checkbox' $checked class='checkbox'lay-skin='primary' name='levels[]' value='$url' title='{$value2->item}'>";
                }
            }
            
            // 生成菜单html
          if ($value->mcode == 'M130') {
                if (in_array('/admin/Content/add', $checkeds)) {
                    $checked = 'checked="checked"';
                } else {
                    $checked = '';
                }
                $html = '<input type="checkbox" ' . $checked . ' class="checkbox" lay-skin="primary" name="levels[]" value="/admin/Content/add" title="列表内容新增">';
                if (in_array('/admin/Content/del', $checkeds)) {
                    $checked = 'checked="checked"';
                } else {
                    $checked = '';
                }
                $html .= '<input type="checkbox" ' . $checked . ' class="checkbox" lay-skin="primary" name="levels[]" value="/admin/Content/del" title="列表内容删除">';
                if (in_array('/admin/Content/mod', $checkeds)) {
                    $checked = 'checked="checked"';
                } else {
                    $checked = '';
                }
                $html .= '<input type="checkbox" ' . $checked . ' class="checkbox" lay-skin="primary" name="levels[]" value="/admin/Content/mod" title="列表内容修改">';
//                单页内容修改
                if (in_array('/admin/Single/mod', $checkeds)) {
                    $checked = 'checked="checked"';
                } else {
                    $checked = '';
                }
                $html .= '<input type="checkbox" ' . $checked . ' class="checkbox" lay-skin="primary" name="levels[]" value="/admin/Single/mod" title="单页内容修改">';
                $string .= $html;
                $menu_html .= "<div class='layui-row'><div class='layui-col-md3 layui-col-lg2' style='margin-top:10px;'>{$this->blank} $ico {$value->name}</div><div class='layui-col-md9'>$string</div></div>";
                // 子菜单处理
                $menu_html .= $this->my_content_html($menu_level, $checkeds);
            } else {
                $menu_html .= "<div class='layui-row'><div class='layui-col-md3 layui-col-lg2' style='margin-top:10px;'>{$this->blank} $ico {$value->name}</div><div class='layui-col-md9'>$string</div></div>";
                // 子菜单处理
                if ($value->son) {
                    $this->blank .= '　　';
                    $menu_html .= $this->makeLevelList($value->son, $menu_level, $checkeds);
                }
            }
        }

        // 循环完后回归缩进位置
        $this->blank = substr($this->blank, 0, - 6);
        return $menu_html;
    }

    /**
     * //        $html = '<div class="layui-row">
//                                <div class="layui-col-md3 layui-col-lg2" style="margin-top:10px;"><i class="fa fa-folder-open-o" aria-hidden="true"></i> 文章内容</div>
//                                <div class="layui-col-md9">
//                                    <input type="checkbox" checked="checked" class="checkbox" lay-skin="primary" name="levels[]" value="/admin/M130/index" title="浏览">
//                                    <div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin="primary"><span>浏览</span><i class="layui-icon layui-icon-ok"></i></div>
//                                </div>
//                            </div>';
     * @param array $menu_level  菜单mcode对应的子类菜单数组
     * @param array $checkeds    所有已经选择的权限
     * @return string 返回带有权限的html元素
     */
    function my_content_html($menu_level, $checkeds = array()) {
//        dump($menu_level);
//        dump($checkeds);
//        die;
        $m130_menu = $menu_level['M130'];

        $blank = '　　';//空白栏目
        $html = '';//最后面返还的html
        $models = model('admin.content.Model');
        $menu_models = $models->getModelMenu();//获得模型对应的菜单
//        dump($menu_models);
//        die;
        foreach ($menu_models as $menu_info) {
            $html .= '<div class="layui-row">';
            $html .= '<div class="layui-col-md3 layui-col-lg2" style="margin-top:10px;">' . $blank . '<i class="fa fa-folder-o" aria-hidden="true"></i>' . $menu_info->name . '内容</div>';
            $html .= '<div class="layui-col-md9">';
            if ($menu_info->type == 1) {
                $view_url = '/admin/Single/index/mcode/' . $menu_info->mcode;
            } else {
                $view_url = '/admin/Content/index/mcode/' . $menu_info->mcode;
            }
            if (in_array($view_url, $checkeds)) {
                $checked = 'checked="checked"';
            } else {
                $checked = '';
            }
            $html .= '<input type="checkbox" ' . $checked . ' class="checkbox" lay-skin="primary" name="levels[]" value="' . $view_url . '" title="浏览">';
            $html .= '<div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin="primary"><span>浏览</span><i class="layui-icon layui-icon-ok"></i></div>';
            if ($menu_info->type != 1) {//不是单页有子类
                $data = \core\basic\Db::table('ay_content_sort')->where("mcode = '{$menu_info->mcode}'")->select(1);
                if ($data) {
                    $html .= '</br>';
                    foreach ($data as $info) {
                        $info_url = '/admin/Content/' . $info['id'];//sugao_edit
                        if (in_array($info_url, $checkeds)) {
                            $checked = 'checked="checked"';
                        } else {
                            $checked = '';
                        }
                        $html .= '<input type="checkbox" ' . $checked . ' class="checkbox" lay-skin="primary" name="levels[]" value="' . $info_url . '" title="' . $info['name'] . '">';
                        $html .= '<div class="layui-unselect layui-form-checkbox" lay-skin="primary"><span>' . $info['name'] . '</span><i class="layui-icon layui-icon-ok"></i></div>';
                    }
                }
            }
            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }


    // 角色删除
    public function del()
    {
        if (! $rcode = get('rcode', 'var')) {
            error('传递的参数值错误！', - 1);
        }
        if ($this->model->delRole($rcode)) {
            $this->log('删除角色' . $rcode . '成功！');
            success('删除成功！', - 1);
        } else {
            $this->log('删除角色' . $rcode . '失败！');
            error('删除失败！', - 1);
        }
    }

    // 角色修改
    public function mod()
    {
        if (! $rcode = get('rcode', 'var')) {
            error('传递的参数值错误！', - 1);
        }
        
        // 修改操作
        if ($_POST) {
            // 获取数据
            $name = post('name');
            $description = post('description');
            $acodes = post('acodes', 'array', false, '角色数据区域', array()); // 区域
            $levels = post('levels', 'array', false, '角色权限', array()); // 权限
            
            if (! $name) {
                alert_back('角色名不能为空！');
            }
            
            // 构建数据
            $data = array(
                'name' => $name,
                'description' => $description,
                'update_user' => session('username')
            );
            
            // 执行修改
            if ($this->model->modRole($rcode, $data, $acodes, $levels)) {
                $this->log('修改角色' . $rcode . '成功！');
                if (! ! $backurl = get('backurl')) {
                    success('修改成功！', base64_decode($backurl));
                } else {
                    success('修改成功！', url('admin/Role/index'));
                }
            } else {
                location(- 1);
            }
        } else {
            $this->assign('mod', true);
            
            // 调取修改内容
            $result = $this->model->getRole($rcode);
            if (! $result) {
                error('编辑的内容已经不存在！', - 1);
            }
            $this->assign('role', $result);
            
            // 数据区域选择
            $area_model = model('admin.system.Area');
            $area_tree = $area_model->getSelect();
            $area_checkbox = $this->makeAreaCheckbox($area_tree, $result->acodes);
            $this->assign('area_checkbox', $area_checkbox);
            
            // 菜单权限表
            $menu_model = model('admin.system.Menu');
            $menu_level = $menu_model->getMenuLevel();
            $menus = $menu_model->getSelect();
            $menu_list = $this->makeLevelList($menus, $menu_level, $result->levels);
            $this->assign('menu_list', $menu_list);
            
            $this->display('system/role.html');
        }
    }
}