<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }

function sendemail($toname,$toemail,$fromname,$fromemail,$subject,$message,$type="plain",$cc="",$bcc="") {

	global $settings, $locale;
	
	require_once INCLUDES."phpmailer_include.php";
	
	$mail = new PHPMailer();
	if (file_exists(INCLUDES."languages/phpmailer.lang-".$locale['tinymce'].".php")) {
		$mail->SetLanguage($locale['tinymce'], INCLUDES."language/");
	} else {
		$mail->SetLanguage("en", INCLUDES."language/");
	}

	if ($settings['smtp_host']=="") {
		$mail->IsMAIL();
	} else {
		$mail->IsSMTP();
		$mail->Host = $settings['smtp_host'];
		$mail->SMTPAuth = false;
		$mail->Username = $settings['smtp_username'];
		$mail->Password = $settings['smtp_password'];
	}
	
	$mail->CharSet = $locale['charset'];
	$mail->From = $fromemail;
	$mail->FromName = $fromname;
	$mail->AddAddress($toemail, $toname);
	if ($cc) $mail->AddCC($cc);
	if ($bcc) $mail->AddBCC($bcc);
	$mail->AddReplyTo($fromemail, $fromname);
	
	if ($type == "plain") {
		$mail->IsHTML(false);
	} else {
		$mail->IsHTML(true);
	}
	
	$mail->Subject = $subject;
	$mail->Body = $message;
	
	if(!$mail->Send()) {
		$mail->ErrorInfo;
		return false;
	} else {
		return true;
	}

}
?>
