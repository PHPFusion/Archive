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

/* sendemail parameters
	$to_name	-	recipient's name
	$to_email	-	recipient's email address
	$from_name	-	sender's name
	$from_email	-	sender's email address
	$subject	-	e-mail subject
	$message	-	e-mail body
	$type		-	plain or html (must be specified if $cc or $bcc is set)
	$cc		-	carbon copy recipients (must be specified if $bcc is set)
	$bcc		-	blind carbon copy recipients
*/

function sendemail($to_name,$to_email,$from_name,$from_email,$subject,$message,$type="plain",$cc="",$bcc="") {
	
	global $settings;
	
	$email_to = $to_name." <".$to_email.">";
	$headers = "From: ".$from_name." <".$from_email.">\n";
	$headers .= "Reply-To: ".$from_name." <".$from_email.">\n";
	$headers .= "X-Sender: <".$from_email.">\n";
	$headers .= "X-Mailer: PHP-Mail\n";
	$headers .= "X-MimeOLE: Produced By PHP-Fusion 5\n";
	$headers .= "X-Priority: 3\n";
	$headers .= "X-MSMail-Priority: Normal\n";
	$headers .= "MIME-Version: 1.0\n";
	if ($type == "plain") {
		$headers .= "Content-type: text/plain; charset=".FUSION_CHARSET;
	} else if ($type == "html") {
		$headers .= "Content-type: text/html; charset=".FUSION_CHARSET;
	}
	if ($cc) $headers .= "\n"."Cc: ".$cc;
	if ($bcc) $headers .= "\n"."Bcc: ".$bcc;
	if (mail($email_to, $subject, $message, $headers)) {
		return TRUE;
	} else {
		return FALSE;
	}
}
?>
