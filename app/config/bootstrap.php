<?php
/* SVN FILE: $Id$ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */
 
 // echo APP;
 // echo DS;

 define('LOGIN_ATTEMPT', 2);

 if(date('Y-m-d H:i:s') > '2011-09-11 08:00:00' && date('Y-m-d H:i:s') < '2011-09-11 10:00:00') {
 	define('FREE_SMS_SERVER_DOWN', true);
	define('BULK_SMS_SERVER_DOWN', true);
 } else {
	define('FREE_SMS_SERVER_DOWN', false);
 	define('BULK_SMS_SERVER_DOWN', false);
 }
 define('UNAVAILABLE_MESSAGE', 'The server is down for maintenance, we hope to come back live soon to serve you better.');

 if(date('H') > '08' && date('H') < '21') {
 	define('NINE_TO_NINE_ACTIVATED', false);
 } else {
 	define('NINE_TO_NINE_ACTIVATED', true);
 }
 define('NINE_TO_NINE_ACTIVATED_MESSAGE', 'SMS Service is unavailable betweeen 9pm to 9am - TRAI');

 define('NOW', date('Y-m-d H:i:s'));
 define('STATICSERVER', 'static.freesmsapi.com');
 define('SERVERNAME', 'freesmsapi.com');
 
 if(IS_LIVE_SERVER) define('SERVER', 'http://www.freesmsapi.com/');
 else if(IS_STAGING_SERVER) define('SERVER', 'http://staging.freesmsapi.com/');
 else define('SERVER', 'http://dev.freesmsapi.com/');
 
 define('LIVE_IP', '78.46.212.120');
 
 define('DIRECPAY_SUCCESS_URL', "http://dev.freesmsapi.com/users/paymentresponse/1");
 define('DIRECPAY_FAILURE_URL', "http://dev.freesmsapi.com/users/paymentresponse/0");
 
 if(!IS_LIVE_SERVER) define('DIRECPAY_COLLABORATOR', 'TOML');
 else define('DIRECPAY_COLLABORATOR', 'DirecPay');
 
 define('SHOW_PAYMENT_MODULE', false);
 define('ONLY_DEVELOPER_SECTION', true);
 define('SMS_SENDER_ID', '090000');
 define('SHOW_SENDER_ID', false);
 define('BULK_SMS_SENDER_ID', 'fsmsapi');
 define('LINE_BREAK', ' - ');
 define('SEPERATOR', "\n");
 define('SEPERATOR_HTML', '<br/>');
 
 // Notice and IP Address Lock
 define('FREE_USER_NOTICE', false);
 define('BULK_USER_NOTICE', false);
 define('IP_LOCK', true);
 define('IP_ADDRESS_LIMIT', 1);
  
 define('INTERNAL_SMS_SENDER_ID', '090000');  //615565 (acharya)
 define('INTERNAL_SMS_VENDOR_ID', 2);
 
 define('DEFAULT_SMS_VENDOR_ID', 2);
 define('SENDER_ID_DEFAULT_SMS_VENDOR_ID', 2);
 define('BULK_ACCOUNT_EXPIRED', 'Account has expired, Kindly recharge your account to continue sending SMS\'s');
 define('BULK_SMS_CLI_DECIDER', 100);
 
 // Sender ID activation through API
 define('SENDER_ID_DEFAULT_SEND_URL', 'http://reseller.freesmsapi.com/api/senderid.php');
 define('SENDER_ID_DEFAULT_GET_URL', 'http://reseller.freesmsapi.com/api/getsenderids.php');
 define('PRICING_LINK', SERVER.'users/pricing');
 
 define('CAPTCHA_PRIVATE_KEY', '******');
 define('CAPTCHA_PUBLIC_KEY', '******');
 
 define('ADMIN_USERNAME', '******');
 define('ADMIN_PASSWORD1', '******');
 define('ADMIN_PASSWORD2', '******');
 define('ADMIN_TIMEOUT', 10 * 60);
 
 define('SENDER_ID_PATH', APP . 'files'. DS);
 define('SENDER_ID_FOLDER', 'sender_id' . DS);
 define('SENDER_ID_FILE', 'sender_id.jpg');
 define('PARENT_COMPANY', 'Gyrodev IT Services');
 
 define('BULK_SMS_RESPONSE_LIMIT', 90);
 define('BULK_SMS_QUERY_LIMIT', 2000);
 
 define('GREEN_COLOR', '#a1f8a1');
 
 define('SUBDOMAIN_LOCK', false);
 
 define('UNDELIVERED','PENDING');
 
 define('VERIFY_START_DATE', '2011-12-30 23:59:59');
 
 define('ALIAS_TRIAL_PERIOD', 30);
 define('MAX_FREE_ALIAS', 1);
 
 define('DEFAULT_PLAN_ID', 21);
 define('ADVERTISEMENT_CHAR_LIMIT', 40);
 
 define('MESSAGE_CHAR_LIMIT', 140);
 define('MESSAGE_MOBILE_CHAR_LIMIT', 145);
 
 define('MAX_RECIPIENT', 50);
 define('ONE_SMS_CHARS', 160);
 define('MAX_CHARS', 800);
 
 define('NUMBER_LIMIT_IN_API', 200);
 define('RID_LIMIT_IN_API', 100);
 
 define('WIDGET_SEPERATOR', '::');
 define('WIDGET_SMS_LIMIT_PER_SESSION', 5);
 
 define('DUMMY_MOBILE', "******");
 
 define('INTERNAL_SENDER', '******');
 define('EXTERNAL_SENDER', '******');
 define('ADMIN_EMAIL', 'admin@freesmsapi.com');
 define('INTERNAL_SENDER_NAME', 'Freesmsapi Support');
 define('SMS_FOOTER', 'Freesmsapi Team');
 define('FREE_SMS_FOOTER', "\n- Freesmsapi.com");
 
 define('SALT', 'asDF12#$qwER45^&');
 define('TRADEMARK', 'freesmsapi.com');
 
 define('SERVICE_TAX', 10.3);
 
 define('MAIL_FOOTER', 'We strive to offer variety of useful features and services to our customers. '.
 						'We will keep updating you about our new features as and when available.'.
 						SEPERATOR . SEPERATOR .
 						'Your feedback is extremely valuable to us. Please do not hesitate to contact us at '.INTERNAL_SENDER.' or '.
 						'simply fill up the feedback form on the website with your suggestions.'.
 						SEPERATOR . SEPERATOR .
 						'Did you know that Freesmsapi\'s self-service site includes useful'.
 						' articles that contain answers to almost all of your technical support and'.
 						' sales questions? Check out '.SERVER.'faqs and get your question answered'.
 						' right away.'.
 						SEPERATOR . SEPERATOR .
 						'Best regards,'.
 						SEPERATOR .
 						'The Freesmsapi Team');
 
 define('NEW_FEATURE_HEADER', 'As part of our on going efforts to offer excellent service, '.
 								'we are pleased to announce inclusion of a new feature to our SMS module.');
 
 define('NEW_FEATURE_FOOTER', 'Simply log on to freesmsapi or click on '.SERVER.'users/login '.
 								'to learn more about this feature.');
 
?>
