<?php
/**
 * @author CMS88.COM
 *  阿里云SMS
 */
namespace core\sms;

use core\basic\Config;

class AliyunSms {
    // 保存错误信息
    public $error;
    // Access Key ID
    private $accessKeyId = '';
    // Access Access Key Secret
    private $accessKeySecret = '';
    // 签名
    private $signName = '';
    // 模版ID
    private $templateCode = '';
    public function __construct() {
        // 配置参数
        $this->accessKeyId = Config::get('sms_appid');
        $this->accessKeySecret = Config::get('sms_secret');
        $this->signName = Config::get('sms_signame');
        $this->templateCode = Config::get('sms_checkcodetpl');
    }
    private function percentEncode($string) {
        $string = urlencode ( $string );
        $string = preg_replace ( '/\+/', '%20', $string );
        $string = preg_replace ( '/\*/', '%2A', $string );
        $string = preg_replace ( '/%7E/', '~', $string );
        return $string;
    }
    /**
     * 签名
     * @param unknown $parameters            
     * @param unknown $accessKeySecret            
     * @return string
     */
    private function computeSignature($parameters, $accessKeySecret) {
        ksort( $parameters );
        $canonicalizedQueryString = '';
        foreach ( $parameters as $key => $value ) {
            $canonicalizedQueryString .= '&' . $this->percentEncode ( $key ) . '=' . $this->percentEncode ( $value );
        }
        $stringToSign = 'GET&%2F&' . $this->percentencode ( substr ( $canonicalizedQueryString, 1 ) );
        $signature = base64_encode ( hash_hmac ( 'sha1', $stringToSign, $accessKeySecret . '&', true ) );
        return $signature;
    }
    /**
     * @param unknown $mobile            
     * @param unknown $verify_code            
     *
     */
    public function send_verify($mobile, $code) {
        $params = array (
            //此处作了修改
            'SignName' => $this->signName,
            'Format' => 'JSON',
            'Version' => '2017-05-25',
            'AccessKeyId' => $this->accessKeyId,
            'SignatureVersion' => '1.0',
            'SignatureMethod' => 'HMAC-SHA1',
            'SignatureNonce' => uniqid (),
            'Timestamp' => gmdate ( 'Y-m-d\TH:i:s\Z' ),
            'Action' => 'SendSms',
            'TemplateCode' => $this->templateCode,
            'PhoneNumbers' => $mobile,
            'TemplateParam' => '{"code":"' . $code . '"}' 
           // 'TemplateParam' => $tpljson   //更换为自己的实际模版
        );
        //var_dump($params);die;
        // 计算签名并把签名结果加入请求参数
        $params ['Signature'] = $this->computeSignature( $params, $this->accessKeySecret );
        // 发送请求（此处作了修改）
        //$url = 'https://sms.aliyuncs.com/?' . http_build_query ( $params );
        $url = 'http://dysmsapi.aliyuncs.com/?' . http_build_query ( $params );
        $result = json_decode( get_url($url) );
        if( isset($result->Code) && $result->Code=='OK' ) {
            return ['code'=>1,'msg'=>$result->Message];
        }else{
            return ['code'=>0,'msg'=>$result->Message];
        }
    }

}