<?php
/*
 * Created on Feb 21, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class AppController extends Controller {
	
	var $uses = array('Feedback', 'Smslog', 'Domain', 'Contact', 'Alias', 'AliasReminder');
	var $serverList = array('S1', 'S2');
	var $bulkServerList = array('1', '2', '3', '4', '5');
	var $updateBulkAmountArray = array('UNDELIVERED', 'EXPIRED');
	//var $serverList = array('S1', 'S2', 'S3', 'S4', 'S5', 'S6', 'S7', 'S8', 'S9');
	var $components = array('Email'); //, 'DebugKit.Toolbar'
	var $persistModel = false; // when true was throwing error in send message
	var $bulkSmsExpiryReminderList = array('10','5','2','0');
	var $bulkSmsExpired = 0;	
	
	//SMS VENDORS
	var $sms_vendor_id = '';
	var $sms_vendor_details = array();
	
	function beforeFilter() {
		
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
 			Configure::write('debug', 0);
 		}
 		
	}
 
	function encrypt($text) {
	    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
	}
	
	function decrypt($text) {
	    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
	}
	
	function convert($size) {
	    $unit=array('b','kb','mb','gb','tb','pb');
	    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}
 
	function direcpayEncode($data) {
		$enc = base64_encode($data);
		$str1 = substr($enc, 0, 1);
		$str2 = substr($enc, 1, strlen($enc));
		$newenc = base64_encode($str1 .'T'. $str2);
		return $newenc;
	}
	
	function format_money($number, $fractional=false) {
		if($fractional) {
			$number = sprintf('%.2f', $number);
			list($number, $frac) = explode('.', $number);
		} 
		$spl = str_split($number);
		$lpcount = count($spl);
		$rem = $lpcount-3;
		$data = '';
		
		//even one
		if($lpcount%2==0) {
			for($i=0;$i<=$lpcount-1;$i++) {
		
				if($i%2!=0 && $i!=0 && $i!=$lpcount-1) {
					$data[] = ",";
				}
				$data[] = $spl[$i];
			}
		}
		//odd one
		if($lpcount%2!=0) {
			for($i=0;$i<=$lpcount-1;$i++) {
				if($i%2==0 && $i!=0 && $i!=$lpcount-1) {
					$data[] = ",";
				}
				$data[] = $spl[$i];
			}
		}
		$number = implode('', $data);
		return $fractional ? $number .'.'. $frac : $number;
	}
	
	function min_key($array) {
	    foreach ($array as $key => $val) {
	        if ($val == min($array)) return $key; 
	    }
	}

	function csv_to_array($csv, $delimiter = ',', $enclosure = '"', $escape = '\\', $terminator = "\n") { 
	    $r = array(); 
	    $rows = explode($terminator,trim($csv)); 
	    //$names = array_shift($rows);
	    //$names = str_getcsv($names,$delimiter,$enclosure,$escape); 
	    //$nc = count($names); 
	    foreach ($rows as $row) {
	        if (trim($row)) { 
	            $values = str_getcsv($row, $delimiter, $enclosure, $escape); 
	            //if (!$values) $values = array_fill(0,$nc,null); 
	            //$r[] = array_combine($names,$values);
	            $r[] = array_filter($values);
	        } 
	    } 
	    return $r; 
	}
	
	function date_diff($interval, $datefrom, $dateto, $using_timestamps = false) {
		
		/*
	    $interval can be:
	    yyyy - Number of full years
	    q - Number of full quarters
	    m - Number of full months
	    y - Difference between day numbers
	        (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
	    d - Number of full days
	    w - Number of full weekdays
	    ww - Number of full weeks
	    h - Number of full hours
	    n - Number of full minutes
	    s - Number of full seconds (default)
	    */
	    
	    if (!$using_timestamps) {
	        $datefrom = strtotime($datefrom, 0);
	        $dateto = strtotime($dateto, 0);
	    }
	    $difference = $dateto - $datefrom; // Difference in seconds
	     
	    switch($interval) {
	     
		    case 'yyyy': // Number of full years
		
		        $years_difference = floor($difference / 31536000);
		        if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
		            $years_difference--;
		        }
		        if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
		            $years_difference++;
		        }
		        $datediff = $years_difference;
		        break;
		
		    case "q": // Number of full quarters
		
		        $quarters_difference = floor($difference / 8035200);
		        while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
		            $months_difference++;
		        }
		        $quarters_difference--;
		        $datediff = $quarters_difference;
		        break;
		
		    case "m": // Number of full months
		
		        $months_difference = floor($difference / 2678400);
		        while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
		            $months_difference++;
		        }
		        $months_difference--;
		        $datediff = $months_difference;
		        break;
		
		    case 'y': // Difference between day numbers
		
		        $datediff = date("z", $dateto) - date("z", $datefrom);
		        break;
		
		    case "d": // Number of full days
		
		        $datediff = floor($difference / 86400);
		        break;
		
		    case "w": // Number of full weekdays
		
		        $days_difference = floor($difference / 86400);
		        $weeks_difference = floor($days_difference / 7); // Complete weeks
		        $first_day = date("w", $datefrom);
		        $days_remainder = floor($days_difference % 7);
		        $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
		        if ($odd_days > 7) { // Sunday
		            $days_remainder--;
		        }
		        if ($odd_days > 6) { // Saturday
		            $days_remainder--;
		        }
		        $datediff = ($weeks_difference * 5) + $days_remainder;
		        break;
		
		    case "ww": // Number of full weeks
		
		        $datediff = floor($difference / 604800);
		        break;
		
		    case "h": // Number of full hours
		
		        $datediff = floor($difference / 3600);
		        break;
		
		    case "n": // Number of full minutes
		
		        $datediff = floor($difference / 60);
		        break;
		
		    default: // Number of full seconds (default)
		
		        $datediff = $difference;
		        break;
	    }    
	
	    return $datediff;

	}

	/*	Convert UTF to Unicode	*/
	function UTF_to_Unicode($input, $array=False) {
	
		 $bit1  = pow(64, 0);
		 $bit2  = pow(64, 1);
		 $bit3  = pow(64, 2);
		 $bit4  = pow(64, 3);
		 $bit5  = pow(64, 4);
		 $bit6  = pow(64, 5);
		 
		 $value = '';
		 $val   = array();
		 
		 for($i=0; $i< strlen( $input ); $i++){
		 
		     $ints = ord ( $input[$i] );
		     
		     $z     = ord ( $input[$i] );
		     $y     = ord ( $input[$i+1] ) - 128;
		     $x     = ord ( $input[$i+2] ) - 128;
		     $w     = ord ( $input[$i+3] ) - 128;
		     $v     = ord ( $input[$i+4] ) - 128;
		     $u     = ord ( $input[$i+5] ) - 128;
		
		     if( $ints >= 0 && $ints <= 127 ){
		        // 1 bit
		        $value .= '&#'.($z * $bit1).';';
		        $val[]  = $value; 
		     }
		     if( $ints >= 192 && $ints <= 223 ){
		        // 2 bit
		        $value .= '&#'.(($z-192) * $bit2 + $y * $bit1).';';
		        $val[]  = $value;
		     }    
		     if( $ints >= 224 && $ints <= 239 ){
		        // 3 bit
		        $value .= '&#'.(($z-224) * $bit3 + $y * $bit2 + $x * $bit1).';';
		        $val[]  = $value;
		     }     
		     if( $ints >= 240 && $ints <= 247 ){
		        // 4 bit
		        $value .= '&#'.(($z-240) * $bit4 + $y * $bit3 + $x * $bit2 + $w * $bit1).';';
		        $val[]  = $value;        
		     }     
		     if( $ints >= 248 && $ints <= 251 ){
		        // 5 bit
		        $value .= '&#'.(($z-248) * $bit5 + $y * $bit4 + $x * $bit3 + $w * $bit2 + $v * $bit1).';';
		        $val[]  = $value;   
		     }
		     if( $ints == 252 && $ints == 253 ){
		        // 6 bit
		        $value .= '&#'.(($z-252) * $bit6 + $y * $bit5 + $x * $bit4 + $w * $bit3 + $v * $bit2 + $u * $bit1).';';
		        $val[]  = $value; 
		     }
		     if( $ints == 254 || $ints == 255 ){
		       //echo 'Wrong Result!<br>';
		     }
		     
		 }
	 
		 if( $array === False ){
		    return $unicode = $value;
		 }
		 if($array === True ){
		     $val     = str_replace('&#', '', $value);
		     $val     = explode(';', $val);
		     $len = count($val);
		     unset($val[$len-1]);
		     
		     return $unicode = $val;
		 }
	 
	}
	
	 
	function Unicode_to_UTF( $input, $array=TRUE){
	
	     $utf = '';
	    if(!is_array($input)){
	       $input     = str_replace('&#', '', $input);
	       $input     = explode(';', $input);
	       $len = count($input);
	       unset($input[$len-1]);
	    }
	    for($i=0; $i < count($input); $i++){
	    
	    if ( $input[$i] <128 ){
	       $byte1 = $input[$i];
	       $utf  .= chr($byte1);
	    }
	    if ( $input[$i] >=128 && $input[$i] <=2047 ){
	    
	       $byte1 = 192 + (int)($input[$i] / 64);
	       $byte2 = 128 + ($input[$i] % 64);
	       $utf  .= chr($byte1).chr($byte2);
	    }
	    if ( $input[$i] >=2048 && $input[$i] <=65535){
	    
	       $byte1 = 224 + (int)($input[$i] / 4096);
	       $byte2 = 128 + ((int)($input[$i] / 64) % 64);
	       $byte3 = 128 + ($input[$i] % 64);
	       
	       $utf  .= chr($byte1).chr($byte2).chr($byte3);
	    }
	    if ( $input[$i] >=65536 && $input[$i] <=2097151){
	    
	       $byte1 = 240 + (int)($input[$i] / 262144);
	       $byte2 = 128 + ((int)($input[$i] / 4096) % 64);
	       $byte3 = 128 + ((int)($input[$i] / 64) % 64);
	       $byte4 = 128 + ($input[$i] % 64);
	       $utf  .= chr($byte1).chr($byte2).chr($byte3).chr($byte4);
	    }
	    if ( $input[$i] >=2097152 && $input[$i] <=67108863){
	    
	       $byte1 = 248 + (int)($input[$i] / 16777216);
	       $byte2 = 128 + ((int)($input[$i] / 262144) % 64);
	       $byte3 = 128 + ((int)($input[$i] / 4096) % 64);
	       $byte4 = 128 + ((int)($input[$i] / 64) % 64);
	       $byte5 = 128 + ($input[$i] % 64);
	       $utf  .= chr($byte1).chr($byte2).chr($byte3).chr($byte4).chr($byte5);
	    }
	    if ( $input[$i] >=67108864 && $input[$i] <=2147483647){
	    
	       $byte1 = 252 + ($input[$i] / 1073741824);
	       $byte2 = 128 + (($input[$i] / 16777216) % 64);
	       $byte3 = 128 + (($input[$i] / 262144) % 64);
	       $byte4 = 128 + (($input[$i] / 4096) % 64);
	       $byte5 = 128 + (($input[$i] / 64) % 64);
	       $byte6 = 128 + ($input[$i] % 64);
	       $utf  .= chr($byte1).chr($byte2).chr($byte3).chr($byte4).chr($byte5).chr($byte6);
	    }
	   }
	   return $utf;
	}
	
	/*	Set SMS Vendor Details	*/
	function setSmsVendor($id) {
		if(!empty($id)) {
			$this->sms_vendor_id = $id;
	 		$this->getSmsVendorDetails($id);
		}
	}
	
	/*	Set SMS Vendor Details for Free User	*/
	function getSmsVendor($domain_id) {
		$cond['conditions']['Domain.id'] = $domain_id;
		$cond['conditions']['Domain.status'] = 0;
		$cond['fields'] = array('id', 'sms_vendor_id');
		$this->Domain->recursive = -1;
		$data = $this->Domain->find('all', $cond);
		
		$this->sms_vendor_id = $data['0']['Domain']['sms_vendor_id'];
		$this->getSmsVendorDetails($this->sms_vendor_id);
	}
	
 	/*	Set SMS Vendor Details for Bulk User	*/
	function getBulkSmsVendor($user_id) {
		$cond['conditions']['BulkAccount.bulk_user_id'] = $user_id;
		$cond['conditions']['BulkAccount.status'] = 0;
		$cond['fields'] = array('id', 'sms_vendor_id');
		$this->BulkAccount->recursive = -1;
		$data = $this->BulkAccount->find('all', $cond);
		
		$this->sms_vendor_id = $data['0']['BulkAccount']['sms_vendor_id'];
		$this->getSmsVendorDetails($this->sms_vendor_id);
	}
	
	/*	Set Vendor Details	*/
	function getSmsVendorDetails($id=null) {
		if(!empty($id)) 
			$cond['conditions']['SmsVendor.id'] = $id;
		$cond['conditions']['SmsVendor.status'] = 0;
		$data = $this->SmsVendor->find('all', $cond);
		
		foreach($data as $k => $v) {
			$this->sms_vendor_details['sms_vendor_'.$v['SmsVendor']['id']] = $v['SmsVendor'];
			$this->sms_vendor_details['sms_vendor_'.$v['SmsVendor']['id']]['sms_delivery_report_seperator'] = html_entity_decode($v['SmsVendor']['sms_delivery_report_seperator']);
		}
	}
	
 	/*	Get all Alias	  */
	function get_alias($domain_id=null) {
		
		$domain_id = ($domain_id==null) ? $this->Session->read('domain_id') : $domain_id;
		$cond['Alias.publish'] = '1';
 		$cond['Alias.status'] = '0';
 		$cond['Alias.domain_id'] = $domain_id;
 		$this->Alias->unBindAll();
 		$sid = $this->Alias->findAll($cond);
		
 		if(!empty($sid)) return $sid;
 		else return false;
 		
	}
	
	/*	Check if alias is purchased	  ||	Return DATA if purchased	*/
	function check_alias_purchase($domain_id=null) {
		
		$domain_id = ($domain_id==null) ? $this->Session->read('domain_id') : $domain_id;
		$cond['Alias.publish'] = '1';
 		$cond['Alias.status'] = '0';
 		$cond['AliasBuy.status'] = '0';
 		$cond['0'] = array('AliasInvoice.validtill > now()');
 		$cond['Alias.domain_id'] = $domain_id;
 		$sid = $this->Alias->findAll($cond, array('Alias.id', 'Alias.name', 'AliasInvoice.id'));
		
 		if(!empty($sid['0']['AliasInvoice'])) return $sid;
 		else return false;
 		
	}
	
	/*	check 30 days trial period	||	TRUE if expired	*/
	function check_alias_date($domain_id=null) {

 		$domain_id = ($domain_id==null) ? $this->Session->read('domain_id') : $domain_id;
 		$cond['conditions']['AliasReminder.domain_id'] = $domain_id;
 		$cond['conditions']['AliasReminder.type'] = 'expiration day';
 		if($this->AliasReminder->find('count', $cond)) return true;
 		else return false;
 		
 		/*$starting_date = $this->Alias->field('created', array('Alias.domain_id' => $domain_id, 'Alias.publish' => '1', 'Alias.status' => '0'));
 		
 		if($starting_date) {
 			
	 		$starting_date = date('Y-m-d', strtotime($starting_date));
			$end_date = date('Y-m-d');
			$diff = $this->date_diff('d', $starting_date, $end_date, false);
			
			if($diff && $diff > ALIAS_TRIAL_PERIOD) return false;
			else return true;
 		
 		} else return true;*/
 		
	}
	
	function generateRandomNumber($limit) {
		//$abc = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
		$abc = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
		$tmp = '';
		for($i=0; $i<$limit; $i++)
			$tmp .= $abc[rand(0,9)]; 
		return $tmp; 
		//$tmp .= $abc[rand(0,35)]; 
	}
	
	function checkAccess($type=null) {
		
		if($type == 'advertiser') {
			
			if($this->Session->read('user_type') == 'advertiser') return true;
			else $this->showLogin();
			
		} else if($type == 'developer') {
			
			if($this->Session->read('user_type') == 'developer') return true;
			else $this->showLogin();
			
		} else if($type == 'bulksms') {
			
			if($this->Session->read('user_type') == 'bulksms') return true;
			else $this->showBulksmsLogin();
			
		} else {
		
			if($this->Session->check('user_id')) return true;
			else $this->showLogin();
		
		}
		
	}
	
	function isLogin($type=null) {
		
		if($type == 'advertiser') {
			
			if($this->Session->read('user_type') == 'advertiser') return true;
			else return false;
			
		} else if($type == 'user') {
			
			if($this->Session->read('user_type') == 'developer') return true;
			else return false;
			
		} else {
		
			if($this->Session->check('user_id')) return true;
			else return false;
		
		}
		
	}
	
	function showBulksmsLogin() {
		
		$this->redirect('/bulksms/login');
		exit;
		
	}
	
 	function showLogin() {
		
		$this->redirect('/users/login');
		exit;
		
	}
	
	function getPlanData($plan_id) {
		
		$data = '<a href="'.SERVER.'"><img src="'.SERVER.'/plans/image/'.$plan_id.'" /></a>';
		return $data;
		
	}
	
	function changeDomainStatus($id) {
		
		$this->Domain->id = $id;
		$status = $this->Domain->field('status');
		
		$status = ($status == '0') ? '1' : '0';
		
		$this->Domain->id = $id;
		$this->Domain->saveField('status', $status);
		
	}
	
 	function changeUerStatus($id) {
		
		$this->Domain->id = $id;
		$user_id = $this->Domain->field('user_id');
		
		$this->User->id = $user_id;
		$status = $this->User->field('status');
		
		$status = ($status == '0') ? '1' : '0';
		
		$this->User->id = $user_id;
		$this->User->saveField('status', $status);
		
	}
	
	function getMinBulkServer() {
		
		return 5;
		
		//TODO: Error on live server
		foreach($this->bulkServerList as $value) {
			
			$this->BulkSmsLogDetail->setSource('bulk_sms_log_detail_' . $value);
			$data[$value] = $this->BulkSmsLogDetail->find('count');
		
		}
		
		$flipped = array_flip($data);
		ksort($flipped);
		
		return $flipped['0'];
	}
	
	/*function getFeedback() {
		
		$condition['conditions']['publish'] = '1';
		$condition['fields'] = array('id', 'name', 'feedback');
		$condition['order'] = 'RAND()';
		$condition['limit'] = '3';
		$this->set('feedback', $this->Contact->find('all', $condition));
	
	}*/
	
	function getFeedback() {
		
		$condition['conditions']['publish'] = '1';
		$condition['conditions']['status'] = '0';
		$list = array_keys($this->Feedback->find('list', $condition));
		$limit = count($list);
		
		$tmp = array();
		for($i=0; $i<3; $i++) {
			$n = $list[rand(0, $limit-1)];
			if(!in_array($n, $tmp)) $tmp[] = $n;
			else $i--; 
		}
		
		unset($condition);
		$condition['conditions']['Feedback.id'] = $tmp; 
		$condition['fields'] = array('id', 'domain_id', 'feedback');
		$this->set('feedback', $this->Feedback->find('all', $condition));
		$this->set('domain_list', $this->Domain->find('list'));
		
	}
	
	function getSecretKey($domain_name) {

		$id = $this->Session->read('user_id');
		return md5( md5( $domain_name . SALT . $id ) . SALT . $id );
		
	}
	
	function checkSecretKey($key) {

		//$this->Domain->unBindAll();
		$cond['conditions']['User.status'] = '0';
		$cond['conditions']['0'] = array('Domain.secret_key = "'. $key .'" or concat(User.name, User.password) = "' . $key . '"');
		//$cond['conditions']['Domain.secret_key'] = $key;
		$data = $this->Domain->find('all', $cond);
		if(count($data) > 0) {
		
			$id = $data['0']['Domain']['user_id'];
			$domain_name = $data['0']['Domain']['name'];

			return array($data['0']['Domain']['id'], $data['0']['Domain']['name'], 
							$data['0']['Domain']['plan_id'], $data['0']['Domain']['server'], 
								$data['0']['Domain']['ip'], $data['0']['Domain']['category_id'],
									$data['0']['User']['verify'], $data['0']['Plan']['sms'],
										$data['0']['User']['id'], $data['0']['Domain']['widget']);
			
			
		} else return false;

	}
	
	/**
	 * Verify if the secret key is valid or not
	 * @param $key
	 * @return TRUE || FALSE
	 */
	function verifySecretKey($key) {

		$cond['conditions']['Domain.status'] = '0';
		$cond['conditions']['0'] = array('Domain.secret_key = "'. $key .'" or concat(User.name, User.password) = "' . $key . '"');
		$data = $this->Domain->find('all', $cond);
		if(count($data) > 0) return true;
		else return false;
	
	}
	
	// crawl to user domain to find the image
 	function findImageOnWebsite($plan_id, $domain_name) { 

 		if(trim($plan_id) == '') return false;
 		
 		else if($plan_id == '1') return true;
 		
 		else {
 		
 			$data = $this->getPlanData($plan_id);
			App::import('Vendor', 'httprequest');
			$r = new HTTPRequest('http://www.' . $domain_name);
			$content = $r->DownloadToString();
	
 			if(strstr($content, $data)) return true;
 			else return false;
 			
 		}
 		
 	}
 	
 	function checkNumber($number) {
 		
 		if(!empty($number) && strlen($number) == '10') {
 			
 			$condition['conditions']['name'] = substr($number, 0, 4);
 			if($this->ValidNumber->find('count', $condition)) return true;
 			else return false;
 			
 		} else return false;
 			
 	}
 	
 	function checkDomain($domain) {
 		
 		if(!empty($domain)) {
 			
 			if(strpos($domain, 'mail2') === 0) return false;
 			if(strpos($domain, 'yahoo.') === 0) return false;
			if(strpos($domain, '.tk') !== false) return false;
 			if(strpos($domain, '.co.cc') !== false) return false;
 			if(strpos($domain, 'live.') === 0) return false;
 			
 			$condition['conditions']['name'] = $domain;
 			if($this->BlacklistDomain->find('count', $condition)) return false;
 			else return true;
 			
 		} else return false;
 			
 	}

	function checkSubdomain($domain) {
		
		if(!empty($domain)) {
			
			$data = explode('.', $domain);
			
			if(count($data) == 2) {
				ereg("(www\.)?([^\.]*)\.([^\.]*)\.([^\.]{2,5})", $domain, $regs);
	 			if(isset($regs) && $regs[$count] != '') return false;
	 			else return true;
			
			} else if(count($data) == 3) {
				if(strpos($domain, '.org.in') || strpos($domain, '.edu.in') || strpos($domain, '.co.in') || strpos($domain, '.gov.in') || strpos($domain, '.ac.in')) return true;
				else return false;
			
			} else return false;
 			
 			
		} return false;
	}
 	
 	function checkDomainIP($domain) {
 	
 		if(!empty($domain)) {
 			
 			$ip = gethostbyname($domain);
 			return ($ip != $domain) ? true : false;
 			
 		}
 		
 	}
 	
 	function getDomainIP($domain) {
 	
 		if(!empty($domain)) {
 			
 			return gethostbyname($domain);
 			
 		}
 		
 	}
 	
 	function validDomain($domain) {

 		if(!empty($domain)) {
 			
 			App::import('Vendor', 'httprequest');
			$r = new HTTPRequest('http://www.' . $domain);
			$content = $r->DownloadToString();
 			
 			if(!empty($content)) return true;
 			else return false;
 			
 		} else return false;
 			
 	}
 	
 	function getDomainID($domain_name) {

 		if(!empty($domain_name)) {
 			
 			$c['conditions']['status'] = 0;
 			$c['conditions']['name'] = $domain_name;
 			$this->Domain->recursive = -1;
 			$data = $this->Domain->find('all', $c);
 			if(!empty($data)) {
 				
 				return $data['0']['Domain']['id'];
 				
 			} else return 0;
 			
 		} else return 0;
 		
 	}
 	
 	
 	function getRemoteAddr() {
 		return $_SERVER['REMOTE_ADDR'];
 		//http://www.phpclasses.org/package/2910-PHP-Analyze-remote-IP-Proxy-detection-Blacklist-check.html
 	}

	function getRealIpAddr() {
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	function traceroute ($remote_addr, $original, $domain_id) {

		//$remote_addr = '85.25.110.204';
        //$original = '62.75.203.70';

	    exec("traceroute $remote_addr", $data_1);
		exec("traceroute $original", $data_2);

		//print_r($data_1);
		//print_r($data_2);
		
		// get second last and third last row
		$rows_1[] = $data_1[count($data_1)-2]; 
		$rows_1[] = $data_1[count($data_1)-3]; 
		
		$rows_2[] = $data_2[count($data_2)-2]; 
		$rows_2[] = $data_2[count($data_2)-3]; 
		
		//print_r($rows_1);
		//print_r($rows_2);
		
		$found = true;

		for($i=0; $i<2; $i++) {
			$rows_1_data = explode('  ', $rows_1[$i]);
			$rows_2_data = explode('  ', $rows_2[$i]);
			
			$rows_1_data = explode(' ', $rows_1_data[1]);
			$rows_2_data = explode(' ', $rows_2_data[1]);
			
			//print_r($rows_1_data);
			//print_r($rows_2_data);
			
			if(trim($rows_1_data[1]) == '*' || trim($rows_1_data[1]) != trim($rows_2_data[1])) {
				//echo trim($rows_1_data[1]) .' :: '. trim($rows_2_data[1]);
				//echo PHP_EOL;
			
				$found = false;

			}
		}
		
		if($found) {
			$value['domain_id'] = $domain_id;
			$value['ip'] = ip2long($remote_addr);
			$this->DomainIp->save($value);
			return true;
		} else {
			return false;
		}

	}


	function dig($ip, $domain) {

		//$ip = "85.25.110.204";
		//$domain = "freehosting.com";
	    $found = false;
        $hostname = gethostbyaddr($ip);
	    $cmd = "dig $hostname";
        //echo PHP_EOL;
        exec($cmd, $raw_data);
        //print_r($raw_data);
        $raw_data = implode(' ', $raw_data);
        $regex = '/' .
                '^;(.*?)' .
                ';; QUESTION SECTION\:(.*?)' .
                '(;; ANSWER SECTION\:(.*?))?' .
                '(;; AUTHORITY SECTION\:(.*?))?' .
                '(;; ADDITIONAL SECTION\:(.*?))?' .
                '(;;.*)' .
                '/ims';

        $regex = '/;; AUTHORITY SECTION\:(.*?);;/ims';

        if (preg_match($regex, $raw_data, $matches)) {
                //print_r($matches);
                /* authority section */
                $temp = $matches;
                if ($temp) {
                        //$temp = explode("\n", $temp);
                        if (count($temp)) {
                                foreach($temp as $line) {
                                        $result[] = $line;
                                        if(strpos($line, $domain) !== false) { $found = true; break; }
                                }
                        }
                }
                //print_r($result);
                if($found) return true;
                else return false;
        }
	}

 	function charLimit($str) {
 		
 		$str = html_entity_decode($str, ENT_QUOTES);
 		if(strlen($str) > MESSAGE_CHAR_LIMIT)
			return substr($str, 0, MESSAGE_CHAR_LIMIT);
		else return $str;
 		
 	}
 	
 	function checkIfEmail($email) {

		//$patternEmail = '#^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$#i';
		$patternEmail = '/^([a-zA-Z0-9_.-]+)+@([a-zA-Z0-9_-]+.[a-zA-Z0-9]+)+(\.(ac|ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|asia|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cat|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|info|int|io|iq|ir|is|it|je|jm|jo|jobs|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mobi|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tel|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|travel|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$)/';
		if(!preg_match($patternEmail, $email)) return false;
		else return true;
 		
 	}
 	
 	function checkIfLink($orig_url) {
		
		$url = htmlspecialchars_decode($orig_url);
		
		// Make sure we didn't pick up an email address
		//if (preg_match('#^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$#i', $url)) $url = "mailto:".$url; //continue;
		
		// Remove surrounding punctuation
		$url = trim($url, '.?!,;:\'"`([<');
		
		// Remove surrounding parens and the like
		preg_match('/[)\]>]+$/', $url, $trailing);
		if (isset($trailing[0])) {
			preg_match_all('/[(\[<]/', $url, $opened);
			preg_match_all('/[)\]>]/', $url, $closed);
			$unopened = count($closed[0]) - count($opened[0]);
		    
			// Make sure not to take off more closing parens than there are at the end
			$unopened = ($unopened > strlen($trailing[0])) ? strlen($trailing[0]):$unopened;
		    
			$url = ($unopened > 0) ? substr($url, 0, $unopened * -1):$url;
		}
		
		// Remove trailing punctuation again (in case there were some inside parens)
		$url = rtrim($url, '.?!,;:\'"`');
		
		// Make sure we didn't capture part of the next sentence
		preg_match('#((?:[^.\s/]+\.)+)(museum|travel|[a-z]{2,4})#i', $url, $url_parts);
		
		//mosh
		if(!isset($url_parts[2]) && !isset($url_parts[1])) return false;
		
		// Were the parts capitalized any?
		$last_part = (strtolower($url_parts[2]) !== $url_parts[2]) ? true:false;
		$prev_part = (strtolower($url_parts[1]) !== $url_parts[1]) ? true:false;

		// If the first part wasn't cap'd but the last part was, we captured too much
		if ((!$prev_part && $last_part)) {
			$url = substr($url, 0 , strpos($url, '.'.$url_parts['2'], 0));
		}

		// Capture the new TLD
		preg_match('#((?:[^.\s/]+\.)+)(museum|travel|[a-z]{2,4})#i', $url, $url_parts);
		
		$tlds = array('ac', 'ad', 'ae', 'aero', 'af', 'ag', 'ai', 'al', 'am', 'an', 'ao', 'aq', 'ar', 'arpa', 'as', 'asia', 'at', 'au', 'aw', 'ax', 'az', 'ba', 'bb', 'bd', 'be', 'bf', 'bg', 'bh', 'bi', 'biz', 'bj', 'bm', 'bn', 'bo', 'br', 'bs', 'bt', 'bv', 'bw', 'by', 'bz', 'ca', 'cat', 'cc', 'cd', 'cf', 'cg', 'ch', 'ci', 'ck', 'cl', 'cm', 'cn', 'co', 'com', 'coop', 'cr', 'cu', 'cv', 'cx', 'cy', 'cz', 'de', 'dj', 'dk', 'dm', 'do', 'dz', 'ec', 'edu', 'ee', 'eg', 'er', 'es', 'et', 'eu', 'fi', 'fj', 'fk', 'fm', 'fo', 'fr', 'ga', 'gb', 'gd', 'ge', 'gf', 'gg', 'gh', 'gi', 'gl', 'gm', 'gn', 'gov', 'gp', 'gq', 'gr', 'gs', 'gt', 'gu', 'gw', 'gy', 'hk', 'hm', 'hn', 'hr', 'ht', 'hu', 'id', 'ie', 'il', 'im', 'in', 'info', 'int', 'io', 'iq', 'ir', 'is', 'it', 'je', 'jm', 'jo', 'jobs', 'jp', 'ke', 'kg', 'kh', 'ki', 'km', 'kn', 'kp', 'kr', 'kw', 'ky', 'kz', 'la', 'lb', 'lc', 'li', 'lk', 'lr', 'ls', 'lt', 'lu', 'lv', 'ly', 'ma', 'mc', 'md', 'me', 'mg', 'mh', 'mil', 'mk', 'ml', 'mm', 'mn', 'mo', 'mobi', 'mp', 'mq', 'mr', 'ms', 'mt', 'mu', 'museum', 'mv', 'mw', 'mx', 'my', 'mz', 'na', 'name', 'nc', 'ne', 'net', 'nf', 'ng', 'ni', 'nl', 'no', 'np', 'nr', 'nu', 'nz', 'om', 'org', 'pa', 'pe', 'pf', 'pg', 'ph', 'pk', 'pl', 'pm', 'pn', 'pr', 'pro', 'ps', 'pt', 'pw', 'py', 'qa', 're', 'ro', 'rs', 'ru', 'rw', 'sa', 'sb', 'sc', 'sd', 'se', 'sg', 'sh', 'si', 'sj', 'sk', 'sl', 'sm', 'sn', 'so', 'sr', 'st', 'su', 'sv', 'sy', 'sz', 'tc', 'td', 'tel', 'tf', 'tg', 'th', 'tj', 'tk', 'tl', 'tm', 'tn', 'to', 'tp', 'tr', 'travel', 'tt', 'tv', 'tw', 'tz', 'ua', 'ug', 'uk', 'us', 'uy', 'uz', 'va', 'vc', 've', 'vg', 'vi', 'vn', 'vu', 'wf', 'ws', 'ye', 'yt', 'yu', 'za', 'zm', 'zw');
		
		if (!in_array($url_parts[2], $tlds)) return false; else	return true;
		
	}
	
	/*	check if sender name is valid - BULK API	*/
 	function checkBulkSenderidName($senderid, $user_id) {
		
		$c['BulkSenderid.name'] = $senderid;
		$c['BulkSenderid.bulk_user_id'] = $user_id;
		$c['BulkSenderid.status'] = '0';
		$c['BulkSenderid.publish'] = '1';
		$this->BulkSenderid->unBindAll();
		$data = $this->BulkSenderid->findAll($c); 
		
		if(count($data) > 0) return $data['0']['BulkSenderid']['id'];
		return false;
		
	}
	
	/*	check if tag name is valid - BULK API	*/
	function checkBulkTagName($tag, $user_id) {
		
		$c['BulkSmsTag.name'] = $tag;
		$c['BulkSmsTag.bulk_user_id'] = $user_id;
		$c['BulkSmsTag.status'] = '0';
		$this->BulkSmsTag->unBindAll();
		$data = $this->BulkSmsTag->findAll($c); 
		
		if(count($data) > 0) return $data['0']['BulkSmsTag']['id'];
		return false;
		
	}
	
	/*	check if group name is valid - BULK API	*/
	function checkBulkGroupName($groupname, $user_id) {
		
		$c['BulkGroup.name'] = $groupname;
		$c['BulkGroup.bulk_user_id'] = $user_id;
		$c['BulkGroup.status'] = '0';
		$this->BulkGroup->unBindAll();
		$data = $this->BulkGroup->findAll($c); 
		
		if(count($data) > 0) return $data['0']['BulkGroup']['id'];
		return false;
		
	}
	
	/*	check if secret key is valid - BULK API	*/
	function checkBulkSecretKey($secret_key) {
		
		$c['BulkAccount.secret_key'] = $secret_key;
		$c['BulkAccount.status'] = '0';
		//$this->BulkAccount->unBindAll();
		$data = $this->BulkAccount->find('all', array('conditions' => $c));
		
		if(count($data) > 0) return array('user_id' => $data['0']['BulkAccount']['bulk_user_id'],
											'validity' => $data['0']['BulkAccountRecharge'][count($data['0']['BulkAccountRecharge'])-1]['validtill'],
												'server' => $data['0']['BulkAccount']['server']);
		else return false;
		
	}
	
	/*	Check Bulk Account Validity	TRUE - NOT EXPIRED	*/
	function checkBulkValidity($validity=null) {
		
		if($validity) {
			if(NOW > $validity) return false;
			else return true;	
		} else return false;
			
	}
	
 	/*	Check if groupid belongs to logged in user	*/
	function checkBulkSenderId($sender_id) {
		
		$this->BulkSenderid->id = $sender_id;
	 	if($this->BulkSenderid->field('bulk_user_id') != $this->Session->read('user_id')) return false;
	 	else return true;
		
	}
	
	/*	Check if groupid belongs to logged in user	*/
	function checkBulkGroupId($group_id) {
		
		$this->BulkGroup->id = $group_id;
	 	if($this->BulkGroup->field('bulk_user_id') != $this->Session->read('user_id')) return false;
	 	else return true;
		
	}
	
 	/*	Check if tagid belongs to logged in user	*/
	function checkBulkTagId($tag_id) {
		
		$this->BulkSmsTag->id = $tag_id;
	 	if($this->BulkSmsTag->field('bulk_user_id') != $this->Session->read('user_id')) return false;
	 	else return true;
		
	}
	
 	/*	Return Total SMS Consume By a SMS	*/
 	function sms_count($message) {
 		
 		$d = strlen(html_entity_decode($message, ENT_QUOTES))/ONE_SMS_CHARS;
 		if(is_float($d)) $extra = 1; else $extra = 0;
		return round(floor($d)) + $extra;	
 	
 	}
 
 	/*	Show Error When calling from API	*/
 	function _throwError($error, $response_format) {
 		
 		Configure::write('debug', 0);
 		
 		$op = '<?xml version="1.0" encoding="UTF-8"?><response>' . $error .'</response>';
	 	
 		if($response_format == 'json') {

 			App::import('vendor', 'xml2json', array('file' => 'xml2json/xml2json.php'));
	 		$jsonContents = xml2json::transformXmlStringToJson($op);
			echo($jsonContents);
 		
 		} else {
 		
			//header("content-type: text/xml");
			echo $op;
 		
 		}

 		exit;
 	}
 	
 	/*	Send SMS To The Bulk Group and Numbers
 	 * 	Parameteres ->
 	 * 	1. Group ID
 	 * 	2. Sender ID
 	 * 	3. Message
 	 * 	4. SMS LOG ID
 	 *  5. User ID
 	 *  6. Numbers
 	 *  7. API Call - Boolean
 	 *  8. SMS Vendor ID
 	 * */
 	function _sendBulkMessage($bulk_group_id, $bulk_senderid_id, $message, $bulk_sms_log_id, $bulk_user_id, $number=null, $api=false, $sms_vendor_id) {
 		 		
		$this->write($bulk_user_id.' :: '.date('Y-m-d H:i:s').' :: START');
 		
 		set_time_limit(0);
 		
 		if(empty($this->sms_vendor_details)) $this->getSmsVendorDetails();
		$sms_vendor = $this->sms_vendor_details['sms_vendor_'.$sms_vendor_id];
				
 		//get account details
 		unset($cond);
 		$cond['BulkAccount.bulk_user_id'] = $bulk_user_id;
 		$cond['BulkAccount.status'] = '0';
 		$fields = array('BulkAccount.id', 'BulkAccount.quantity', 'BulkAccount.amount', 'BulkAccount.server');
 		$this->BulkAccount->recursive = -1;
 		$bulk_account = $this->BulkAccount->findAll($cond, $fields);
 		
 		/*unset($cond);
 		$cond['BulkAccountRecharge.bulk_account_id'] = $bulk_account['0']['BulkAccount']['id'];
 		$cond['BulkAccountRecharge.status'] = '0';
 		$bulk_account_recharge = $this->BulkAccountRecharge->findAll($cond);*/
 		
 		$sms_count = $this->sms_count($message);
 		$quantity = $bulk_account['0']['BulkAccount']['quantity'];
 		$amount = $bulk_account['0']['BulkAccount']['amount'];
 		$server = $bulk_account['0']['BulkAccount']['server'];
 		
 		// Since Amount and Quantity are topped up on every Recharge Cost per SMS varies significantly
 		// So this is the best possible way for even N number of Recharges. 
 		$costpersms = $amount/$quantity;
 		
 		//$costpersms = $bulk_account_recharge['0']['BulkAccountRecharge']['costpersms'];
 		//$validtill = $bulk_account_recharge['0']['BulkAccountRecharge']['validtill'];
 		
 		$count = 0;
 		
 		// sender id (optional)
 		if(!empty($bulk_senderid_id)) {
	 		$this->BulkSenderid->id = $bulk_senderid_id;
	 		$sender_id = $this->BulkSenderid->field('name');
 		} else {
 			$sender_id = SMS_SENDER_ID;
 			$bulk_senderid_id = '0';
 		}
 		
 		
 		// Get contacts from addressbook
 		/*if(!empty($bulk_group_id)) {
 			
	 		$d['BulkAddressbook.status'] = '0';
	 		$d['BulkAddressbook.bulk_group_id'] = $bulk_group_id;
	 		$addressbook = $this->BulkAddressbook->findAll($d);
	 		
	 		$number_count = count($addressbook);
 			for($i=0; $i<$number_count; $i++) {
				if($i != 0 && $i % NUMBER_LIMIT_IN_API == 0) { 
					$s[] = $addressbook[$i]['BulkAddressbook']['mobile'];
					$count++;
					 
					$this->_saveBulkMessage($s, $bulk_sms_log_id, $sender_id, $message, $bulk_user_id);
					unset($s);
					
				} else {
					$s[] = $addressbook[$i]['BulkAddressbook']['mobile'];
					$count++;
				}
			}
			
			if(isset($s) && count($s) > 0) {
				$this->_saveBulkMessage($s, $bulk_sms_log_id, $sender_id, $message, $bulk_user_id);
				unset($s);
			}

 		}*/
 		
		$this->write($bulk_user_id.' :: '.date('Y-m-d H:i:s').' :: Looping Numbers');

 		// Mobile numbers entered in textarea
 		if(isset($number) && !empty($number)) {
 			
 			$number_count = count($number);
 			
	 		//send sms in group of 200
 			for($i=0; $i<$number_count; $i++) {
				if($i != 0 && $i % NUMBER_LIMIT_IN_API == 0) {
					
					//limit the number of sms if number of outgoing sms is greater then sms remaining
					if(($sms_count * $count) >= $quantity) break;
					
					$s[] = trim($number[$i]);
					$count++;
					
					$this->write($bulk_user_id.' :: '.date('Y-m-d H:i:s').' :: Sending Chunk '.count($s).' of '.$count);
					$response_data[] = $this->_saveBulkMessage($s, $bulk_sms_log_id, $sender_id, $message, $bulk_user_id, $api, $server, $sms_vendor);
					$this->write($bulk_user_id.' :: '.date('Y-m-d H:i:s').' :: Recieving response '.count($s).' of '.$count);

					unset($s);
					
				} else {
					
					//limit the number of sms if number of outgoing sms is greater then sms remaining
					if(($sms_count * $count) >= $quantity) break;
					
					$s[] = trim($number[$i]);
					$count++;
				}
			}
			
			if(isset($s) && count($s) > 0) {

				$this->write($bulk_user_id.' :: '.date('Y-m-d H:i:s').' :: Sending Chunk '.count($s).' of '.$count);
				$response_data[] = $this->_saveBulkMessage($s, $bulk_sms_log_id, $sender_id, $message, $bulk_user_id, $api, $server, $sms_vendor);
				$this->write($bulk_user_id.' :: '.date('Y-m-d H:i:s').' :: Receiving response '.count($s).' of '.$count);

				unset($s);
			}
 			
 		}
		
		$this->write($bulk_user_id.' :: '.date('Y-m-d H:i:s').' :: UPDATE AMOUNT');

 		//update remaining amount and quantity
 		$rem = $quantity - ($sms_count * $count);
 		$rem_amount = $amount - ($sms_count * $count * $costpersms);
 		
 		$this->BulkAccount->updateAll(
 			array('BulkAccount.quantity' => $rem, 'BulkAccount.amount' => $rem_amount),
 			array('BulkAccount.id' => $bulk_account['0']['BulkAccount']['id'])
 		);
		
		$this->write($bulk_user_id.' :: '.date('Y-m-d H:i:s').' :: END');
		$this->write("\n");

 		if($api) return $response_data;
 		
 	}
	
 	// Send And Save Bulk SMS
 	function _saveBulkMessage($data, $bulk_sms_log_id, $sender_id, $message, $bulk_user_id, $api, $server, $sms_vendor) {

 		$sms_username = $sms_vendor['sms_username'];
 		$sms_password = $sms_vendor['sms_password'];
		
 		$response_key = '0';
 		if(!IS_TEST_SERVER) {
 			//$url = LONG_SMS.'?username='.$sms_username.'&pass='.$sms_password.'&senderid='.$sender_id.'&message='.urlencode(html_entity_decode($message, ENT_QUOTES)).'&dest_mobileno='.implode(',', $data).'&response=Y';
 			$url = $sms_vendor['sms_url'].'?'.$sms_vendor['sms_url_username'].'='.$sms_vendor['sms_username'].'&'.
	 						$sms_vendor['sms_url_password'].'='.$sms_vendor['sms_password'].'&'.
	 						$sms_vendor['sms_senderid'].'='.$sender_id.'&'.
	 						$sms_vendor['sms_message'].'='.urlencode(html_entity_decode($message, ENT_QUOTES)).'&'.
	 						$sms_vendor['sms_recipient'].'='.implode(',', $data).'&'.
	 						$sms_vendor['sms_response'];
			
 			$response_key = trim(file_get_contents($url));
 			
 			// Only for smsarchariya - 2012-02-26
 			if($sms_vendor['id'] == 2) {
	 			$response_key = $this->br2sep($response_key, '##', $sms_vendor['sms_delivery_report_seperator']);
	 			$response_key = explode('##', $response_key);
 			}
 		}
		
 		$data_count = count($data);
 		
 		// Only for pinnacle - 2012-02-26
 		if($sms_vendor['id'] == 1) {
 			
 			for($i=0; $i<$data_count;$i++) {
 				$random = date('Y-m-d') .'_'. substr(number_format(time() * rand(),0,'',''),0,10);
 				$d['key'] = $response_key;
 				$d['value'] = $random;
 				$d['mobile'] = $data[$i];
 				$savedata[] = $d;
 				$dummy_response_key[] = $random;
 			}
 			$this->BulkSmsResponsePinnacle->saveAll($savedata);
 			unset($savedata);
 			
 			$response_key = $dummy_response_key;
 		}
 		
 		for($i=0; $i<$data_count;$i++) {
 			$rid = !IS_TEST_SERVER ? trim(strip_tags($response_key[$i])) : 0;
 			//$rid = trim(strip_tags($response_key[$i]));
	 		$s['mobile'] = $data[$i];
	 		$s['bulk_sms_log_id'] = $bulk_sms_log_id;
	 		$s['response_key'] = $rid;
	 		$s['response_status'] = UNDELIVERED;
			$savedata[] = $s;
			unset($s);
			
			// For response
			if($api) $response_data[] = '<mobile>'.$data[$i].'</mobile><rid>'.$rid.'</rid>';
 		}
 		//echo $server;pr($savedata);exit;
 		// Save all data

 		$this->BulkSmsLogDetail->create();
		$this->BulkSmsLogDetail->setSource('bulk_sms_log_detail_' . $server);
 		$this->BulkSmsLogDetail->saveAll($savedata);
 		unset($savedata);

 		if($api) return $response_data;

 	}
 	
 	function br2sep($string, $sep, $regex) {
 		//return preg_replace('/\<\/?br(\s*)?\/?\>/i', $sep, $string);
 		return preg_replace($regex, $sep, $string);
 	}
 	
 	/*	Get Bulk SMS Server table 	*/
 	function _getBulkServer($bulk_user_id=null) {
 		
 		$cond['conditions']['BulkAccount.status'] = 0;
 		$cond['conditions']['BulkAccount.bulk_user_id'] = !empty($bulk_user_id) ? $bulk_user_id : $this->Session->read('user_id');
 		$cond['fields'] = array('BulkAccount.id', 'BulkAccount.server');
 		$this->BulkAccount->recursive = -1;
 		$data = $this->BulkAccount->find('all', $cond);
 		return $data['0']['BulkAccount']['server'];
 	
 	}
 	
 	/*	Get Bulk SMS Secret Key	*/
 	function _getBulkSecretKey($bulk_user_id=null) {
 		
 		$cond['conditions']['BulkAccount.status'] = 0;
 		$cond['conditions']['BulkAccount.bulk_user_id'] = !empty($bulk_user_id) ? $bulk_user_id : $this->Session->read('user_id');
 		$cond['fields'] = array('BulkAccount.id', 'BulkAccount.secret_key');
 		$this->BulkAccount->recursive = -1;
 		$data = $this->BulkAccount->find('all', $cond);
 		return $data['0']['BulkAccount']['secret_key'];
 	
 	}
 	
 	/*	send email for bulk registration (when payment is done)	*/
 	function _sendBulkRegistrationEmail($url, $email) {
 	
 		if(!empty($url) && !empty($email)) {
 		
	 		$subject = 'Premium User Account Registration and Activation';
			$message = 'Dear User,'.
						SEPERATOR . SEPERATOR .
						'Thank you for giving us the opportunity to serve you. Kindly remit your details and register by using the link below.'.
						SEPERATOR .
						$url .
						SEPERATOR . SEPERATOR .
						'The link is valid for one time registration. Upon submission of your details, the above link will be deactivated.'.
						SEPERATOR . SEPERATOR .
						'Please do not hesitate to contact us at support@freesmsapi.com if you have any issues during registration.'.
						SEPERATOR . SEPERATOR .
						'Best regards,' .
						SEPERATOR .
						'The Freesmsapi Team';
	 		
	 		$this->sendMail($email, $subject, $message);
	 		
 		}
 	}
 	
	// Send ONE at a time SMS to Free User
	function sendSMS($recipient, $message, $sender_id, $internal=false) {

		$response_key = '007-';

		if(!$internal) {
			$sms_vendor = $this->sms_vendor_details['sms_vendor_'.$this->sms_vendor_id];
		} else {
			$this->getSmsVendorDetails();
			$sms_vendor = $this->sms_vendor_details['sms_vendor_'.INTERNAL_SMS_VENDOR_ID];
		}

 		if(!IS_TEST_SERVER) {
 			
 			$url = $sms_vendor['sms_url'].'?'.$sms_vendor['sms_url_username'].'='.$sms_vendor['sms_username'].'&'.
				 							$sms_vendor['sms_url_password'].'='.$sms_vendor['sms_password'].'&'.
				 							$sms_vendor['sms_senderid'].'='.$sender_id.'&'.
				 							$sms_vendor['sms_message'].'='.urlencode(html_entity_decode($message, ENT_QUOTES)).'&'.
				 							$sms_vendor['sms_recipient'].'='.$recipient.'&'.$sms_vendor['sms_route'].'&'.
				 							$sms_vendor['sms_response'];
 			
 			$response_key = file_get_contents($url);
 			$response_key = trim(strip_tags($response_key));
 		}

		// from verification process
		if($internal) {
			$data['smsto'] = $recipient;
			$data['smsfrom'] = $sender_id;
			$data['smsbody'] = $message;
			$data['response_key'] = $response_key;
			$this->Smslog->create();
			$this->Smslog->save($data);
		}

		return $response_key;
	}
	
	function sendMailTmp($recipient, $subject, $message, $cc=null, $attachments=null) {

		if(!IS_TEST_SERVER) {

			$this->set('content_for_layout', $message);
			//$recipient = 'dilzfiesta@gmail.com';
			$this->Email->to = $recipient;
			$this->Email->subject = $subject;
			
			// Add Carbon Copy (if any)
			if($cc != null) {
				$this->Email->cc = $cc;
				$recipient = $recipient . ' - ' . $cc;
			}

			// Add Attachments (if any)
			if($attachments != null) {
				foreach($attachments as $file) $this->Email->attach($file);	
			}
			
			// Send Mail
			$result = $this->Email->send();
			
			if($result) {
				
				$data['mailto'] = $recipient;
				$data['mailfrom'] = INTERNAL_SENDER;
				$data['mailsubject'] = $subject;
				$data['mailbody'] = $message;
				$this->Maillog->create();
				$this->Maillog->save($data);
				
			}
		}
		return true;
	}
	
	/*	Send Mail	*/
	function sendMail($recipient, $subject, $message, $cc=null, $attachments=null) {

		if(!IS_TEST_SERVER) {

			$this->set('content_for_layout', $message);
			//$recipient = 'dilzfiesta@gmail.com';
			$this->Email->to = $recipient;
			$this->Email->subject = $subject;
			
			// For internal notifications only
			if($recipient == ADMIN_EMAIL) $this->Email->from = EXTERNAL_SENDER;
			else $this->Email->from = INTERNAL_SENDER;
			
			// Add Carbon Copy (if any)
			if($cc != null) {
				$this->Email->cc = $cc;
				$recipient = $recipient . ' - ' . $cc;
			}

			// Add Attachments (if any)
			if($attachments != null) {
				foreach($attachments as $file) $this->Email->attach($file);	
			}
			
			// Send Mail
			$result = $this->Email->send();
			
			if($result) {
				
				$data['mailto'] = $recipient;
				$data['mailfrom'] = INTERNAL_SENDER;
				$data['mailsubject'] = $subject;
				$data['mailbody'] = $message;
				$this->Maillog->create();
				$this->Maillog->save($data);
				
			}
		}
		
		return true;
		
	}

	/*	Send SMTP Mail	*/
	function sendSMTPMail($recipient, $subject, $message, $cc=null, $attachments=null) {

		if(!IS_TEST_SERVER) {

			//$this->Email->useSMTPAuth = true;
			
			$this->set('content_for_layout', $message);
			//$recipient = 'dilzfiesta@gmail.com';
			$this->Email->to = $recipient;
			$this->Email->subject = $subject;
			
			$this->Email->from = "mohtashim.shaikh@gmail.com"; 
			$this->Email->fromName = "Mohtashim Shaikh";

			// Add Carbon Copy (if any)
			if($cc != null) {
				$this->Email->cc = $cc;
				$recipient = $recipient . ' - ' . $cc;
			}

			// Add Attachments (if any)
			if($attachments != null) {
				foreach($attachments as $file) $this->Email->attach($file);	
			}
			
			// Send Mail
			$result = $this->Email->send();
			
			/*if($result) {
				
				$data['mailto'] = $recipient;
				$data['mailfrom'] = INTERNAL_SENDER;
				$data['mailsubject'] = $subject;
				$data['mailbody'] = $message;
				$this->Maillog->create();
				$this->Maillog->save($data);
				
			}*/
		}
		
		return true;
		
	}
	

	function sendMailOld($recipient, $subject, $message, $cc=null, $attachments=null) {
		
		# -=-=-=- PHP FROM VARIABLES (add as many as you would like) 
		
		$email = $recipient;
		
		# -=-=-=- MIME BOUNDARY 
		
		$mime_boundary = "----".ucfirst(TRADEMARK)."----".md5(time()); 
		
		# -=-=-=- MAIL HEADERS 
		
		$to = "$email"; 
		
		$headers = "From: ".INTERNAL_SENDER_NAME." <".INTERNAL_SENDER.">\n";
		$headers .= "Reply-To: ".INTERNAL_SENDER."\n";
		$headers .= "MIME-Version: 1.0\n"; 
		$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n"; 
		
		# -=-=-=- TEXT EMAIL PART 

		$message = "--$mime_boundary\n";
		$message .= "Content-Type: text/plain; charset=UTF-8\n"; 
		$message .= "Content-Transfer-Encoding: 8bit\n\n"; 
		$message .= $mess;

		# -=-=-=- HTML EMAIL PART 
		
		$message .= "--$mime_boundary\n"; 
		
		$message .= "Content-Type: text/html; charset=UTF-8\n"; 
		$message .= "Content-Transfer-Encoding: 8bit\n\n"; 
		$message .= "<html>\n"; 
		$message .= "<body style=\"font-family:Verdana, Verdana, Geneva, sans-serif; font-size:14px; color:#666666;\">\n";
		$message .= str_replace(SEPERATOR, SEPERATOR_HTML, $mess);
		$message .= "</body>\n"; 
		$message .= "</html>\n"; 
		
		# -=-=-=- FINAL BOUNDARY 
		
		$message .= "--$mime_boundary--\n\n"; 
		
		# -=-=-=- SEND MAIL 
		
		if(!IS_TEST_SERVER) {

			if(@mail( $to, $subject, $message, $headers )) {
				
				$data['mailto'] = $recipient;
				$data['mailfrom'] = INTERNAL_SENDER;
				$data['mailsubject'] = $subject;
				$data['mailbody'] = $message;
				$this->Maillog->create();
				$this->Maillog->save($data);
				
			}
		}
		
	}
	
	// Send mail
	/*function sendMail($recipient, $subject, $message) {
	
		//$recipient = $recipient;
		$sender = INTERNAL_SENDER;
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'FROM:'.$sender."\r\n";

		if(!IS_TEST_SERVER) @mail($recipient, $subject, $message, $headers);
		
		$data['mailto'] = $recipient;
		$data['mailfrom'] = $sender;
		$data['mailsubject'] = $subject;
		$data['mailbody'] = $message;
		$this->Maillog->create();
		$this->Maillog->save($data);
	
	
	}*/
	
	/*	$return = $this->authMail($sender, $sender, $recipient, $recipient, $subject, $message);
		print_r($return);
		
	/*	$server = "{imap.gmail.com:993/imap/ssl}INBOX";
		$username = INTERNAL_SENDER;
		$password = "_asdf1234@";
		$conn = imap_open ($server, $username, $password) or $this->write("can't connect: " . imap_last_error());
		$headers = @imap_headers($conn) or $this->write("Couldn't get emails");
		$numEmails = sizeof($headers); 
		$this->write("You have $numEmails in your mailbox");

		@imap_mail($recipient, $subject, $message, $headers);
	*/
	
	/*	Notify the admin for any events happening	*/
	function notifyAdmin($data) {
		
		if(!empty($data)) {
			if(isset($data['data']['domain_id']))
				if($this->Session->read('user_type') == 'developer')
					$data['data']['domain_name'] = $this->Session->read('domain_name');

			if(isset($data['data']['user_id']) || isset($data['data']['bulk_user_id']))
				if($this->Session->read('user_type') == 'bulksms')
					$data['data']['user_name'] = $this->Session->read('user_name');
					
			/*	Send Email	*/		
			$this->sendMail(ADMIN_EMAIL, 'New '.$data['type'], implode("\n\n", $data['data']));
		}
		
	}
	
 	/*	Sanitize the value of an array when usring array_walk()	*/
 	function sanitize(&$data) {
 		$data = filter_var(trim($data), FILTER_SANITIZE_STRING);	
 	}
 	
	function getTrademark() {
		
		return TRADEMARK;
		
	}
		
	
	/*	Check if user is suspended	*/
	function checkUserSuspended($user_id) {
		
		$conditions['conditions']['UserSuspended.user_id'] = $user_id;
		$conditions['conditions']['UserSuspended.status'] = 0;
		$conditions['fields'] = array('UserSuspendedReason.terminate');
		$data = $this->UserSuspended->find('all', $conditions);
		
		if(!empty($data)) {
			if($data['0']['UserSuspendedReason']['terminate']) 
				return 'Your account has been terminated, please contact the our Support Team for more information';
			else return 'Your account has been temporarily suspended, please contact the our Support Team for more information';
			
		} else return '';
	}

	function getMemoryUsage($state=false, $real_usage=false) {
		
		$mem = $this->convert(memory_get_usage($real_usage));
		
		if($state) $this->write($mem);
		else if(Configure::read('debug') == 2) echo $mem . '<br/>';	
		
	}
	
 	function write($data) {
		file_put_contents(TMP_FILE, $data . "\n", FILE_APPEND);
	}
	
 	function show_array($array) {
	    if (is_array($array) == 1){ 
	       foreach($array as $key => $value) {
	           if (is_array($value) == 1) {
	                $this->show_array($value);
	           } else {
	                file_put_contents(TMP_FILE, $key .' => '. $value . "\n", FILE_APPEND);
	           }
	       }
	    }
	    else{
	        return;
	    }
	}

}
?>
