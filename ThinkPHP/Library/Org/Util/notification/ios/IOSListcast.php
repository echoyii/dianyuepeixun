<?php
require_once(dirname(__FILE__) . '/../IOSNotification.php');

class IOSlistcast extends IOSNotification {
	function __construct() {
		parent::__construct();
		$this->data["type"] = "listcast";
		$this->data["device_tokens"] = NULL;
	}

}
?>