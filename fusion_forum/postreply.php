<?
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
if (!defined("IN_FUSION")) header("Location:../index.php");

if (isset($_POST['previewreply'])) {
	if (isset($_POST['show_sig'])) { $sig_checked = " checked"; }
	if (isset($_POST['disable_smileys'])) { $disable_smileys_check = " checked"; }
	opentable(LAN_402);
	$subject = stripinput(censorwords($subject));
	$message = stripinput(censorwords($message));
	if ($_POST['subject'] == "") {
		$previewsubject = "RE: ".$tdata['thread_subject'];
	} else {
		$previewsubject = "RE: ".$subject;
	}
	if ($message == "") {
		$previewmessage = LAN_421;
	} else {
		$previewmessage = $message;
		if ($sig_checked) { $previewmessage = $previewmessage."\n\n".$userdata['user_sig']; }
		if (!$disable_smileys_check) {  $previewmessage = parsesmileys($previewmessage); }
		$previewmessage = parseubb($previewmessage);
		$previewmessage = nl2br($previewmessage);
	}
	echo "<table cellspacing='0' cellpadding='0' border='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellspacing='1' cellpadding='0' width='100%'>
<tr>
<td width='145' class='tbl2'>".LAN_422."</td>
<td class='tbl2'>$previewsubject</td>
</tr>
<tr>
<td valign='top' rowspan='2' width='145' class='tbl1'>".$userdata['user_name']."<br>
<span class='alt'>".getmodlevel($userdata['user_mod'])."</span><br><br>\n";
if ($userdata[user_avatar]) {
	echo "<img src='".FUSION_BASE."fusion_public/avatars/".$userdata['user_avatar']."'><br><br>\n";
	$height = "200";
} else {
	$height = "60";
}
echo "<span class='alt'>".LAN_423."</span> ".$userdata['user_posts']."<br>
<span class='alt'>".LAN_424."</span> ".$userdata['user_location']."<br>
<span class='alt'>".LAN_425."</span> ".showdate("%d.%m.%y", $userdata['user_joined'])."</td>
<td class='tbl1'><span class='small'>".LAN_426.showdate("forumdate", time())."</span></td>
</tr>
<tr>
<td height='$height' valign='top' class='tbl1'>$previewmessage</td>
</tr>
</table>
</tr>
</td>
</table>\n";
	closetable();
	tablebreak();
}
if (isset($_POST['postreply'])) {
	if (isset($_POST['show_sig'])) { $sig = "1"; } else { $sig = "0"; }
	if (isset($_POST['disable_smileys'])) { $smileys = "0"; } else { $smileys = "1"; }
	if ($_POST['subject'] == "") {
		$subject = "RE: ".$tdata['thread_subject'];
	} else {
		$subject = "RE: ".stripinput(censorwords($subject));
	}
	$message = stripinput(censorwords($message));
	if (iMEMBER) {
		if ($subject != "" && $message != "") {
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_lastpost='".time()."', forum_lastuser='".$userdata['user_id']."' WHERE forum_id='$forum_id'");
			$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_lastpost='".time()."', thread_lastuser='".$userdata['user_id']."' WHERE thread_id='$thread_id'");
			$result = dbquery("INSERT INTO ".$fusion_prefix."posts VALUES('$forum_id', '$thread_id', '', '$subject', '$message', '$sig', '$smileys', '".$userdata['user_id']."', '".time()."', '0', '0')");
			$newpost_id = mysql_insert_id();
			$result = dbquery("UPDATE ".$fusion_prefix."users SET user_posts=user_posts+1 WHERE user_id='".$userdata['user_id']."'");
			
			$attach = $_FILES['attach'];
			if ($attach['name'] != "" && !empty($attach['name']) && is_uploaded_file($attach['tmp_name']) && $attach['size'] <= $settings['attachmax']) {
				$attachext = strrchr($attach['name'],".");
				$attachtypes = explode(",", $settings['attachtypes']);
				if (in_array($attachext, $attachtypes)) {
					$attachname = substr($attach['name'], 0, strrpos($attach['name'], "."));
					$result = dbquery("INSERT INTO ".$fusion_prefix."forum_attachments VALUES('', '$thread_id', '$newpost_id', '$attachname', '$attachext', '".$attach['size']."')");
					$fileid = mysql_insert_id();
					$attachname = $attachname."[$fileid]".$attachext;
					move_uploaded_file($attach['tmp_name'], FUSION_PUBLIC."attachments/".$attachname);
					chmod(FUSION_PUBLIC."attachments/".$attachname,0644);
				} else {
					$uploaderror = LAN_440;
				}
			}
		} else {
			$error = LAN_441;
		}
	} else {
		$error = LAN_450;
	}
	opentable(LAN_403.": $caption");
	echo "<center><br>\n";
	if ($error) {
		echo "$error<br><br>\n";
	} else {
		echo LAN_443."<br><br>\n";
	}
	echo "<a href='viewthread.php?forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id'>".LAN_447."</a> |
<a href='viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat'>".LAN_448."</a> |
<a href='index.php'>".LAN_449."</a><br><br>
</center>\n";
	closetable();
} else {
	if (!isset($_POST['previewreply'])) { $sig_checked = " checked"; }
	if (isset($quote) && isNum($quote)) {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."posts INNER JOIN ".$fusion_prefix."users ON ".$fusion_prefix."posts.post_author=".$fusion_prefix."users.user_id WHERE thread_id='$thread_id' and post_id='$quote'");
		if (dbrows($result) != "0") {
			$data = dbarray($result);
			$message = "[quote][b]".$data['user_name'].LAN_429."[/b]\n".$data['post_message']."[/quote]";
		}
	}
	opentable(LAN_403.": $caption");
	echo "<form name='inputform' method='post' action='$PHP_SELF?action=reply&forum_id=$forum_id&thread_id=$thread_id' enctype='multipart/form-data'>
<table border='0' cellspacing='0' cellpadding='0' width='100%' class='tbl-border'>
<tr>
<td>
<table width='100%' border='0' cellspacing='1' cellpadding='0'>
<tr>
<td width='145' class='tbl2'>".LAN_460."</td>
<td class='tbl2'><input type='text' name='subject' value='$subject' class='textbox' maxlength='255' style='width:250px'>".LAN_465."</td>
</tr>
<tr>
<td valign='top' width='145' class='tbl2'>".LAN_461."</td>
<td class='tbl1'><textarea name='message' cols='80' rows='15' class='textbox'>$message</textarea></td>
</tr>
<tr>
<td width='145' class='tbl2'>&nbsp;</td>
<td class='tbl2'>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('message', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('message', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('message', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('message', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('message', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('message', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('message', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('message', '[small]', '[/small]');\">
<input type='button' value='code' class='button' style='width:40px;' onClick=\"addText('message', '[code]', '[/code]');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('message', '[quote]', '[/quote]');\">
</td>
</tr>
<td width='145' class='tbl2'>&nbsp;</td>
<td class='tbl1'>
".LAN_462."<select name='bbcolor' class='textbox' style='width:90px;' onChange=\"addText('message', '[color=' + this.options[this.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;\">
<option value=''>Default</option>
<option value='maroon' style='color:maroon;'>Maroon</option>
<option value='red' style='color:red;'>Red</option>
<option value='orange' style='color:orange;'>Orange</option>
<option value='brown' style='color:brown;'>Brown</option>
<option value='yellow' style='color:yellow;'>Yellow</option>
<option value='green' style='color:green;'>Green</option>
<option value='lime' style='color:lime;'>Lime</option>
<option value='olive' style='color:olive;'>Olive</option>
<option value='cyan' style='color:cyan;'>Cyan</option>
<option value='blue' style='color:blue;'>Blue</option>
<option value='navy' style='color:navy;'>Navy Blue</option>
<option value='purple' style='color:purple;'>Purple</option>
<option value='violet' style='color:violet;'>Violet</option>
<option value='black' style='color:black;'>Black</option>
<option value='gray' style='color:gray;'>Gray</option>
<option value='silver' style='color:silver;'>Silver</option>
<option value='white' style='color:white;'>White</option>
</select>
</td>
</tr>
<tr>
<td width='145' class='tbl2'>&nbsp;</td>
<td class='tbl2'>
".displaysmileys("message")."
</td>
</tr>
<tr>
<td valign='top' width='145' class='tbl2'>".LAN_463."</td>
<td class='tbl1'>
<input type='checkbox' name='disable_smileys' value='1'$disable_smileys_check>".LAN_483;
	if ($userdata['user_sig']) {
		echo "<br>\n<input type='checkbox' name='show_sig' value='1'$sig_checked>".LAN_481;
	}
	echo "</td>
</tr>\n";
	if ($settings['attachments'] == "1") {
		echo "<tr>
<td width='145' class='tbl2'>".LAN_464."</td>
<td class='tbl1'><input type='file' name='attach' enctype='multipart/form-data' class='textbox' style='width:200px;'></td>
</tr>\n";
	}
	echo "</table>
</td>
</tr>
</table>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td align='center' colspan='2' class='tbl1'>
<input type='submit' name='previewreply' value='".LAN_402."' class='button'>
<input type='submit' name='postreply' value='".LAN_404."' class='button'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}
?>