<?php
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidBroadcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidFilecast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidGroupcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidUnicast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidCustomizedcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidListcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSBroadcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSFilecast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSGroupcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSUnicast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSCustomizedcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSListcast.php');
class Sendmessage {
	protected $appkey           = NULL;
	protected $appMasterSecret     = NULL;
	protected $timestamp        = NULL;
	protected $validation_token = NULL;
	protected $device_token = NULL;
	protected $ticker = NULL;
	protected $title = NULL;
	protected $text = NULL;
	protected $type = NULL;
	protected $url = NULL;
	protected $custom = NULL;
	protected $activity = NULL;

	function __construct($key, $secret ,$device_token,$ticker,$title,$text,$type,$url,$custom,$activity) {
		$this->appkey = $key;
		$this->appMasterSecret = $secret;
		$this->device_token = $device_token;
		$this->ticker =$ticker;
		$this->title = $title;
		$this->text = $text;
		$this->type = $type;
		$this->url = $url;
		$this->custom = $custom;
		$this->activity = $activity;
		$this->timestamp = strval(time());
	}


	//向所有的安卓设备发送广播通知
	function sendAndroidBroadcast() {
		try {
			$brocast = new AndroidBroadcast();
			$brocast->setAppMasterSecret($this->appMasterSecret);
			$brocast->setPredefinedKeyValue("appkey",           $this->appkey);
			$brocast->setPredefinedKeyValue("timestamp",        $this->timestamp);

			//消息通知的提示文字
			$brocast->setPredefinedKeyValue("ticker",           $this->ticker);
			$unicast->setPredefinedKeyValue("device_tokens",           $this->device_token);
			//消息通知的标题
			$brocast->setPredefinedKeyValue("title",            $this->title);

			//消息通知的内容
			$brocast->setPredefinedKeyValue("text",             $this->text);

			//用户点击通知后做的操作  "go_app"打开应用  "go_url": 跳转到URL "go_activity": 打开特定的activity "go_custom": 用户自定义内容。
			$brocast->setPredefinedKeyValue("after_open",       $this->type);

			$brocast->setPredefinedKeyValue("url",       $this->url);
			$brocast->setPredefinedKeyValue("activity",       $this->activity);
			$brocast->setPredefinedKeyValue("custom",       $this->custom);

			//消息模式  true 正式模式    false 测试模式
			$brocast->setPredefinedKeyValue("production_mode", "true");
			// [optional]Set extra fields
			$brocast->setExtraField("test", "helloworld");
			//print("Sending broadcast notification, please wait...\r\n");
			$brocast->send();
			//print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}


	//向指定的用户发送单播消息
	function sendAndroidUnicast() {
		try {
			$unicast = new AndroidUnicast();
			$unicast->setAppMasterSecret($this->appMasterSecret);
			$unicast->setPredefinedKeyValue("appkey",           $this->appkey);
			$unicast->setPredefinedKeyValue("timestamp",        $this->timestamp);

			$unicast->setPredefinedKeyValue("device_tokens",           $this->device_token);
			//消息通知的提示文字
			$unicast->setPredefinedKeyValue("ticker",           $this->ticker);

			//消息通知的标题
			$unicast->setPredefinedKeyValue("title",            $this->title);

			//消息通知的内容
			$unicast->setPredefinedKeyValue("text",             $this->text);

			//用户点击通知后做的操作  "go_app"打开应用  "go_url": 跳转到URL "go_activity": 打开特定的activity "go_custom": 用户自定义内容。
			$unicast->setPredefinedKeyValue("after_open",       $this->type);

			$unicast->setPredefinedKeyValue("url",       $this->url);
			$unicast->setPredefinedKeyValue("activity",       $this->activity);
			$unicast->setPredefinedKeyValue("custom",       $this->custom);
			$unicast->setPredefinedKeyValue("production_mode", "true");
			// Set extra fields
			$unicast->setExtraField("test", "helloworld");
			//print("Sending unicast notification, please wait...\r\n");
			$unicast->send();
			//print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

	//向指定的用户群发送单播消息
	function sendAndroidListcast() {
		try {
			$listcast = new AndroidListcast();
			$listcast->setAppMasterSecret($this->appMasterSecret);
			$listcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$listcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			$listcast->setPredefinedKeyValue("ticker",           $this->ticker);
			//消息通知的提示文字
			$listcast->setPredefinedKeyValue("device_tokens",           $this->device_token);
			//消息通知的标题
			$listcast->setPredefinedKeyValue("title",            $this->title);
			//消息通知的内容
			$listcast->setPredefinedKeyValue("text",             $this->text);
			//用户点击通知后做的操作  "go_app"打开应用  "go_url": 跳转到URL "go_activity": 打开特定的activity "go_custom": 用户自定义内容。
			$listcast->setPredefinedKeyValue("after_open",       $this->type);

			$listcast->setPredefinedKeyValue("url",       $this->url);
			$listcast->setPredefinedKeyValue("activity",       $this->activity);
			$listcast->setPredefinedKeyValue("custom",       $this->custom);
			$listcast->setPredefinedKeyValue("production_mode", "true");
			// Set extra fields
			$listcast->setExtraField("test", "helloworld");
			//print("Sending unicast notification, please wait...\r\n");
			$listcast->send();
			//print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}


	//发送文件消息
	function sendAndroidFilecast() {
		try {
			$filecast = new AndroidFilecast();
			$filecast->setAppMasterSecret($this->appMasterSecret);
			$filecast->setPredefinedKeyValue("appkey",           $this->appkey);
			$filecast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			$filecast->setPredefinedKeyValue("ticker",           "Android filecast ticker");
			$filecast->setPredefinedKeyValue("title",            "Android filecast title");
			$filecast->setPredefinedKeyValue("text",             "Android filecast text");
			$filecast->setPredefinedKeyValue("after_open",       "go_app");  //go to app
			print("Uploading file contents, please wait...\r\n");
			// Upload your device tokens, and use '\n' to split them if there are multiple tokens
			$filecast->uploadContents("aa"."\n"."bb");
			//print("Sending filecast notification, please wait...\r\n");
			$filecast->send();
			//print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}


	//向满足条件的用户发送消息
	function sendAndroidGroupcast() {
		try {
			/* 
		 	 *  Construct the filter condition:
		 	 *  "where": 
		 	 *	{
    	 	 *		"and": 
    	 	 *		[
      	 	 *			{"tag":"test"},
      	 	 *			{"tag":"Test"}
    	 	 *		]
		 	 *	}
		 	 */
			$filter = 	array(
							"where" => 	array(
								    		"and" 	=>  array(
								    						array(
							     								"tag" => "test"
															),
								     						array(
							     								"tag" => "Test"
								     						)
								     		 			)
								   		)
					  	);
					  
			$groupcast = new AndroidGroupcast();
			$groupcast->setAppMasterSecret($this->appMasterSecret);
			$groupcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$groupcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set the filter condition
			$groupcast->setPredefinedKeyValue("filter",           $filter);
			$groupcast->setPredefinedKeyValue("ticker",           "Android groupcast ticker");
			$groupcast->setPredefinedKeyValue("title",            "Android groupcast title");
			$groupcast->setPredefinedKeyValue("text",             "Android groupcast text");
			$groupcast->setPredefinedKeyValue("after_open",       "go_app");
			// Set 'production_mode' to 'false' if it's a test device. 
			// For how to register a test device, please see the developer doc.
			$groupcast->setPredefinedKeyValue("production_mode", "true");
			//print("Sending groupcast notification, please wait...\r\n");
			$groupcast->send();
			//print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

	function sendAndroidCustomizedcast() {
		try {
			$customizedcast = new AndroidCustomizedcast();
			$customizedcast->setAppMasterSecret($this->appMasterSecret);
			$customizedcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$customizedcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set your alias here, and use comma to split them if there are multiple alias.
			// And if you have many alias, you can also upload a file containing these alias, then 
			// use file_id to send customized notification.
			$customizedcast->setPredefinedKeyValue("alias",            "xx");
			// Set your alias_type here
			$customizedcast->setPredefinedKeyValue("alias_type",       "xx");
			$customizedcast->setPredefinedKeyValue("ticker",           "Android customizedcast ticker");
			$customizedcast->setPredefinedKeyValue("title",            "Android customizedcast title");
			$customizedcast->setPredefinedKeyValue("text",             "Android customizedcast text");
			$customizedcast->setPredefinedKeyValue("after_open",       "go_app");
			//print("Sending customizedcast notification, please wait...\r\n");
			$customizedcast->send();
			//print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}



	//向所有的ios设备推送消息

	function sendIOSBroadcast() {
		try {
			$brocast = new IOSBroadcast();
			$brocast->setAppMasterSecret($this->appMasterSecret);
			$brocast->setPredefinedKeyValue("appkey",           $this->appkey);
			$brocast->setPredefinedKeyValue("timestamp",        $this->timestamp);

			$brocast->setPredefinedKeyValue("alert", "IOS 广播测试");
			$brocast->setPredefinedKeyValue("badge", 0);
			$brocast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$brocast->setPredefinedKeyValue("production_mode", "false");
			// Set customized fields
			$brocast->setCustomizedField("test", "helloworld");
			//print("Sending broadcast notification, please wait...\r\n");
			$brocast->send();
			//print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}



	//向指定的用户组发送消息
	function sendIOSListcast() {
		// echo $this->device_token; die;
		try {
			$listcast = new IOSListcast();
			$listcast->setAppMasterSecret($this->appMasterSecret);
			$listcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$listcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			$listcast->setPredefinedKeyValue("device_tokens",	$this->device_token); 
			$listcast->setPredefinedKeyValue("type",        			"listcast");
			$listcast->setPredefinedKeyValue("alert", $this->ticker);
			$listcast->setPredefinedKeyValue("badge", 0);
			// echo $this->type; die;
			$listcast->setPredefinedKeyValue("after_open", $this->type);
			$listcast->setPredefinedKeyValue("url", $this->url);
			$listcast->setPredefinedKeyValue("activity", $this->activity);
			$listcast->setPredefinedKeyValue("custom", $this->custom);

			$listcast->setPredefinedKeyValue("sound", "chime");
		
			$listcast->setPredefinedKeyValue("production_mode", "false");
			$listcast->setCustomizedField("test", "helloworld");
			$listcast->send();
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

	//向指定的用户发送消息
	function sendIOSUnicast() {
		try {
			$unicast = new IOSUnicast();
			$unicast->setAppMasterSecret($this->appMasterSecret);
			$unicast->setPredefinedKeyValue("appkey",           $this->appkey);
			// echo $this->appkey; echo $this->appMasterSecret; die;
 			$unicast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			$unicast->setPredefinedKeyValue("type",        			"unicast");
			// Set your device tokens here
			$unicast->setPredefinedKeyValue("device_tokens",    $this->device_token); 

			$unicast->setPredefinedKeyValue("alert", $this->ticker);
			$unicast->setPredefinedKeyValue("badge", 0);
			$unicast->setPredefinedKeyValue("sound", "chime");

			$unicast->setPredefinedKeyValue("after_open", $this->type);
			$unicast->setPredefinedKeyValue("url", $this->url);
			$unicast->setPredefinedKeyValue("activity", $this->activity);
			$unicast->setPredefinedKeyValue("custom", $this->custom);

			$unicast->setPredefinedKeyValue("production_mode", "false");
			$unicast->setCustomizedField("test", "helloworld");
			$unicast->send();
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}



	function sendIOSFilecast() {
		// echo $this->$device_token; die;
		try {
			$filecast = new IOSFilecast();
			$filecast->setAppMasterSecret($this->appMasterSecret);
			$filecast->setPredefinedKeyValue("appkey",           $this->appkey);
			$filecast->setPredefinedKeyValue("timestamp",        $this->timestamp);

			$filecast->setPredefinedKeyValue("alert", "IOS 文件播测试");
			$filecast->setPredefinedKeyValue("badge", 0);
			$filecast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$filecast->setPredefinedKeyValue("production_mode", "false");
			print("Uploading file contents, please wait...\r\n");
			// Upload your device tokens, and use '\n' to split them if there are multiple tokens
			$filecast->uploadContents("aa"."\n"."bb");
			//print("Sending filecast notification, please wait...\r\n");
			$filecast->send();
			//print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

	function sendIOSGroupcast() {
		try {
			/* 
		 	 *  Construct the filter condition:
		 	 *  "where": 
		 	 *	{
    	 	 *		"and": 
    	 	 *		[
      	 	 *			{"tag":"iostest"}
    	 	 *		]
		 	 *	}
		 	 */
			$filter = 	array(
							"where" => 	array(
								    		"and" 	=>  array(
								    						array(
							     								"tag" => "iostest"
															)
								     		 			)
								   		)
					  	);
					  
			$groupcast = new IOSGroupcast();
			$groupcast->setAppMasterSecret($this->appMasterSecret);
			$groupcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$groupcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set the filter condition
			$groupcast->setPredefinedKeyValue("filter",           $filter);
			$groupcast->setPredefinedKeyValue("alert", "IOS 组播测试");
			$groupcast->setPredefinedKeyValue("badge", 0);
			$groupcast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$groupcast->setPredefinedKeyValue("production_mode", "false");
			//print("Sending groupcast notification, please wait...\r\n");
			$groupcast->send();
			//print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

	function sendIOSCustomizedcast() {
		try {
			$customizedcast = new IOSCustomizedcast();
			$customizedcast->setAppMasterSecret($this->appMasterSecret);
			$customizedcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$customizedcast->setPredefinedKeyValue("timestamp",        $this->timestamp);

			// Set your alias here, and use comma to split them if there are multiple alias.
			// And if you have many alias, you can also upload a file containing these alias, then 
			// use file_id to send customized notification.
			$customizedcast->setPredefinedKeyValue("alias", "xx");
			// Set your alias_type here
			$customizedcast->setPredefinedKeyValue("alias_type", "xx");
			$customizedcast->setPredefinedKeyValue("alert", "IOS 个性化测试");
			$customizedcast->setPredefinedKeyValue("badge", 0);
			$customizedcast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$customizedcast->setPredefinedKeyValue("production_mode", "false");
			//print("Sending customizedcast notification, please wait...\r\n");
			$customizedcast->send();
			//print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}
}

// Set your appkey and master secret here
// $demo = new Sendmessage("your appkey", "your app master secret");
// $demo->sendAndroidUnicast();
/* these methods are all available, just fill in some fields and do the test
 * $demo->sendAndroidBroadcast();
 * $demo->sendAndroidFilecast();
 * $demo->sendAndroidGroupcast();
 * $demo->sendAndroidCustomizedcast();
 *
 * $demo->sendIOSBroadcast();
 * $demo->sendIOSUnicast();
 * $demo->sendIOSFilecast();
 * $demo->sendIOSGroupcast();
 * $demo->sendIOSCustomizedcast();
 */

/**
	end!_____________
**/

?>