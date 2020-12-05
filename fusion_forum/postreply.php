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
$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE thread_id='$thread_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$caption = $data[thread_subject];
}

if (isset($_POST['previewreply'])) {
	if (isset($_POST['show_sig'])) { $sig_checked = " checked"; }
	if (isset($_POST['disable_smileys'])) { $disable_smileys_check = " checked"; }
	opentable(LAN_402);
	$subject = stripinput($subject);
	$message = stripinput($message);
	if ($subject == "") {
		$previewsubject = "RE: ".$data[thread_subject];
	} else {
		$previewsubject = "RE: ".$subject;
	}
	if ($message == "") {
		$previewmessage = LAN_421;
	} else {
		$previewmessage = $message;
		if ($sig_checked) { $previewmessage = $previewmessage."\n\n".$userdata[user_sig]; }
		if (!$disable_smileys_check) {  $previewmessage = parsesmileys($previewmessage); }
		$previewmessage = parseubb($previewmessage);
		$previewmessage = nl2br($previewmessage);
	}
	$posted = strftime($settings[forumdate], time()+($settings[timeoffset]*3600));
	echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"forum-border\">
<tr>
<td>
<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\">
<tr>
<td width=\"145\" class=\"forum2\">".LAN_422."</td>
<td class=\"forum2\">$previewsubject</td>
</tr>
<tr>
<td valign=\"top\" rowspan=\"2\" width=\"145\" class=\"forum1\">$userdata[user_name]<br>
<span class=\"alt\">".getmodlevel($userdata[user_mod])."</span><br><br>\n";
if ($userdata[user_avatar]) {
	echo "<img src=\"".fusion_basedir."fusion_public/avatars/$userdata[user_avatar]\"><br><br>\n";
	$height = "200";
} else {
	$height = "60";
}
echo "<span class=\"alt\">".LAN_423."</span> $userdata[user_posts]<br>
<span class=\"alt\">".LAN_424."</span> $userdata[user_location]<br>
<span class=\"alt\">".LAN_425."</span> ".strftime("%d.%m.%y", $userdata[user_joined]+($settings[timeoffset]*3600))."</td>
<td class=\"forum1\"><span class=\"small\">".LAN_426."$posted</span></td>
</tr>
<tr>
<td height=\"$height\" valign=\"top\" class=\"forum1\">$previewmessage</td>
</tr>
</table>
</tr>
</td>
</table>\n";
	closetable();
	tablebreak();
}
if (isset($_POST['postreply'])) {
	if (isset($_POST['show_sig'])) { $sig = "y"; } else { $sig = "n"; }
	if (isset($_POST['disable_smileys'])) { $smileys = "n"; } else { $smileys = "y"; }
	if ($subject == "") {
		$subject = "RE: ".$data[thread_subject];
	} else {
		$subject = "RE: ".stripinput($subject);
	}
	$message = stripinput($message);
	if (Member()) {
		if ($subject != "" && $message != "") {
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_posts=forum_posts+1, forum_lastpost='".time()."', forum_lastuser='$userdata[user_id]' WHERE forum_id='$forum_id'");
			$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_replies=thread_replies+1, thread_lastpost='".time()."', thread_lastuser='$userdata[user_id]' WHERE thread_id='$thread_id'");
			$result = dbquery("INSERT INTO ".$fusion_prefix."posts VALUES('$forum_id', '$thread_id', '', '$subject', '$message', '$sig', '$smileys', '$userdata[user_id]', '".time()."', '', '0')");
			$newpost_id = mysql_insert_id();
			$result = dbquery("UPDATE ".$fusion_prefix."users SET user_posts=user_posts+1 WHERE user_id='$userdata[user_id]'");
			
			$attach = $_FILES['attach'];
			if ($attach['name'] != "" && !empty($attach['name']) && is_uploaded_file($attach['tmp_name']) && $attach['size'] <= $settings[attachmax]) {
				$attachext = strrchr($attach['name'],".");
				$attachtypes = explode(",", $settings[attachtypes]);
				if (in_array($attachext, $attachtypes)) {
					$attachname = substr($attach['name'], 0, strrpos($attach['name'], "."));
					$result = dbquery("INSERT INTO ".$fusion_prefix."forum_attachments VALUES('', '$thread_id', '$newpost_id', '$attachname', '$attachext', '$attach[size]')");
					$fileid = mysql_insert_id();
					$attachname = $attachname."[$fileid]".$attachext;
					move_uploaded_file($attach['tmp_name'], fusion_basedir."fusion_public/attachments/".$attachname);
					chmod(fusion_basedir."fusion_public/attachments/".$attachname,0644);
				} else {
					$uploaderror = LAN_440;
				}
			}
		} else {
			$error = LAN_441;
		}
	} else {
		$error = LAN_449;
	}
	opentable(LAN_403.": $caption");
	echo "<center><br>\n";
	if ($error) {
		echo "$error<br><br>\n";
	} else {
		echo LAN_443."<br><br>\n";
	}
	echo "<a href=\"viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat\">".LAN_447."</a><br><br>
<a href=\"index.php\">".LAN_448."</a><br><br>
</center>\n";
	closetable();
} else {
	if (!isset($_POST['previewpost'])) { $sig_checked = " checked"; }
	if (isset($quote)) {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."posts JOIN ".$fusion_prefix."users ON ".$fusion_prefix."posts.post_author=".$fusion_prefix."users.user_id WHERE thread_id='$thread_id' and post_id='$quote'");
		if (dbrows($result) != "0") {
			$data = dbarray($result);
			$message = "[quote][b]$data[user_name] wrote:[/b]\n".$data[post_message]."[/quote]";
		}
	}
	opentable(LAN_403.": $caption");
	echo "<form name=\"editform\" method=\"post\" action=\"$PHP_SELF?action=reply&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id\" enctype=\"multipart/form-data\">
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"forum-border\">
<tr>
<td>
<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
<tr>
<td width=\"145\" class=\"forum2\">".LAN_460."</td>
<td class=\"forum2\"><input type=\"text\" name=\"subject\" value=\"$subject\" class=\"textbox\" maxlength=\"255\" style=\"width:250px\">".LAN_465."</td>
</tr>
<tr>
<td valign=\"top\" width=\"145\" class=\"forum2\">".LAN_461."</td>
<td class=\"forum1\"><textarea name=\"message\" cols=\"80\" rows=\"15\" class=\"textbox\" onselect=\"updatePos(this);\" onkeyup=\"updatePos(this);\" onclick=\"updatePos(this);\" ondblclick=\"updatePos(this);\">$message</textarea></td>
</tr>
<tr>
<td width=\"145\" class=\"forum2\">&nbsp;</td>
<td class=\"forum2\">
<input type=\"button\" value=\"b\" class=\"button\" style=\"font-weight:bold;width:25px;\" onClick=\"AddText('b', '/b');\">
<input type=\"button\" value=\"i\" class=\"button\" style=\"font-style:italic;width:25px;\" onClick=\"AddText('i', '/i');\">
<input type=\"button\" value=\"u\" class=\"button\" style=\"text-decoration:underline;width:25px;\" onClick=\"AddText('u', '/u');\">
<input type=\"button\" value=\"url\" class=\"button\" style=\"width:30px;\" onClick=\"AddText('url', '/url');\">
<input type=\"button\" value=\"mail\" class=\"button\" style=\"width:35px;\" onClick=\"AddText('mail', '/mail');\">
<input type=\"button\" value=\"img\" class=\"button\" style=\"width:30px;\" onClick=\"AddText('img', '/img');\">
<input type=\"button\" value=\"center\" class=\"button\" style=\"width:45px;\" onClick=\"AddText('center', '/center');\">
<input type=\"button\" value=\"small\" class=\"button\" style=\"width:40px;\" onClick=\"AddText('small', '/small');\">
<input type=\"button\" value=\"code\" class=\"button\" style=\"width:40px;\" onClick=\"AddText('code', '/code');\">
<input type=\"button\" value=\"quote\" class=\"button\" style=\"width:45px;\" onClick=\"AddText('quote', '/quote');\">
</td>
</tr>
<td width=\"145\" class=\"forum2\">&nbsp;</td>
<td class=\"forum1\">
".LAN_462."<select name=\"bbcolor\" class=\"textbox\" style=\"width:90px;\" onChange=\"AddText('color=' + this.options[this.selectedIndex].value + '', '/color');this.selectedIndex=0;\">
<option value=\"\">Default</option>
<option value=\"maroon\" style=\"color:maroon;\">Maroon</option>
<option value=\"red\" style=\"color:red;\">Red</option>
<option value=\"orange\" style=\"color:orange;\">Orange</option>
<option value=\"brown\" style=\"color:brown;\">Brown</option>
<option value=\"yellow\" style=\"color:yellow;\">Yellow</option>
<option value=\"green\" style=\"color:green;\">Green</option>
<option value=\"lime\" style=\"color:lime;\">Lime</option>
<option value=\"olive\" style=\"color:olive;\">Olive</option>
<option value=\"cyan\" style=\"color:cyan;\">Cyan</option>
<option value=\"blue\" style=\"color:blue;\">Blue</option>
<option value=\"navy\" style=\"color:navy;\">Navy Blue</option>
<option value=\"purple\" style=\"color:purple;\">Purple</option>
<option value=\"violet\" style=\"color:violet;\">Violet</option>
<option value=\"black\" style=\"color:black;\">Black</option>
<option value=\"gray\" style=\"color:gray;\">Gray</option>
<option value=\"silver\" style=\"color:silver;\">Silver</option>
<option value=\"white\" style=\"color:white;\">White</option>
</select>
</td>
</tr>
<tr>
<td width=\"145\" class=\"forum2\">&nbsp;</td>
<td class=\"forum2\">
<a href=\"javascript:insertText(':)');\"><img src=\"".fusion_basedir."fusion_images/smiley/smile.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(';)');\"><img src=\"".fusion_basedir."fusion_images/smiley/wink.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':|');\"><img src=\"".fusion_basedir."fusion_images/smiley/frown.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':(');\"><img src=\"".fusion_basedir."fusion_images/smiley/sad.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':o');\"><img src=\"".fusion_basedir."fusion_images/smiley/shock.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':p');\"><img src=\"".fusion_basedir."fusion_images/smiley/pfft.gif\" border=\"0\"></a>
<a href=\"javascript:insertText('B)');\"><img src=\"".fusion_basedir."fusion_images/smiley/cool.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':D');\"><img src=\"".fusion_basedir."fusion_images/smiley/grin.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':@');\"><img src=\"".fusion_basedir."fusion_images/smiley/angry.gif\" border=\"0\"></a>
</td>
</tr>
<tr>
<td valign=\"top\" width=\"145\" class=\"forum2\">".LAN_463."</td>
<td class=\"forum1\">
<input type=\"checkbox\" name=\"disable_smileys\" value=\"y\"$disable_smileys_check>".LAN_483;
	if ($userdata[user_sig]) {
		echo "<br>\n<input type=\"checkbox\" name=\"show_sig\" value=\"sig\"$sig_checked>".LAN_481;
	}
	echo "</td>
</tr>\n";
	if ($settings[attachments] == "1") {
		echo "<tr>
<td width=\"145\" class=\"forum2\">".LAN_464."</td>
<td class=\"forum1\"><input type=\"file\" name=\"attach\" enctype=\"multipart/form-data\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>\n";
	}
	echo "</table>
</td>
</tr>
</table>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td align=\"center\" colspan=\"2\" class=\"forum1\">
<input type=\"submit\" name=\"previewreply\" value=\"".LAN_402."\" class=\"button\">
<input type=\"submit\" name=\"postreply\" value=\"".LAN_404."\" class=\"button\">
</td>
</tr>
</table>
</form>\n";
	closetable();
	echo "<script language=\"JavaScript\">
var editBody = document.editform.message;
function insertText(theText) {
	if (editBody.createTextRange && editBody.curPos) {
		editBody.curPos.text = theText;
	} else {
		editBody.value += theText;
	}
	editBody.focus();
}
function AddText(wrap, wrap2) {
	if (editBody.curPos) {
		insertText(\"[\" + wrap + \"]\" + editBody.curPos.text + \"[\" + wrap2 + \"]\");
	} else {
		insertText(\"[\" + wrap + \"][\" + wrap2 + \"]\");
	}
}
function updatePos(obj) {
	if (obj.createTextRange) {
		obj.curPos = document.selection.createRange().duplicate();
	}
}
</script>\n";
}
?>