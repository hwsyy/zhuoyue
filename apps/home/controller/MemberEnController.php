<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2020年06月26日
 *  会员前台控制器
 */
namespace app\home\controller;

use core\basic\Controller;
use app\home\model\MemberModel;
use core\basic\Url;
use core\sms\AliyunSms;

class MemberController extends Controller
{

    protected $parser;

    protected $model;

    protected $htmldir;

    public function __construct()
    {
        $this->model = new MemberModel();
        $this->modelsms=new AliyunSms();
        $this->parser = new ParserController();
        $this->htmldir = $this->config('tpl_html_dir') ? $this->config('tpl_html_dir') . '/' : '';
    }

    // 会员登录页面
    public function login()
    {
        // 已经登录时跳转到用户中心
        if (session('pboot_uid')) {
            location(Url::home('member/ucenter'));
            
        }
        
        // 执行登录验证
        if ($_POST) {
            if ($this->config('login_status') === '0') {
                error('The system has closed the login function. Please open it in the background and try again！');
            }
            
            // 验证码验证
           /* $checkcode = strtolower(post('checkcode', 'var'));
            if ($this->config('login_check_code') !== '0') {
                if (! $checkcode) {
                    alert_back('验证码不能为空！');
                }
                
                if ($checkcode != session('checkcode')) {
                    alert_back('验证码错误！');
                }
            }*/
            
            $username = post('useremail');
            $password = post('password');
            $yuanpassword=$password;
            $isremember=post('isremember');
            if (! $username) {
                alert_back('The user account cannot be empty！');
            }

            
            
            // 检查用户名
            if (! $this->model->checkUsername("username='$username' or useremail='$username' or usermobile='$username'")) {
                alert_back('The user account cannot be empty！');
            }
            
            // 检查密码
            if (! $password) {
                alert_back('The user password cannot be empty！');
            } else {
                $password = md5(md5($password));
            }
            
            // 登录验证
            if (! ! $login = $this->model->login("(username='$username' or useremail='$username' or usermobile='$username') AND password='$password'")) {
                if (! $login->status) {
                    alert_back('Your account needs to be reviewed, please contact the administrator！');
                }
                
                if($isremember){
                    cookie('password',$yuanpassword,7*24*60*60);
                    cookie('username',$username,7*24*60*60);
                }
                session('pboot_uid', $login->id);
                session('pboot_ucode', $login->ucode);
                session('pboot_username', $login->username);

                session('pboot_useremail', $login->seremail);
                session('pboot_usermobile', $login->usermobile);
                session('pboot_gid', $login->gid);
                session('pboot_gcode', $login->gcode);
                session('pboot_gname', $login->gname);
                
                if (! ! $backurl = get('backurl')) {
                    alert_location('Login successful！', $backurl, 1);
                } else {
                    alert_location('Login successful！', "/", 1);
                }
            } else {
                alert_back('The account password is wrong, please check and try again！', 0);
            }
        } else {
            $content = parent::parser($this->htmldir . 'member/login.html'); // 框架标签解析
            $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
            $content = str_replace('{pboot:pagetitle}', $this->config('login_title') ?: 'Member login-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $content = $this->parser->parserPositionLabel($content, 0, 'Member login', Url::home('member/login')); // CMS当前位置标签解析
            $content = $this->parser->parserSpecialPageSortLabel($content, - 2, 'Member login', Url::home('member/login')); // 解析分类标签
            $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
            echo $content;
            exit();
        }
    }

    // 会员注册页面
    public function register()
    {
        // 已经登录时跳转到用户中心
        if (session('pboot_uid')) {
            location(Url::home('member/ucenter'));
        }
        
        // 执行注册
        if ($_POST) {
            if ($this->config('register_status') === '0') {
                error('The system has closed the registration function, please go to the background to open and try again！');
            }
            
            if (time() - session('lastreg') < 10) {
                alert_back('You are registering too often, please try again later！');
            }
            
            // 验证码验证
            /*$checkcode = strtolower(post('checkcode', 'var'));
            if ($this->config('register_check_code') !== '0') {
                if (! $checkcode) {
                    alert_back('验证码不能为空！');
                }
                
                if ($checkcode != session('checkcode')) {
                    alert_back('验证码错误！');
                }
            }*/
            
            $ucode = get_auto_code($this->model->getLastUcode(), 1);
            $username = post('useremail'); // 接受用户名、邮箱、手机三种方式
            $emailcode=post('emailcode');
            $password = post('password');
            $repassword = post('repassword');
            $code=post('code');
            $name=post('name');
            $phone=post('phone');
            $zhiwu=post('zhiwu');
            $company=post('company');
            $companyphone=post('companyphone');
            if($emailcode!=session('checkcode')){
               alert_back('Verification code error！'); 
            }
            
            // 注册类型判断
            if ($this->config('register_type') == 2) { // 邮箱注册
                $useremail = $username;
                if (! $useremail) {
                    alert_back('The account cannot be empty, please enter the registered email account！');
                }
                if (! preg_match('/^[\w]+@[\w\.]+\.[a-zA-Z]+$/', $useremail)) {
                    alert_back('The account format is not correct, please input the correct email account！');
                }
                if ($this->model->checkUsername("useremail='$useremail' OR username='$useremail'")) {
                    alert_back('The email address you entered has been registered！');
                }
            } elseif ($this->config('register_type') == 3) { // 手机注册
                $usermobile = $username;
                if (! $usermobile) {
                    alert_back('The account cannot be empty, please enter the registered mobile phone number！');
                }
                if (! preg_match('/^1[0-9]{10}$/', $usermobile)) {
                    alert_back('The account format is not correct, please input the correct mobile phone number！');
                }
                if ($this->model->checkUsername("usermobile='$usermobile' OR username='$usermobile'")) {
                    alert_back('The mobile phone number you entered has been registered！');
                }
            } else { // 账号注册
                if (! $username) {
                    alert_back('The user name cannot be empty！');
                }
                if (! preg_match('/^[\w\@\.]+$/', $username)) {
                    alert_back('The user account contains a special character that is not allowed！');
                }
                // 检查用户名
                if ($this->model->checkUsername("username='$username' OR useremail='$username' OR usermobile='$username'")) {
                    alert_back('The account you entered has been registered！');
                }
            }
            
            if ($password != $repassword) {
                alert_back('Verify that the password is incorrect！');
            }
            
            if (! $password) {
                alert_back('The password cannot be empty！');
            } else {
                $password = md5(md5($password));
            }
            
            // 默认值设置
            $status = $this->config('register_verify') ? 0 : 1; // 默认不需要审核
            $score = $this->config('register_score') ?: 0;
            
            $group = $this->model->getFirstGroup();
            $gid = $this->model->getGroupID($this->config('register_gcode')) ?: $group->id;
            
            // 构建数据
            $data = array(
                'ucode' => $ucode,
                'username' => $username,
                'name' => $name,
                'phone'=>$phone,
                'zhiwu'=>$zhiwu,
                'company'=>$company,
                'companyphone'=>$companyphone,
                'usermobile' => $usermobile,
                'nickname' => $nickname,
                'password' => $password,
                'headpic' => '',
                'status' => $status,
                'gid' => $gid,
                'wxid' => '',
                'qqid' => '',
                'wbid' => '',
                'activation' => 1,
                'score' => $score,
                'register_time' => get_datetime(),
                'login_count' => 0,
                'last_login_ip' => 0,
                'last_login_time' => 0
            );
            
            // 读取字段
           /* if (! ! $field = $this->model->getField()) {
                foreach ($field as $value) {
                    $field_data = post($value->name);
                    if (is_array($field_data)) { // 如果是多选等情况时转换
                        $field_data = implode(',', $field_data);
                    }
                    $field_data = preg_replace_r('pboot:if', '', $field_data);
                    if ($value->required && ! $field_data) {
                        alert_back($value->description . '不能为空！');
                    } else {
                        $data[$value->name] = $field_data;
                    }
                }
            }*/
            
            // 执行注册
            if ($insertId=$this->model->register($data)) {
             //   session('pboot_uid', $insertId);
                session('lastreg', time()); // 记录最后提交时间
                if ($status) {
                    alert_location('Registered successfully！', Url::home('member/login'), 1);
                } else {
                    alert_location('Registration successful, please wait for administrator review！', Url::home('member/login'), 1);
                }
            } else {
                error('Member registration failed！', - 1);
            }
        } else {
            $content = parent::parser($this->htmldir . 'member/register.html'); // 框架标签解析
            $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
            $content = str_replace('{pboot:pagetitle}', $this->config('register_title') ?: 'Registered members-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $content = $this->parser->parserPositionLabel($content, 0, 'Registered members', Url::home('member/register')); // CMS当前位置标签解析
            $content = $this->parser->parserSpecialPageSortLabel($content, - 3, 'Registered members', Url::home('member/register')); // 解析分类标签
            $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
            echo $content;
            exit();
        }
    }


     public function isRegister(){
        if ($_POST) {
            $email = post('useremail');
          
            if (time() - session('lastsend') < 60) {
                json(0, 'You are submitting too frequently, please try again later！');
            }

            if(!$email){
                alert_back('Mailboxes cannot be empty！');
            }
            // 检查用户名
            if ($this->model->checkUsername("username='$email' or useremail='$email'")) {
                alert_back('Your email address has been registered！');
            }

           

            

        }
    }


    // 用户中心
    public function ucenter()
    {
        // 未登录时跳转到用户登录
        if (! session('pboot_uid')) {
            location(Url::home('member/login'));
        }
        // location("/");
        // exit;
        $content = parent::parser($this->htmldir . 'member/ucenter.html'); // 框架标签解析
        $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
        $content = str_replace('{pboot:pagetitle}', $this->config('ucenter_title') ?: 'Personal center-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
        $content = $this->parser->parserPositionLabel($content, 0, 'Personal center', Url::home('member/ucenter')); // CMS当前位置标签解析
        $content = $this->parser->parserSpecialPageSortLabel($content, - 4, 'Personal center', Url::home('member/ucenter')); // 解析分类标签
        $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
        echo $content;
        exit();
    }

    // 用户修改
    public function umodify()
    {
        // 未登录时跳转到用户登录
        if (! session('pboot_uid')) {
            location(Url::home('member/login'));
        }
        
        // 执行资料修改
        if ($_POST && session('pboot_uid')) {
            $nickname = post('nickname');
            $useremail = post('useremail');
            $usermobile = post('usermobile');
            $password = post('password');
            $rpassword = post('rpassword');
            $headpic = str_replace(SITE_DIR, '', post('headpic'));
            
            if ($useremail) { // 邮箱校验
                if (! preg_match('/^[\w]+@[\w\.]+\.[a-zA-Z]+$/', $useremail)) {
                    alert_back('The email format is not correct, please input the correct email account！');
                }
                if ($this->model->checkUsername("(useremail='$useremail' OR username='$useremail') AND id<>'" . session('pboot_uid') . "'")) {
                    alert_back('The email address you entered has been registered！');
                }
            }
            
            if ($usermobile) { // 手机检验
                if (! preg_match('/^1[0-9]{10}$/', $usermobile)) {
                    alert_back('The mobile phone format is not correct, please input the correct mobile phone number！');
                }
                if ($this->model->checkUsername("(usermobile='$usermobile' OR username='$usermobile') AND id<>'" . session('pboot_uid') . "'")) {
                    alert_back('The mobile phone number you entered has been registered！');
                }
            }
            
            // 构建数据
            $data = array(
                'nickname' => $nickname,
                'useremail' => $useremail,
                'usermobile' => $usermobile,
                'headpic' => $headpic
            );
            
            // 密码修改
            if ($password) {
                if ($password != $rpassword) {
                    alert_back('Verify that the password is incorrect！');
                } else {
                    $data['password'] = md5(md5($password));
                }
            }
            
            // 读取字段
            if (! ! $field = $this->model->getField()) {
                foreach ($field as $value) {
                    $field_data = post($value->name);
                    if (is_array($field_data)) { // 如果是多选等情况时转换
                        $field_data = implode(',', $field_data);
                    }
                    $field_data = preg_replace_r('pboot:if', '', $field_data);
                    if ($value->required && ! $field_data) {
                        alert_back($value->description . "Can't be empty！");
                    } else {
                        $data[$value->name] = $field_data;
                    }
                }
            }
            
            // 不允许修改的字段
            unset($data['id']);
            unset($data['ucode']);
            unset($data['username']);
            unset($data['status']);
            unset($data['gid']);
            unset($data['wxid']);
            unset($data['qqid']);
            unset($data['wbid']);
            unset($data['score']);
            unset($data['register_time']);
            unset($data['login_count']);
            unset($data['last_login_ip']);
            unset($data['last_login_time']);
            
            // 执行修改
            if ($this->model->modUser($data)) {
                alert_location('修改成功！', Url::home('member/umodify'), 1);
            } else {
                error('资料修改失败！', - 1);
            }
        } else {
            $content = parent::parser($this->htmldir . 'member/umodify.html'); // 框架标签解析
            $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
            $content = str_replace('{pboot:pagetitle}', $this->config('umodify_title') ?: 'Data modification-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $content = $this->parser->parserPositionLabel($content, 0, 'Data modification', Url::home('member/umodify')); // CMS当前位置标签解析
            $content = $this->parser->parserSpecialPageSortLabel($content, - 5, 'Data modification', Url::home('member/umodify')); // 解析分类标签
            $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
            echo $content;
            exit();
        }
    }


    public function forgetpwd(){
        if ($_POST) {
            $email = post('useremail');
            $emailcode=post('emailcode');
            if (time() - session('lastsend') < 60) {
                json(0, 'You are submitting too frequently, please try again later！');
            }

            if(!$email){
                alert_back('Mailboxes cannot be empty！');
            }
            // 检查用户名
            if (! $this->model->checkUsername("username='$email' or useremail='$email'")) {
                alert_back('Your email address is not registered！');
            }

            if($emailcode!=session('checkcode')){
                alert_back('Your verification code is incorrect');
            }else{
                session('useremail',$email);
                alert_location('Authentication is successful！', Url::home('member/resetpwd'), 1);
            }

            

        }else{
            $content = parent::parser($this->htmldir . 'member/forgetpwd.html'); // 框架标签解析
            $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
            $content = str_replace('{pboot:pagetitle}', $this->config('index_title') ?: 'Forgot password-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $content = $this->parser->parserPositionLabel($content, - 1, 'Home', SITE_INDEX_DIR . '/'); // CMS当前位置标签解析
            $content = $this->parser->parserSpecialPageSortLabel($content, 0, '', SITE_INDEX_DIR . '/'); // 解析分类标签
            $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
            echo $content;
            exit();
        }
    }

    // 用户修改
    public function resetpwd()
    {
        // 未登录时跳转到用户登录
       /* if (! session('pboot_uid')) {
            location(Url::home('member/login'));
        }
        */
        // 执行资料修改
        if ($_POST) {
            

            $password=post('password');
            $repassword=post('repassword');
            if($password!=$repassword){
                alert_back('The password is different！');
            }else {
                $password = md5(md5($password));
            }
            $useremail=session('useremail');
            $data['password']=$password;
            // 执行修改
            if ($this->model->modPassword($useremail,$data)) {
                alert_location('Modify the success！', Url::home('member/login'), 1);
            } else {
                error('Password reset failed！', - 1);
            }
        } else {
            $content = parent::parser($this->htmldir . 'member/resetpwd.html'); // 框架标签解析
            $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
            $content = str_replace('{pboot:pagetitle}', $this->config('umodify_title') ?: '资料修改-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $content = $this->parser->parserPositionLabel($content, 0, '资料修改', Url::home('member/umodify')); // CMS当前位置标签解析
            $content = $this->parser->parserSpecialPageSortLabel($content, - 5, '资料修改', Url::home('member/umodify')); // 解析分类标签
            $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
            echo $content;
            exit();
        }
    }

    // 退出登录
    public function logout()
    {
        session('pboot_uid', '');
        session('pboot_ucode', '');
        session('pboot_username', '');
        session('pboot_useremail', '');
        session('pboot_usermobile', '');
        session('pboot_gid', '');
        session('pboot_gcode', '');
        session('pboot_gname', '');
        location(Url::home('member/login'));
    }

    // 文件上传方法(Ajax)
    public function upload()
    {
        // 必须登录
        if (! session('pboot_uid')) {
            json(0, '请先登录！');
        }
        
        $ext = $this->config('home_upload_ext') ?: "jpg,jpeg,png,gif,xls,xlsx,doc,docx,ppt,pptx,rar,zip,pdf,txt";
        $upload = upload('upload', $ext);
        if (is_array($upload)) {
            json(1, $upload);
        } else {
            json(0, $upload);
        }
    }

    // 发送邮件
    public function sendEmail()
    {
        if ($this->config('register_check_code') != 2) {
            json(0, 'Send failed, background configuration non-mailbox verification code mode！');
        }
        
        if (time() - session('lastsend') < 10) {
            json(0, 'You are submitting too frequently, please try again later！');
        }
        
       /* if (! session('sendemail')) {
            json(0, '非法提交发送邮件！');
        }*/
        
        // 发送邮箱参数
        if (! ! $to = post('useremail')) {
            if (! preg_match('/^[\w]+@[\w]+\.[a-zA-Z]+$/', $to)) {
                json(0, 'The email format is not correct, please input the correct email account！');
            }
        } else {
            json(0, 'Send failed. Missing send object parameter to！');
        }
        
        $rs = false;
        if ($to) {
            session('lastsend', time()); // 记录最后提交时间
            $mail_subject = "You have the new verification code information, please note that check！";
            $code = create_code(6);
            session('checkcode', strtolower($code));
            $mail_body = "Your verification code is：" . $code;
            $mail_body .= '<br>From the website ' . get_http_url() . ' （' . date('Y-m-d H:i:s') . '）';
            $rs = sendmail($this->config(), $to, $mail_subject, $mail_body);
        }
        if ($rs === true) {
            json(1, 'Send a success！');
        } else {
            json(0, 'Send failure，' . $rs);
        }
    }


    //发送短信验证码
     public function sms(){

        if (time() - session('lastsend') < 10) {
            json(0, 'You are submitting too frequently, please try again later！');
        }
        $phone = post('phone');
        
        if(!$phone){alert_back('Please enter your mobile phone number！');}
        if (! preg_match('/^1[0-9]{10}$/', $phone)) {
            alert_back('The mobile phone format is not correct, please input the correct mobile phone number！');
        }
        
        $code = create_code_number(6); 
        $result=$this->modelsms->send_verify($phone, $code);
      //  var_dump($result);
      //  exit;
         
            if($result['code']==1){
                session('lastsend', time());
                session('smscode'.$phone, $code);
                json(1,'Send a success');
            }else{json(0,$result['msg']);}
         

    }

    public function _empty()
    {
        _404('The address you visited does not exist, please check it and try again！');
    }
}