<?php
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }

//ini_set(sendmail_path, "/usr/sbin/sendmail -t -f ".$settings['siteemail']);

function sendemail($to_name,$to_email,$subject,$message,$type="plain",$cc="",$bcc="") {
	
	global $settings;
	
	$email_to = $to_name." <".$to_email.">";
	$headers .= "Return-Path: <".$settings['siteemail'].">\r\n";
	$headers .= "Reply-To: ".$settings['sitename']." <".$settings['siteemail'].">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "From: $settings[sitename] <".$settings['siteemail'].">\r\n";
	$headers .= "X-Sender: <".$settings['siteemail'].">\r\n";
	$headers .= "X-Mailer: PHP\r\n";
	$headers .= "X-Priority: 3\r\n";
	if ($type == "plain") {
		$headers .= "Content-type: text/plain; charset=".FUSION_CHARSET."\r\n";
	} else if ($type == "html") {
		$headers .= "Content-type: text/html; charset=".FUSION_CHARSET."\r\n";
	}
	if ($cc) $headers .= "Cc: ".$cc."\r\n";
	if ($bcc) $headers .= "Bcc: ".$bcc."\r\n";
	if (mail($email_to, $subject, $message, $headers)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function sendemailfrom($mailname,$email,$subject,$message,$type="plain") {
	
	global $settings;
	
	$email_to = $settings['siteusername']." <".$settings['siteemail'].">";
	$headers .= "Return-Path: <".$email.">\r\n";
	$headers .= "Reply-To: ".$mailname." <".$email.">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "From: ".$mailname." <".$email.">\r\n";
	$headers .= "X-Sender: <".$email.">\r\n";
	$headers .= "X-Mailer: PHP\r\n";
	$headers .= "X-Priority: 3\r\n";
	if ($type == "plain") {
		$headers .= "Content-type: text/plain; charset=".FUSION_CHARSET."\r\n";
	} else if ($type == "html") {
		$headers .= "Content-type: text/html; charset=".FUSION_CHARSET."\r\n";
	}
	if (mail($email_to, $subject, $message, $headers)) {
		return TRUE;
	} else {
		return FALSE;
	}
}
?>
