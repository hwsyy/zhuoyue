<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2019年05月27日
 *  微信网页授权
 */
namespace core\weixin;

use core\basic\Config;

class WeiSendAuto
{
//--------------------------------------------------------LOGIN START
private $_apis = [
"host"     =  "https://mp.weixin.qq.com",
"login"     =  "https://mp.weixin.qq.com/cgi-bin/bizlogin?action=startlogin",
"qrcode"    =  "https://mp.weixin.qq.com/cgi-bin/loginqrcode?action=getqrcode¶m=4300",
"loginqrcode"  =  "https://mp.weixin.qq.com/cgi-bin/loginqrcode?action=ask&token=&lang=zh_CN&f=json&ajax=1",
"loginask"   =  "https://mp.weixin.qq.com/cgi-bin/loginqrcode?action=ask&token=&lang=zh_CN&f=json&ajax=1&random=",
"loginauth"   =  "https://mp.weixin.qq.com/cgi-bin/loginauth?action=ask&token=&lang=zh_CN&f=json&ajax=1",
"bizlogin"   =  "https://mp.weixin.qq.com/cgi-bin/bizlogin?action=login&lang=zh_CN"
];
private $_redirect_url = "";
private $_key      = "";
private function _getCookieFile(){
return WEI_UPLOAD_PATH."cookie_{$this- _key}.text";
}
private function _getSavePath(){
return WEI_UPLOAD_PATH.$this- _qrcodeName();
}
private function _qrcodeName(){
return "qrcode_{$this- _key}.png";
}
private function _log($msg){
Log::record("[微信调度:".date("Y-m-d H:i:s")."] ======: {$msg}");
}
public function getToken(){
return Utils::getCache("token_{$this- _key}");
}
public function setToken($token){
Utils::setCache("token_{$this- _key}",$token);
}
public function init($options){
if(!isset($options["key"])){
die("Key is Null!");
}
$this- _key   =  $options["key"];
if($this- getToken()){
echo("HAS Token !");
return;
}else{
//尼玛，先要获取首页!!!
$this- fetch("https://mp.weixin.qq.com/","","text");
$this- _log("start login!!");
$this- start_login($options);
}
}
private function start_login($options){
$_res    = $this- _login($options["account"],$options["password"]);
if(!$_res["status"]){
$this- _log($_res["info"]);
return;
}
//保存二维码
$this- _saveQRcode();
$_ask_api    =  $this- _apis["loginask"];
$_input["refer"] =  $this- _redirect_url;
$_index     =  1;
while(true){
/*      if($_index 60){
break;
}*/
$_res    =  $this- fetch($_ask_api.$this- getWxRandomNum(),$_input);
$_status   =  $_res["status"];
if($_status==1){
if($_res["user_category"]==1){
$_ask_api = $this- _apis["loginauth"];
}else{
$this- _log("Login success");
break;
}
}else if($_status==4){
$this- _log("已经扫码");
}else if($_status==2){
$this- _log("管理员拒绝");
break;
}else if($_status==3){
$this- _log("登录超时");
break;
}else{
if($_ask_api==$this- _apis["loginask"]){
$this- _log("请打开test.jpg，用微信扫码");
}else{
$this- _log("等待确认");
}
}
sleep(2);
$_index++;
}
/*if($_index =60){
$this- _log("U亲，超时了");
return;
}*/
$this- _log("开始验证");
$_input["post"]   = ["lang"= "zh_CN","f"= "json","ajax"= 1,"random"= $this- getWxRandomNum(),"token"= ""];
$_input["refer"]   = $this- _redirect_url;
$_res        = $this- fetch($this- _apis["bizlogin"],$_input);
$this- _log(print_r($_res,true));
if($_res["base_resp"]["ret"]!=0){
$this- _log("error = ".$_res["base_resp"]["err_msg"]);
return ;
}
$redirect_url    =  $_res["redirect_url"];//跳转路径
if(preg_match('/token=([\d]+)/i', $redirect_url,$match)){//获取cookie
$this- setToken($match[1]);
}
$this- _log("验证成功,token: ".$this- getToken());
}
//下载二维码
private function _saveQRcode(){
$_input["refer"] = $this- _redirect_url;
$_res    = $this- fetch($this- _apis["qrcode"],$_input,"text");
$fp     = fopen($this- _getSavePath(), "wb+") or die("open fails");
fwrite($fp,$_res) or die("fwrite fails");
fclose($fp);
}
private function _login($_username,$_password){
$_input["post"] = array(
'username'  =  $_username,
'pwd'    =  md5($_password),
'f'     =  'json',
'imgcode'  =  ""
);
$_input["refer"] = "https://mp.weixin.qq.com";
$_res      = $this- fetch($this- _apis["login"],$_input);
if($_res["base_resp"]["ret"]!==0){
return Utils::error($_res["base_resp"]["err_msg"]);
}
$this- _redirect_url  =  "https://mp.weixin.qq.com".$_res["redirect_url"];//跳转路径
return Utils::success("ok");
}
function getWxRandomNum(){
return "0.".mt_rand(1000000000000000,9999999999999999);
}
/**
* @param $url
* @param null $_input
* @param string $data_type
* @return mixed
* $_input= ["post"= [],"refer"= "",cookiefile='']
*/
function fetch( $url, $_input=null, $data_type='json') {
$ch = curl_init();
$useragent = isset($_input['useragent']) ? $_input['useragent'] : 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2';
//curl_setopt( $ch, CURLOPT_HTTPHEADER, $this- _headers); //设置HTTP头字段的数组
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt( $ch, CURLOPT_POST, isset($_input['post']) );
if( isset($_input['post']) )     curl_setopt( $ch, CURLOPT_POSTFIELDS, $_input['post'] );
if( isset($_input['refer']) )    curl_setopt( $ch, CURLOPT_REFERER, $_input['refer'] );
curl_setopt( $ch, CURLOPT_USERAGENT, $useragent );
curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, ( isset($_input['timeout']) ? $_input['timeout'] : 5 ) );
curl_setopt( $ch, CURLOPT_COOKIEJAR, ( isset($_input['cookiefile']) ? $_input['cookiefile'] : $this- _getCookieFile() ));
curl_setopt( $ch, CURLOPT_COOKIEFILE, ( isset($_input['cookiefile']) ? $_input['cookiefile'] : $this- _getCookieFile() ));
$result = curl_exec( $ch );
curl_close( $ch );
if ($data_type == 'json') {
$result = json_decode($result,true);
}
return $result;
}
//--------------------------------------------------------LOGIN END
}
