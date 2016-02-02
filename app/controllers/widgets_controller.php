<?php
  class WidgetsController extends AppController {	
 	
  	var $uses = array('Domain', 'Customer', 'CustomerLog', 'SmsVendor', 'ValidNumber', 'Widget');
 	
 	function beforeFilter() {
 		Configure::write('debug', 0);
 	}
 	
 	function index() {
 		$this->set('tab', array('8'));
		$this->getFeedback();
 		$this->layout = 'before_login';
 	}
 	
  	function view() {

  		$this->checkAccess('developer');

		$this->layout = 'after_login';
		$this->set('tab', array('9'));
	
 	}

	function download($mode='web') {

 		$this->checkAccess('developer');
 		
 		// save details
 		$data['domain_id'] = $this->Session->read('domain_id');
 		$data['download_date'] = date('Y-m-d H:i:s');
 		$this->Widget->save($data);
 		
 		if($mode == 'web') {
	 		$id = 'freesmsapi_web_widget_v1.1.zip';
	 		$name = 'freesmsapi_web_widget';
 		} else if($mode == 'wap') {
 			$id = 'freesmsapi_wap_widget_v1.0.zip';
	 		$name = 'freesmsapi_wap_widget';
 		}
 		
 		// download
 		 $this->view = 'Media';
 		 $params = array(              
 		 	'id' => $id,              
 		 	'name' => $name,              
 		 	'download' => true,              
 		 	'extension' => 'zip',  // must be lower case              
 		 	'path' => APP . 'files' . DS   // don't forget terminal 'DS'       
 		 );       
 		 $this->set($params);
 		
 	}
 	
 	// old widget
  	function get_html() {
 		
  		echo 'Please use our new widget';
  		exit;
  		
 		$data = $_GET;
 		
 		if(empty($data['secretkey'])) {
 			die("Secret Key is required");
 		} else if(!$this->verifySecretKey($data['secretkey'])) {
 			die("Invalid Secret Key");
 		}
 		
 		if(empty($data['csrftoken'])) {
 			die("CSRF Token is required");
 		}
 		
 		if(empty($data['submitpath'])) {
 			$data['submitpath'] = '';
 		}
 		
 		echo '<link type="text/css" href="'.SERVER.'css/widget.css" rel="stylesheet"/>
 			<div id="freesmsapi_widget_d" style="width:165px"> 
				<form id="freesmsapi_widget_f" action="'.$data['submitpath'].'" method="post" onsubmit="return freesmsapi_widget_send()"> 
					<label for="freesmsapi_widget_d_in">Mobile (Comma Seperated)</label> 
					<input id="freesmsapi_widget_d_in" name="freesmsapi_to" type="text" onkeypress="return freesmsapi_widget_numKey(event)"/> 
					<label for="freesmsapi_widget_d_t">Message ('.MESSAGE_CHAR_LIMIT.' Chars)</label> 
					<textarea id="freesmsapi_widget_d_t" name="freesmsapi_message" rows="6" onkeypress="freesmsapi_widget_textCounter(this)"></textarea> 
					<input id="freesmsapi_widget_d_is" name="freesmsapi_widget_d_is" type="submit" value="Send SMS" /> 
					<div class="note">Available only in India - <a href="'.SERVER.'" target="_blank">freesmsapi.com</a></div>
					<input type="hidden" name="csrftoken" value="'.$data['csrftoken'].'" />
				</form> 
			</div> 
			<script type="text/javascript" src="'.SERVER.'js/widget.js"></script>';
 		exit;

 	}
 	
 	// old widget
 	function send_sms() {

 		echo 'Please use our new widget';
  		exit;
 		
		if(empty($_REQUEST['senderid'])) {
 			$_REQUEST['senderid'] = SMS_SENDER_ID;
 		}

 		$this->requestAction('/messages/send');
 		exit;
 	}
 	
 	// new widget
 	function get_html_v1() {
 		
 		$data = $_GET;
 		
 		if(empty($data['secretkey'])) {
 			
 			$this->widget_response(0, 'Secret Key is required');
 			exit;
 		
 		} else if(!$this->verifySecretKey($data['secretkey'])) {

 			$this->widget_response(0, 'Invalid Secret Key');
 			exit;
 		
 		}
 		
 		if(empty($data['csrftoken'])) {
 			
 			$this->widget_response(0, 'CSRF Token is required');
 			exit;
 			
 		}
 		
 		if(empty($data['submitpath'])) {
 			$data['submitpath'] = '';
 		}
 		
 		echo '<link type="text/css" href="'.SERVER.'css/widget_v1.css" rel="stylesheet"/>
 			<div id="freesmsapi_widget_d" style="width:165px">
				<div id="freesmsapi_widget_main">
 					<form id="freesmsapi_widget_fmain" action="'.$data['submitpath'].'" method="post">
						<label for="freesmsapi_widget_d_fm">Your mobile number</label> 
						<input id="freesmsapi_widget_d_fm" name="freesmsapi_from" type="text" autocomplete="off" onkeypress="return freesmsapi_widget_numKey(event)"/>
						<label for="freesmsapi_widget_d_in">Recipient Mobile Number</label> 
						<input id="freesmsapi_widget_d_in" name="freesmsapi_to" type="text" autocomplete="off" onkeypress="return freesmsapi_widget_numKey(event)"/> 
						<label for="freesmsapi_widget_d_t">Message (<span id="freesmsapi_widet_tc">'.MESSAGE_CHAR_LIMIT.'</span> Chars)</label> 
						<textarea id="freesmsapi_widget_d_t" name="freesmsapi_message" rows="6" onkeydown="freesmsapi_widget_textCounter(this)" onkeyup="freesmsapi_widget_textCounter(this)"></textarea> 
						<input id="freesmsapi_widget_d_is" name="freesmsapi_widget_d_is" type="button" onclick="freesmsapi_widget_send()" value="Send SMS" /> 
						<div class="note">Available only in India <br/><a href="'.SERVER.'" target="_blank">FREE SMS API</a></div>
						<input type="hidden" id="csrftoken" name="csrftoken" value="'.$data['csrftoken'].'" />
					</form> 
				</div>
				<div id="freesmsapi_widget_verify" style="display:none">
					<div>You will receive a verification code shortly on your mobile number. Please enter it to send SMS.</div>
					<br/>
					<form id="freesmsapi_widget_fverify" action="'.$data['submitpath'].'" method="post" onsubmit="return freesmsapi_widget_send()">
						<label for="freesmsapi_widget_d_vr">Enter verification code</label> 
						<input id="freesmsapi_widget_d_vr" type="text" autocomplete="off" onkeypress="return freesmsapi_widget_numKey(event)"/>
						<br/>
						<input id="freesmsapi_widget_d_isv" name="freesmsapi_widget_d_is" type="button" onclick="freesmsapi_widget_send()" value="Send SMS" /> 
						<input id="freesmsapi_widget_d_igb" name="freesmsapi_widget_d_is" type="button" onclick="freesmsapi_widget_back()" value="<< Go Back" /> 
						<div class="note">Available only in India <br/><a href="'.SERVER.'" target="_blank">FREE SMS API</a></div>
						<input type="hidden" name="csrftoken" value="'.$data['csrftoken'].'" />
					</form>
				</div>
			</div> 
			<script type="text/javascript" src="'.SERVER.'js/widget_v1.js"></script>
			<!-- JS -->
			<script src="http://yui.yahooapis.com/3.3.0/build/yui/yui-min.js" charset="utf-8"></script>';
 		exit;

 	}
 	
 	// new widget
 	function send_sms_v1() {

 		if(SHOW_ONLY_TO_ME) {
 			//Configure::write('debug', 2);exit;
 		}

 		if(FREE_SMS_SERVER_DOWN) {
 			$this->widget_response(0, UNAVAILABLE_MESSAGE);
 			exit;
 		}
 		
 		if(NINE_TO_NINE_ACTIVATED) {
 			$this->widget_response(0, NINE_TO_NINE_ACTIVATED_MESSAGE);
 			exit;
 		}

		if(empty($_REQUEST['senderid'])) {
 			$_REQUEST['senderid'] = SMS_SENDER_ID;
 		}

 		$skey = urldecode(trim($_REQUEST['skey']));
 		$from = urldecode(trim($_REQUEST['from']));
 		$recipient = urldecode(trim($_REQUEST['recipient']));
 		$message = urldecode(trim($_REQUEST['message']));
 		$senderid = urldecode(trim($_REQUEST['senderid']));
 		$client_ip = urldecode(trim($_REQUEST['client_ip']));
 		$vcode = urldecode(trim($_REQUEST['vcode']));

 		// check for valid secret key
 		$data = $this->checkSecretKey($skey);
 		if(!$data) {

 			$this->widget_response(0, 'Invalid Secret Key');
 			exit;
 				
 		}
 		
 		// check for valid client ip address
 		if(empty($client_ip) || !ip2long($client_ip)) {
 			
 			$this->widget_response(0, 'Invalid IP Address');
 			exit;
 			
 		}
 		
 		
 		if($message != '') {

 			// check mobile number
 			if(!$this->checkNumber($from)) {
 				
 				$this->widget_response(0, 'Invalid mobile number');
 				exit;
 				
 			}
 			
 			// check mobile number of recipient
 			if(!$this->checkNumber($recipient)) {
 				
 				$this->widget_response(0, 'Invalid mobile number of recipient');
 				exit;
 				
 			}

 			// attach mobile number with message
 			$message = $this->attachMobileToMessage($message, $from);
 			
 			// get customer id
 			$cond['conditions']['Customer.name'] = $from;
 			$cond['conditions']['Customer.status'] = 0;
 			$customer_data = $this->Customer->find('all', $cond);
 			if(empty($customer_data)) {
 				
 				$value['name'] = $from;
 				$this->Customer->save($value);
 				$customer_id = $this->Customer->getLastInsertId();
 				
 			} else $customer_id = $customer_data['0']['Customer']['id'];
 			
 			$verifycode = $this->generateRandomNumber(4);
 			$senderid = !empty($senderid) ? $senderid : SMS_SENDER_ID; 
 			
 			// check for number of messages already send out
 			unset($cond);
 			$cond['conditions']['CustomerLog.customer_id'] = $customer_id;
 			$cond['conditions']['CustomerLog.domain_id'] = $data[0];
 			$cond['conditions']['CustomerLog.client_ip'] = ip2long($client_ip);
 			$cond['conditions']['CustomerLog.send'] = 1;
 			$cond['conditions'][0] = 'CustomerLog.created LIKE "'.date('Y-m-d').'%"';
 			$count = $this->CustomerLog->find('count', $cond);
 			
 			// send verification code
 			if(!$count || $count % WIDGET_SMS_LIMIT_PER_SESSION == 0) {

 				// save data
 				unset($value);
	 			$customerLogId = $this->save_customer_log($customer_id, $recipient, $message, $senderid, $data[0], $verifycode, $client_ip);

	 			// send verification code
	 			$verify_message = "Mobile number verification code - ".$verifycode." \n\n- Freesmsapi.com";
	 			$response_key = $this->sendSMS($from, $verify_message, INTERNAL_SMS_SENDER_ID, true);
	 			
 				if($this->checkDND($response_key)) {
	 				$return_message = 'DND service is enabled on mobile number '.$from.', hence verification code could not be sent';
	 				$this->widget_response(0, $return_message);
 				} else {
 					$return_message = 'Verification code has been send to your mobile number '.$from.', please enter it to continue';
 					$this->widget_response(1, $return_message);
 				}
	 			
	 			exit;

	 		// send message	
 			} else {
 				
 				// save data
	 			$customerLogId = $this->save_customer_log($customer_id, $recipient, $message, $senderid, $data[0], '', $client_ip);

	 			// send message
	 			$return_message = $this->requestAction('/Messages/send/true/'.$skey.'/'.urlencode($message).'/'.$recipient.'/'.$senderid.'/true');
	 			
	 			// update send status
	 			$this->CustomerLog->id = $customerLogId;
	 			$this->CustomerLog->saveField('send', 1);

	 			$this->widget_response(2, $return_message);
	 			exit;
 				
 			}
 			
 			
 		} else if($vcode != '') {
 			
 			// check if verification code is valid or not
 			$cond['conditions']['CustomerLog.verifycode'] = $vcode;
 			$cond['conditions']['CustomerLog.client_ip'] = ip2long($client_ip);
 			$cond['conditions']['CustomerLog.domain_id'] = $data[0];
 			$cond['conditions']['CustomerLog.send'] = 0;
 			$message_data = $this->CustomerLog->find('all', $cond);

 			if(!empty($message_data)) {
 				
 				// send message
 				$sendnow = true;
 				$widgetcall = true;
 				$return_message = $this->requestAction('/Messages/send/'.$sendnow.'/'.$skey.'/'.urlencode($message_data[0]['CustomerLog']['message']).'/'.$message_data[0]['CustomerLog']['recipient'].'/'.$message_data[0]['CustomerLog']['senderid'].'/'.$widgetcall);
 				
 				// update send status
 				$this->CustomerLog->id = $message_data[0]['CustomerLog']['id'];
	 			$this->CustomerLog->saveField('send', 1);
	 			
	 			$this->widget_response(2, $return_message);
	 			exit;
 		
 			} else {
 				
				$return_message = "Invalid verification code";
 				$this->widget_response(0, $return_message);
 				exit;
 			}
 			
 		} else return false;
 		
 	}
 	
 	function save_customer_log($customer_id, $recipient, $message, $senderid, $domain_id, $verifycode, $client_ip) {
 		
 		$value['customer_id'] = $customer_id;
 		$value['recipient'] = $recipient;
 		$value['message'] = $message;
 		$value['senderid'] = $senderid;
 		$value['domain_id'] = $domain_id;
 		$value['verifycode'] = $verifycode;
 		$value['client_ip'] = ip2long($client_ip);
 		$this->CustomerLog->save($value);
 		
 		return $this->CustomerLog->getLastInsertId();
 		
 		$this->autoRender = false;
 		
 	}
 	
 	function widget_response($code, $message) {

 		echo $code . WIDGET_SEPERATOR . $message;
 		
 		$this->autoRender = false;
 		
 	}
 	
	// wap widget
 	function get_html_wap() {
 		
 		$data = $_GET;
 		
		if(empty($data['secretkey'])) {
 			
 			$this->widget_response(0, "Secret Key is required");
 			exit;
 			
 		} else if(!$this->verifySecretKey($data['secretkey'])) {
 			
 			$this->widget_response(0, "Invalid Secret Key");
 			exit;
 		
 		}
 		
 		if(empty($data['csrftoken'])) {
 			
 			$this->widget_response(0, "CSRF Token is required");
 			exit;
 			
 		}
 		
 		if(empty($data['submitpath'])) {
 			$data['submitpath'] = '';
 		}
 		
 		if(empty($data['step'])) {
 			$data['step'] = 1;
 		}
 		
 		//$isMobile = $this->detectMobileBrowser($data['http_user_agent']);
 		//$submitButton = $isMobile ? 'submit' : 'button';
 		
 		if($data['step'] == 1) {
	 		
 			$main = '<link type="text/css" href="'.SERVER.'css/widget_v1.css" rel="stylesheet"/>
	 			<div id="freesmsapi_widget_d" style="width:165px">
					<div id="freesmsapi_widget_main">
	 					<form id="freesmsapi_widget_fmain" method="post">
							<label for="freesmsapi_widget_d_fm">Your mobile number</label> 
							<input id="freesmsapi_widget_d_fm" name="freesmsapi_from" type="text" autocomplete="off"/>
							<label for="freesmsapi_widget_d_in">Recipient Mobile Number</label> 
							<input id="freesmsapi_widget_d_in" name="freesmsapi_to" type="text" autocomplete="off"/> 
							<label for="freesmsapi_widget_d_t">Message (<span id="freesmsapi_widet_tc">'.MESSAGE_CHAR_LIMIT.'</span> Chars)</label> 
							<textarea id="freesmsapi_widget_d_t" name="freesmsapi_message" rows="6"></textarea> 
							<input id="freesmsapi_widget_d_is" name="freesmsapi_widget_d_is" type="submit" value="Send SMS" /> 
							<div class="note">Available only in India <br/><a href="'.SERVER.'" target="_blank">FREE SMS API</a></div>
							<input type="hidden" id="csrftoken" name="csrftoken" value="'.$data['csrftoken'].'" />
						</form> 
					</div>
				</div>';
	 		
	 		echo $main;	
 		
 		} else if($data['step'] == 2) {

 			//<div>You will receive a verification code shortly on your mobile number. Please enter it to send SMS.</div><br/>
 			
	 		$verify = '<link type="text/css" href="'.SERVER.'css/widget_v1.css" rel="stylesheet"/>
	 			<div id="freesmsapi_widget_d" style="width:165px">
	 				<div id="freesmsapi_widget_verify">
						<form id="freesmsapi_widget_fverify" method="post">
							<label for="freesmsapi_widget_d_vr">Enter verification code</label> 
							<input id="freesmsapi_widget_d_vr" name="freesmsapi_vcode" type="text" autocomplete="off"/>
							<br/>
							<input id="freesmsapi_widget_d_is" name="freesmsapi_widget_d_is" type="submit" value="Send SMS" />
							<div align="center"><a href="freesmsapi_index.php">&lt;&lt; Go Back</a></div>
							<br/> 
							<div class="note">Available only in India <br/><a href="'.SERVER.'" target="_blank">FREE SMS API</a></div>
							<input type="hidden" name="csrftoken" value="'.$data['csrftoken'].'" />
						</form>
					</div>
				</div>';
 		
	 		echo $verify;
 		
 		} else echo '';
 		
 		exit;

 	}
 	
 	//for wap
  	function send_sms_wap() {
 		
 		//Configure::write('debug', 2);
 		
 		if(FREE_SMS_SERVER_DOWN) {
 			$this->widget_response(0, UNAVAILABLE_MESSAGE);
 			exit;
 		}
 		
  		if(NINE_TO_NINE_ACTIVATED) {
 			$this->widget_response(0, NINE_TO_NINE_ACTIVATED_MESSAGE);
 			exit;
 		}

		if(empty($_REQUEST['senderid'])) {
 			$_REQUEST['senderid'] = SMS_SENDER_ID;
 		}

 		$skey = urldecode(trim($_REQUEST['skey']));
 		$from = urldecode(trim($_REQUEST['from']));
 		$recipient = urldecode(trim($_REQUEST['recipient']));
 		$message = urldecode(trim($_REQUEST['message']));
 		$senderid = urldecode(trim($_REQUEST['senderid']));
 		$client_ip = urldecode(trim($_REQUEST['client_ip']));
 		$vcode = urldecode(trim($_REQUEST['vcode']));

 		// check for valid secret key
 		$data = $this->checkSecretKey($skey);
 		if(!$data) {

 			$this->widget_response(0, 'Invalid Secret Key');
 			exit;
 				
 		}
 		
 		// check for valid client ip address
 		if(empty($client_ip) || !ip2long($client_ip)) {
 			
 			$this->widget_response(0, 'Invalid IP Address');
 			exit;
 			
 		}
 		
 		//$return_message = "Invalid verification code";
 		//	$this->widget_response(2, $return_message);
 		//	exit;
 		
 		if($message != '') {

 			// check mobile number
 			if(!$this->checkNumber($from)) {
 				
 				$this->widget_response(0, 'Invalid mobile number');
 				exit;
 				
 			}
 			
 			// check mobile number of recipient
 			if(!$this->checkNumber($recipient)) {
 				
 				$this->widget_response(0, 'Invalid mobile number of recipient');
 				exit;
 				
 			}

 			// attach mobile number with message
 			$message = $this->attachMobileToMessage($message, $from);
 			
 			// get customer id
 			$cond['conditions']['Customer.name'] = $from;
 			$cond['conditions']['Customer.status'] = 0;
 			$customer_data = $this->Customer->find('all', $cond);
 			if(empty($customer_data)) {
 				
 				$value['name'] = $from;
 				$this->Customer->save($value);
 				$customer_id = $this->Customer->getLastInsertId();
 				
 			} else $customer_id = $customer_data['0']['Customer']['id'];
 			
 			$verifycode = $this->generateRandomNumber(4);
 			$senderid = !empty($senderid) ? $senderid : SMS_SENDER_ID; 
 			
 			// check for number of messages already send out
 			unset($cond);
 			$cond['conditions']['CustomerLog.customer_id'] = $customer_id;
 			$cond['conditions']['CustomerLog.domain_id'] = $data[0];
 			$cond['conditions']['CustomerLog.client_ip'] = ip2long($client_ip);
 			$cond['conditions']['CustomerLog.send'] = 1;
 			$cond['conditions'][0] = 'CustomerLog.created LIKE "'.date('Y-m-d').'%"';
 			$count = $this->CustomerLog->find('count', $cond);
 			
 			// send verification code
 			if(!$count || $count % WIDGET_SMS_LIMIT_PER_SESSION == 0) {

 				// save data
 				unset($value);
	 			$customerLogId = $this->save_customer_log($customer_id, $recipient, $message, $senderid, $data[0], $verifycode, $client_ip);

	 			// send verification code
	 			$verify_message = "Mobile number verification code - ".$verifycode." \n\n- Freesmsapi.com";
	 			$response_key = $this->sendSMS($from, $verify_message, INTERNAL_SMS_SENDER_ID, true);
	 			
 				if($this->checkDND($response_key)) {
	 				$return_message = 'DND service is enabled on mobile number '.$from.', hence verification code could not be sent';
	 				$this->widget_response(0, $return_message);
 				} else {
 					$return_message = 'Verification code has been send to your mobile number '.$from.', please enter it to continue';
 					$this->widget_response(2, $return_message);
 				}
 				
	 			exit;

	 		// send message	
 			} else {
 				
 				// save data
	 			$customerLogId = $this->save_customer_log($customer_id, $recipient, $message, $senderid, $data[0], '', $client_ip);

	 			// send message
	 			$return_message = $this->requestAction('/Messages/send/true/'.$skey.'/'.urlencode($message).'/'.$recipient.'/'.$senderid.'/true');
	 			
	 			// update send status
	 			$this->CustomerLog->id = $customerLogId;
	 			$this->CustomerLog->saveField('send', 1);

	 			$this->widget_response(1, $return_message);
	 			exit;
 				
 			}
 			
 			
 		} else if($vcode != '') {
 			
 			// check if verification code is valid or not
 			$cond['conditions']['CustomerLog.verifycode'] = $vcode;
 			$cond['conditions']['CustomerLog.client_ip'] = ip2long($client_ip);
 			$cond['conditions']['CustomerLog.domain_id'] = $data[0];
 			$cond['conditions']['CustomerLog.send'] = 0;
 			$message_data = $this->CustomerLog->find('all', $cond);

 			if(!empty($message_data)) {
 				
 				// send message
 				$sendnow = true;
 				$widgetcall = true;
 				$return_message = $this->requestAction('/Messages/send/'.$sendnow.'/'.$skey.'/'.urlencode($message_data[0]['CustomerLog']['message']).'/'.$message_data[0]['CustomerLog']['recipient'].'/'.$message_data[0]['CustomerLog']['senderid'].'/'.$widgetcall);
 				
 				// update send status
 				$this->CustomerLog->id = $message_data[0]['CustomerLog']['id'];
	 			$this->CustomerLog->saveField('send', 1);
	 			
	 			$this->widget_response(1, $return_message);
	 			exit;
 		
 			} else {
 				
				$return_message = "Invalid verification code";
 				$this->widget_response(2, $return_message);
 				exit;
 			}
 			
 		} else {
 			
 			
 				
 		}
 		
 	}

 	function attachMobileToMessage($message, $from) {
 		
 		return $from . " says: \r\n" . $message;
 		
 	}
 	
 	function findmyip() {
 		
 		echo $_SERVER['REMOTE_ADDR'];
 		exit;
 		
 	}
 	
 	/*function detectMobileBrowser($useragent) {
 		
 		if(empty($useragent))
 			return false;
 			
 		//$useragent=$_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/android.+mobile|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
			return true;
		else return false;
		
		$this->autoRender = false;
 		
 	}*/
 	
 	function checkDND($response_key) {
 		if(substr($response_key, 0, 4) == '007-') return true;
 		else return false;
 	}
 	
 }
?>