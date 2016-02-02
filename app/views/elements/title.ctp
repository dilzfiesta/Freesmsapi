<?
	/*	On every new deployment change NEW to OLD and set a NEW value	*/
	$old_cache_value = '232';
	$new_cache_value = '233';

	if(isset($_COOKIE['freesmsapi_cache'])) {
	
		if($_COOKIE['freesmsapi_cache'] != $new_cache_value) {
		
			Header("Cache-control: private, no-cache");
		    Header("Expires: Mon, 26 Jun 1997 05:00:00 GMT");
		    Header("Pragma: no-cache");
		    
		    setcookie('freesmsapi_cache', $old_cache_value, time()-60*60*24*30);
		    setcookie('freesmsapi_cache', $new_cache_value, time()+60*60*24*30);
		}
		
	} else {
	
		setcookie('freesmsapi_cache', $new_cache_value, time()+60*60*24*30);
	
	}
	
	/*	Set Title and Description for various pages*/
	
	if(strpos($_SERVER['REQUEST_URI'], 'registration')) {
		$title = 'Freesmsapi - Free Group Sms, Free Sms, Group sms, Sms Api, Bulk Sms, Freesmsapi.com';
		$description = 'Register with Freesmsapi.com and feel free to send bulk sms or group sms at very affordable and low cost. Freesmsapi also gives you facility to integrate Free Sms Api to your web site.';

	} else if(strpos($_SERVER['REQUEST_URI'], 'login')) {
		$title = 'Freesmsapi - Low cost Group SMS, Bulk sms , Freesmsapi.com, Free Sms, Free sms Api , Group Sms api';
		$description = 'Free Sms Api is a platform that allowing any developer for their web application and Desktop Application to send free sms, bulk sms or Free sms Api.';

	} else if(strpos($_SERVER['REQUEST_URI'], 'faqs')) {
		$title = 'Freesmsapi - Sms Api, Free Sms Api, Freesmsapi, Excel upload';
		$description = 'Free Sms Api is a platform which provides Free Promotional Sms to their users with their own identity at very affordable price.'; 
		
	} else if(strpos($_SERVER['REQUEST_URI'], 'forgotpassword')) {
		$title = 'Freesmsapi - Forgot Password';
		$description = '';
	
	} else if(strpos($_SERVER['REQUEST_URI'], 'tnc')) {
		$title = 'Freesmsapi - Terms of Service';
		$description = '';
	
	} else if(strpos($_SERVER['REQUEST_URI'], 'aboutus')) {
		$title = 'Freesmsapi - About Us';
		$description = 'WHO WE ARE? Free SMS API was founded in 2010 purely out of necessity to offer mobile features to the developers and business owners, looking to enhance the functionality of their websites and applications.';
	
	} else if(strpos($_SERVER['REQUEST_URI'], 'careers')) {
		$title = 'Freesmsapi - Exciting Career Opppotunity at Free SMS API';
		$description = 'Excellent Opportunity for LAMP Developer - At Freesmsapi we are always looking for the best of the best - talented people who are passionate about mobile technology and related services.';
	
	} else if(strpos($_SERVER['REQUEST_URI'], 'referralprogram')) {
		$title = 'Freesmsapi - Referral Program';
		$description = 'Referral Program help you earn more SMSs for your account at no extra cost.';
	
	} else if(strpos($_SERVER['REQUEST_URI'], 'features')) {
		$title = 'Freesmsapi - Features of Free SMS API';
		$description = '160 Chars - You can use all 160 characters with any advertisements attached. Free API - Free SMS API is available in different programming languages like PHP, Java, Perl, Python, Ruby, C, VB.NET and C# to integrate with your website or product.';
	
	} else if(strpos($_SERVER['REQUEST_URI'], 'services')) {
		$title = 'Freesmsapi - SMS API Integration Service';
		$description = 'We realize that our non technical business owner friends would require integration services to use our APIs with their websites or applications. So, we extend that support to our customers for a very nominal fee.';
	
	} else if(strpos($_SERVER['REQUEST_URI'], 'pricing')) {
		$title = 'Freesmsapi - Send Free SMS to any mobile, Free sms api, Freesmsapi.com, Free sms';
		$description = 'Ideal platform to integrate to Free Sms Api, Schedule Sms and Send Bulk Sms , Free Sms or Group Sms to any mobile.';
	
	} else if(strpos($_SERVER['REQUEST_URI'], 'contact')) {
		$title = 'Freesmsapi - Contact Us';
		$description = 'If you need to contact us for any reason, please fill in the form below. It will be automatically routed to the appropriate person';
	
	} else if(strpos($_SERVER['REQUEST_URI'], 'widgets')) {
		$title = 'Freesmsapi - Free SMS Widget';
		$description = 'Free SMS Widget to send Free SMS\'s in India, which is very easy to configure and implement in your existing website.';
	
	} else {
		$title = 'Freesmsapi - Free Sms Api, Free SMS, Bulk SMS, Freesmsapi, SMS Api, Freesmsapi.com';
		$description = 'Freesmsapi - Our vision is to create and offer useful and effective tools for developers and freelancers who are seeking to add value to their products in the mobile web space for their customers. Bulk SMS, FREE SMS API, SMS API, Low cost Group SMS, Free Group SMS, Send Free SMS to any mobile, Schedule SMS, Excel upload, CSV upload ';
	}
?>
<head>
	<title><?=$title?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
	<meta name="description" content="<?=$description?>" /> 
	<meta name="keywords" content="Freesmsapi, Freesmsapi.com, Bulk SMS, Free SMS Widget, FREE SMS API in PHP Java Perl Python Ruby C VB.NET and C#, SMS API, Low cost Group SMS, Free Group SMS, Send Free SMS to any mobile, Schedule SMS, Excel upload, CSV upload">
	<meta name="google-site-verification" content="KkESfBNcLdt7kLamVTUDINFpats6Dn5bzCqubaXmpuc" />
	<META name="y_key" content="456ffc83fd49bd39" >
	
	<link rel="shortcut icon" href="<?=SERVER?>img/favicon.ico" />
	
	<?php if(empty($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI']=='/') { ?>
	<link href="<?=SERVER?>css/index.css?<?=$new_cache_value?>" rel="stylesheet" type="text/css" />
	<? } else { ?>
	<link href="<?=SERVER?>css/main.css?<?=$new_cache_value?>" rel="stylesheet" type="text/css" />
	<? } ?>

	<?php if(IS_LIVE_SERVER) { ?>

	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-15555694-1']);
	  _gaq.push(['_setDomainName', '.freesmsapi.com']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
	
	<?php } ?>

</head>
