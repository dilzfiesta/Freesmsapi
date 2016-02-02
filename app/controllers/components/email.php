<?php
/**
* This is a component to send email from CakePHP using PHPMailer
* @link http://bakery.cakephp.org/articles/view/94
* @see http://bakery.cakephp.org/articles/view/94
*/

class EmailComponent
{
	/**
	* Send email using SMTP Auth by default.
	*/
	var $from = INTERNAL_SENDER;
	var $fromName = INTERNAL_SENDER_NAME;
	var $sitePrefix = TRADEMARK;

	var $smtpHostNames = "smtp.gmail.com";
	var $smtpPort = "465";
	var $smtpHost = 'mail.freesmsapi.com'; // for peeps with google apps for domain
	var $smtpDebug = 0; // enables SMTP debug information (for testing)
	var $smtpSecure = "ssl"; // sets the prefix to the servier

	var $useSMTPAuth = false;
	var $smtpUserName = '';
	var $smtpPassword = '';

	var $text_body = null;
	var $html_body = null;
	var $to = null;
	var $toName = null;
	var $subject = null;
	var $cc = null;
	var $bcc = null;
	var $template = '../layouts/default';
	var $attachments = null;
	
	var $controller;
	
	function startup( & $controller)
	{
	   $this->controller = & $controller;
	}
	
	/**
	* Helper function to generate the appropriate template location
	*
	* @return string CakePHP location of the template file
	* @param object $template_type
	*/
	function templateLocation($template_type)
	{
	   //return ('..'.DS.strtolower($this->controller->name).DS.$this->template.$template_type);
	   return ($this->template . $template_type);
	}
	
	/**
	* Renders the content for either html or text of the email
	*
	* @return string Rendered content from the associated template
	* @param object $type_suffix
	*/
	function bodyContent($type_suffix)
	{
	   $temp_layout = $this->controller->layout; // store the current controller layout
	
	   if ($type_suffix == 'html')
	       $this->controller->layout = '../layouts/email';
	   else
	       $this->controller->layout = '';
	
	   $mail = $this->controller->render($this->templateLocation('_'.strtolower($type_suffix)));
	   // render() automatically adds to the controller->output, we'll remove it
	   $this->controller->output = str_replace($mail, '', $this->controller->output);
	
	   $this->controller->layout = $temp_layout; // restore the controller layout
	   return $mail;
	}
	
	function attach($filename, $asfile = '')
	{
	   if ( empty($this->attachments))
	   {
	       $this->attachments = array ();
	       $this->attachments[0]['filename'] = $filename;
	       $this->attachments[0]['asfile'] = $asfile;
	   } else
	   {
	       $count = count($this->attachments);
	       $this->attachments[$count+1]['filename'] = $filename;
	       $this->attachments[$count+1]['asfile'] = $asfile;
	   }
	}
	
	function send()
	{
	   App::import('Vendor', 'PHPMailer', array ('file'=>'phpmailer'.DS.'class.phpmailer.php'));
	
	   $mail = new PHPMailer();
		
	   if($this->useSMTPAuth) {
		    $mail->IsSMTP();
			$mail->SMTPAuth = $this->useSMTPAuth;
			$mail->Host = $this->smtpHostNames;
			$mail->Port = $this->smtpPort;
			$mail->Username = $this->smtpUserName;
			$mail->Password = $this->smtpPassword;
			$mail->SMTPDebug = $this->smtpDebug;
			$mail->SMTPSecure = $this->smtpSecure;
	   }
	   
	   $mail->From = $this->from;
	   $mail->FromName = $this->fromName;
	   $mail->AddAddress($this->to, $this->toName);
	   if(!empty($this->cc)) $mail->AddAddress($this->cc, '');
	   if(!empty($this->bcc)) $mail->AddAddress($this->bcc, '');
	   $mail->AddReplyTo($this->from, $this->fromName);
	
	   $mail->CharSet = 'UTF-8';
	   $mail->WordWrap = 80; // set word wrap to 50 characters
	
	   if (! empty($this->attachments))
	   {
	       foreach ($this->attachments as $attachment)
	       {
	           if ( empty($attachment['asfile']))
	           {
	               $mail->AddAttachment($attachment['filename']);
	           } else
	           {
	               $mail->AddAttachment($attachment['filename'], $attachment['asfile']);
	           }
	       }
	   }
	
	   $mail->IsHTML(true); // set email format to HTML
	
	   //$mail->Subject = $this->sitePrefix.' '.$this->subject;
	   $mail->Subject = $this->subject;
	   $mail->Body = $this->bodyContent('html');
	   $mail->AltBody = $this->bodyContent('text');
	
	   $result = $mail->Send();
	
	   if ($result == false)
	       $result = $mail->ErrorInfo;
	
	   return $result;
	}
}
?>