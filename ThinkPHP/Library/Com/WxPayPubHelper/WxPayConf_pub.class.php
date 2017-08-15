<?php
/**
* 	配置账号信息
*/
namespace Com\WxPayPubHelper;

class WxPayConf_pub
{
//	//=======【基本信息设置】=====================================
//	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
//	const APPID = 'wxefd54d4bbcc42db5';
//	//受理商ID，身份标识
//	const MCHID = '1236990102';
//	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
//	const KEY = '19881988198819881988198819881988';
//	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
//	const APPSECRET = '0cf4f04f27a35b24c6f86cdbee887390';
//	
//	//=======【JSAPI路径设置】===================================
//	//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
//	const JS_API_CALL_URL = 'http://mdj.sanliangbaby.com/demo/js_api_call.php';
//	
//	//=======【证书路径设置】=====================================
//	//证书路径,注意应该填写绝对路径
//	const SSLCERT_PATH = './cacert/apiclient_cert.pem';
//	const SSLKEY_PATH = './cacert/apiclient_key.pem';
//	
//	//=======【异步通知url设置】===================================
//	//异步通知url，商户根据实际开发过程设定
//	const NOTIFY_URL = 'http://mdj.sanliangbaby.com/index.php/M/Order/wxpay_notify.html';
//
//	//=======【curl超时设置】===================================
//	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
//	const CURL_TIMEOUT = 30;
        
        //=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	const APPID = 'wxa86d169e179be730';
	//受理商ID，身份标识
	const MCHID = '1275156401';
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	const KEY = 'zhongcetianxiazhongcetianxia2015';
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	const APPSECRET = 'dd31ab2beac8a261008bb6b9a1faca63';
	
	//=======【JSAPI路径设置】===================================
	//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
	const JS_API_CALL_URL = 'http://m.1mdj.com/demo/js_api_call.php';
	
	//=======【证书路径设置】=====================================
	//证书路径,注意应该填写绝对路径
	const SSLCERT_PATH = './cacert/apiclient_cert.pem';
	const SSLKEY_PATH = './cacert/apiclient_key.pem';
	
	//=======【异步通知url设置】===================================
	//异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = 'http://www.zhongcetianxia.com/index.php/M/Founding/wxpay_notify.html';
        const NOTIFY_URL_APP = 'http://www.zhongcetianxia.com/index.php/M/Founding/wx_app_pay_notify.html';

	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
}
	
?>