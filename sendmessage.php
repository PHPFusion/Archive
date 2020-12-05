<?
/*
---------------------------------------------------
	PHP-Fusion - Content Management System
	-------------------------------------------
	© Nick Jones (Digitanium) 2002-2004
	http://www.php-fusion.co.uk
	nick@php-fusion.co.uk
	-------------------------------------------
	Released under the terms and conditions of
	the GNU General Public License (Version 2)
---------------------------------------------------
*/
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."messages.php";
require "side_left.php";

if (Member()) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$user_id'");
	$data = dbarray($result);
	if (isset($_POST['send_message'])) {
		$subject = stripinput($_POST['subject']);
		$subject = trim(chop(str_replace("&nbsp;", "", $subject)));
		$message = stripinput($_POST['message']);
		$message = trim(chop(str_replace("&nbsp;", "", $message)));
		if ($subject == "") {
			$error = "<span class='alt'>".LAN_452."</span><br>\n";
		}
		if ($message == "") {
			$error .= "<span class='alt'>".LAN_453."</span><br>\n";
		}
		if (!$error) {
			$presubject = $subject;
			$premessage = nl2br($message);
			if (!$disable_smileys) { 
				$premessage = parsesmileys($premessage);
				$smileys = "y";
			} else {
				$smileys = "n";
			}
			$premessage = parseubb($premessage);
			$subject = addslashes($subject);
			$message = addslashes($message);
			$datesent = strftime($settings[shortdate], time()+($settings[timeoffset]*3600));
			$result = dbquery("INSERT INTO ".$fusion_prefix."messages VALUES('', '".$data['user_id']."', '".$userdata['user_id']."', '$subject', '$message', '$smileys', '0', '".time()."')");
			opentable(LAN_430);
			echo "<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td><span class='alt'>".LAN_490."</span> $data[user_name]<br>
<span class='alt'>".LAN_491."</span> $userdata[user_name]<br>
<span class='alt'>".LAN_492."</span> $datesent<br>
<span class='alt'>".LAN_493."</span> $presubject<br><br>
<span class='alt'>".LAN_494."</span><br>
$premessage</td></tr>
<tr><td colspan='2'><br><div align='center'>
".LAN_450."<br><br>
<a href='index.php'>".LAN_451."</a></div></td>
</tr>
</table>\n";
			closetable();
		} else {
			opentable(LAN_430);
			echo "<center><br>
".LAN_454."<br><br>
$error<br>
<a href='$PHP_SELF'>".LAN_455."</a>.<br><br>
</center>\n";
			closetable();
		}
	} else {
		if (isset($reply_id)) {
			$result2 = dbquery(
				"SELECT * FROM ".$fusion_prefix."messages INNER JOIN ".$fusion_prefix."users
				ON ".$fusion_prefix."messages.message_from=".$fusion_prefix."users.user_id
				WHERE message_to='$userdata[user_id]' and message_id='$reply_id'"
			);
			$data2 = dbarray($result2);
			$recipient = $data2[user_name];
			if ($data2[message_subject] != "") {
				if (!strstr($data2[message_subject], "RE: ")) {
					$subject = "RE: ".$data2[message_subject];
				} else {
					$subject = $data2[message_subject];
				}
			}
		} else {
			$recipient = $data[user_name];
		}
		opentable(LAN_430);
		echo "<form name='messageform' method='post' action='$PHP_SELF?user_id=$user_id'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td><span class='alt'>".LAN_470."</span> $recipient<br><br>
".LAN_471."<span style='color:#ff0000'>*</span><br>
<input type='text' name='subject' value='$subject' maxlength='32' class='textbox' style='width:200px;'><br><br>
".LAN_472."<span style='color:#ff0000'>*</span><br>
<textarea name='message' cols='80' rows='10' class='textbox' style='width:400px' onselect='updatePos(this);' onkeyup='updatePos(this);' onclick='updatePos(this);' ondblclick='updatePos(this);'></textarea><br>
<center>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"AddText('b');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"AddText('i');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"AddText('u');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"AddText('url');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"AddText('mail');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"AddText('img');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"AddText('center');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"AddText('small');\">
<br><br>
<a href=\"javascript:insertText(':)');\"><img src='".fusion_basedir."fusion_images/smiley/smile.gif' border='0'></a>
<a href=\"javascript:insertText(';)');\"><img src='".fusion_basedir."fusion_images/smiley/wink.gif' border='0'></a>
<a href=\"javascript:insertText(':|');\"><img src='".fusion_basedir."fusion_images/smiley/frown.gif' border='0'></a>
<a href=\"javascript:insertText(':(');\"><img src='".fusion_basedir."fusion_images/smiley/sad.gif' border='0'></a>
<a href=\"javascript:insertText(':o');\"><img src='".fusion_basedir."fusion_images/smiley/shock.gif' border='0'></a>
<a href=\"javascript:insertText(':p');\"><img src='".fusion_basedir."fusion_images/smiley/pfft.gif' border='0'></a>
<a href=\"javascript:insertText('B)');\"><img src='".fusion_basedir."fusion_images/smiley/cool.gif' border='0'></a>
<a href=\"javascript:insertText(':D');\"><img src='".fusion_basedir."fusion_images/smiley/grin.gif' border='0'></a>
<a href=\"javascript:insertText(':@');\"><img src='".fusion_basedir."fusion_images/smiley/angry.gif' border='0'></a>
<br><br>
<input type='checkbox' name='disable_smileys' value='y'$disable_smileys_check>".LAN_474."
<br><br>
<input type='submit' name='send_message' value='".LAN_473."' class='button'>
</center>
</td>
</tr>
</table>
</form>\n";
		closetable();
		echo "<script language=\"JavaScript\">
var editBody = document.messageform.message;
function insertText(theText) {
	if (editBody.createTextRange && editBody.curPos) {
		editBody.curPos.text = theText;
	} else {
		editBody.value += theText;
	}
	editBody.focus();
}
function AddText(wrap) {
	if (editBody.curPos) {
		insertText(\"[\" + wrap + \"]\" + editBody.curPos.text + \"[/\" + wrap + \"]\");
	} else {
		insertText(\"[\" + wrap + \"][/\" + wrap + \"]\");
	}
}
function updatePos(obj) {
	if (obj.createTextRange) {
		obj.curPos = document.selection.createRange().duplicate();
	}
}
</script>\n";
	}
} else {
	opentable(LAN_430);
	echo LAN_413."\n";
	closetable();
}

require "side_right.php";
require "footer.php";
?>