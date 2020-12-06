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
	
	global $settings,$locale;
	
	$email_to = $to_name." <".$to_email.">";
	$headers = "From: ".$from_name." <".$from_email.">\n";
	$headers .= "Reply-To: ".$from_name." <".$from_email.">\n";
	$headers .= "X-Sender: <".$from_email.">\n";
	$headers .= "X-Mailer: PHP-Mail\n";
	$headers .= "X-MimeOLE: Produced By PHP-Fusion 6\n";
	$headers .= "X-Priority: 3\n";
	$headers .= "X-MSMail-Priority: Normal\n";
	$headers .= "MIME-Version: 1.0\n";
	if ($type == "plain") {
		$headers .= "Content-type: text/plain; charset=".$locale['charset'];
	} else if ($type == "html") {
		$headers .= "Content-type: text/html; charset=".$locale['charset'];
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
