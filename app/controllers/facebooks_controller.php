<?php
/*
 * Created on Apr 20, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class facebooksController extends AppController {
 	
 	var $uses = null;

	function connect() {}
	function receiver() {}
	function connected() {}
 	
 	function view() {
 		
 		App::import('vendor', 'Facebook', array('file' => 'facebook/facebook.php'));

		$appapikey = 'f056b8b1d196e8392199e8ba3acfadad';
		$appsecret = '0d1b22691586060b3647745ad41e5cd0';
		$facebook = new Facebook($appapikey, $appsecret);
		$user_id = $facebook->require_login();
		
		// Greet the currently logged-in user!
		echo "<p>Hello, <fb:name uid=\"$user_id\" useyou=\"false\" />!</p>";
		
		// Print out at most 25 of the logged-in user's friends,
		// using the friends.get API method
		echo "<p>Friends:";
		$friends = $facebook->api_client->friends_get();
		$friends = array_slice($friends, 0, 25);
		foreach ($friends as $friend) {
		  echo "<br>$friend";
		}
		echo "</p>";
		exit;
 		
 	}

	function login() {
 		
 		App::import('vendor', 'Facebook', array('file' => 'facebook/facebook.php'));

		$appapikey = 'f056b8b1d196e8392199e8ba3acfadad';
		$appsecret = '0d1b22691586060b3647745ad41e5cd0';
		$facebook = new Facebook($appapikey, $appsecret);
		$user_id = $facebook->require_login();
		
		$appcallbackurl = '/facebook/bye';
		
		//catch the exception that gets thrown if the cookie has an invalid session_key in it
		/*try
		{
			if(!$facebook->api_client->users_isAppAdded())
			{
				$facebook->redirect($facebook->get_add_url());
			}
		}
		catch (Exception $ex)
		{
			//this will clear cookies for your application and redirect them to a login prompt
			$facebook->set_user(null, null);
			$facebook->redirect($appcallbackurl);
		}*/
		return $facebook;
 		
 	}

	function index() {
 	
		set_time_limit(0);
		$facebook = $this->login();
	        //$friends = $facebook->api_client->users_getinfo($facebook->api_client->friends_get(), 'birthday'); pr($friends); exit;
        	$friends = $facebook->api_client->friends_get();

	        //$friends = array_slice($friends, 0, 110);
		//pr($friends);        exit;

		$default_pic = 'http://static.ak.fbcdn.net/pics/d_silhouette.gif';
        	$now = strtotime(date("jS") . date("F"));

		$with_birthday = array();
		$without_birthday = array();
		$person = array();
        
        	foreach($friends as $uid) {
			$username = $facebook->api_client->users_getinfo($uid, 'last_name, first_name');
			$birthday = $facebook->api_client->users_getinfo($uid, 'birthday, pic, email, contact_email');

			if(!is_array($username)) continue;

			$person[$uid]['name'] = $username['0']['first_name'].' '.$username['0']['last_name'];
			$person[$uid]['image'] = (empty($birthday['0']['pic'])) ? $default_pic : $birthday['0']['pic'];
			$person[$uid]['email'] = (empty($birthday['0']['email'])) ? $birthday['0']['contact_email'] : $birthday['0']['email'];
			
			$person[$uid]['day'] = date("jS", strtotime($birthday['0']['birthday']));
			$person[$uid]['month'] = date("F", strtotime($birthday['0']['birthday']));
			$person[$uid]['absolute_timestamp'] = strtotime($birthday['0']['birthday']);
			$person[$uid]['relative_timestamp'] = strtotime($person[$uid]['month'] . $person[$uid]['day']);
			
			if ($person[$uid]['relative_timestamp'] < $now)
			{
			  // birthday has already happened this year
			  $person[$uid]['year'] = date('Y', strtotime('+1 year'));
			}
			else
			{
			  // birthday still to come this year
			  $person[$uid]['year'] = date('Y');
			}
			
			$person[$uid]['relative_timestamp_with_year'] = strtotime($person[$uid]['month'] . $person[$uid]['day'] . $person[$uid]['year']);                
			if ($person[$uid]['absolute_timestamp'])
			{
				$with_birthday[] = $person[$uid];
			}
			else
			{
				$without_birthday[] = $person[$uid];
			}
				
			
			foreach ($with_birthday as $key => $row)
			{
				$relative_timestamp_with_year[$key] = $row['relative_timestamp_with_year'];
			}
				
			array_multisort($relative_timestamp_with_year, SORT_ASC, $with_birthday);
	        }
	        $this->set('person', $with_birthday);
	        
 		
 	}

 	
			
 	function bye() {
 		
 		echo 'Good bye.....';
 		exit;
 		
 	} 	
	
 }
?>