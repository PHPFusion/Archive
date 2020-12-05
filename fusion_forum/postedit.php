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

if (isset($_POST['previewchanges'])) {
	if (isset($_POST['disable_smileys'])) { $disable_smileys_check = " checked"; }
	if (isset($_POST['delete'])) { $del_check = " checked"; }
	opentable(LAN_405);
	$subject = stripinput(censorwords($subject));
	$message = stripinput(censorwords($message));
	if ($subject == "") $subject = $data['post_subject'];
	if ($message == "") {
		$previewmessage = LAN_421;
	} else {
		$previewmessage = $message;
		if (!$disable_smileys_check) { $previewmessage = parsesmileys($previewmessage); }
		$previewmessage = parseubb($previewmessage);
		$previewmessage = nl2br($previewmessage);
	}
	echo "<table cellspacing='0' cellpadding='0' border='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellspacing='1' cellpadding='0' width='100%'>
<tr>
<td width='145' class='tbl2'>".LAN_422."</td>
<td class='tbl2'>$subject</td>
</tr>
<tr>
<td valign='top' rowspan='2' width='145' class='tbl1'>".$data['user_name']."<br>
<span class='alt'>".getmodlevel($data[user_mod])."</span><br><br>
<span class='alt'>".LAN_423."</span> ".$data['user_posts']."<br>
<span class='alt'>".LAN_424."</span> ".$data['user_location']."<br>
<span class='alt'>".LAN_425."</span> ".showdate("%d.%m.%y", $data['user_joined'])."</td>
<td class='tbl1'><span class='small'>".LAN_426.showdate("forumdate", $data['post_datestamp'])."</span></td>
</tr>
<tr>
<td height='50' valign='top' class='tbl1'>$previewmessage<br>
<br>
<span class='small'>".LAN_427.$userdata['user_name'].LAN_428.showdate("forumdate", time())."</span>
</td>
</tr>
</table>
</tr>
</td>
</table>\n";
	closetable();
	tablebreak();
}
if (isset($_POST['savechanges'])) {
	if (isset($_POST['delete'])) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."posts WHERE post_id='$post_id' AND thread_id='$thread_id'");
		$result = dbquery("SELECT * FROM ".$fusion_prefix."forum_attachments WHERE post_id='$post_id'");
		if (dbrows($result) != 0) {
			$attach = dbarray($result);
			unlink(FUSION_PUBLIC."attachments/".$attach['attach_name']."[".$attach['attach_id']."]".$attach['attach_ext']);
			$result2 = dbquery("DELETE FROM ".$fusion_prefix."forum_attachments WHERE post_id='$post_id'");
		}
		$posts = dbcount("(post_id)", "posts", "thread_id='$thread_id'");
		if ($posts == 0) $result = dbquery("DELETE FROM ".$fusion_prefix."threads WHERE thread_id='$thread_id' AND forum_id='$forum_id'");
		opentable(LAN_407);
		echo "<center><br>\n".LAN_445."<br><br>\n";
		if ($posts > 0) echo "<a href='viewthread.php?forum_id=$forum_id&thread_id=$thread_id'>".LAN_447."</a> |\n";
		echo "<a href='viewforum.php?forum_id=$forum_id'>".LAN_448."</a> |
<a href='index.php'>".LAN_449."</a><br><br>\n</center>\n";
		closetable();
	} else {
		if (isset($_POST['disable_smileys'])) { $smileys = "0"; } else { $smileys = "1"; }
		$subject = stripinput(censorwords($subject));
		$message = stripinput(censorwords($message));
		if (iMEMBER) {
			if ($subject != "" && $message != "") {
				$result = dbquery("UPDATE ".$fusion_prefix."posts SET post_subject='$subject', post_message='$message', post_smileys='$smileys', post_edituser='".$userdata['user_id']."', post_edittime='".time()."' WHERE post_id='$post_id'");
				$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id' ORDER BY post_id ASC LIMIT 1"));
				if ($data['post_id'] == $post_id) {
					$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_subject='$subject' WHERE thread_id='$thread_id'");
				}
			} else {
				$error = LAN_441;
			}
		} else {
			$error = LAN_450;
		}
		opentable(LAN_409);
		echo "<center><br>\n";
		if ($error) {
			echo "$error<br><br>\n";
		} else {
			echo LAN_446."<br><br>\n";
		}
		echo "<a href='viewthread.php?forum_id=$forum_id&thread_id=$thread_id'>".LAN_447."</a> |
<a href='viewforum.php?forum_id=$forum_id'>".LAN_448."</a> |
<a href='index.php'>".LAN_449."</a><br><br>
</center>\n";
		closetable();
	}
} else {
	if (!isset($_POST['previewchanges'])) {
		$subject = $pdata['post_subject'];
		$message = $pdata['post_message'];
		if ($pdata['post_smileys'] == "0") { $disable_smileys_check = " checked"; }
	}
	opentable(LAN_408);
	echo "<form name='inputform' method='post' action='$PHP_SELF?action=edit&forum_id=$forum_id&thread_id=$thread_id&post_id=$post_id'>
<table border='0' cellspacing='0' cellpadding='0' width='100%' class='tbl-border'>
<tr>
<td>
<table width='100%' border='0' cellspacing='1' cellpadding='0'>
<tr>
<td width='145' class='tbl2'>".LAN_460."</td>
<td class='tbl2'><input type='text' name='subject' value='$subject' class='textbox' maxlength='255' style='width:250px'></td>
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
<input type='checkbox' name='disable_smileys' value='1'$disable_smileys_check>".LAN_483."<br>
<input type='checkbox' name='delete' value='1'$del_check>".LAN_482."
</td>
</tr>
</table>
</td>
</tr>
</table>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td align='center' colspan='2' class='tbl1'>
<input type='submit' name='previewchanges' value='".LAN_405."' class='button'>
<input type='submit' name='savechanges' value='".LAN_409."' class='button'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}
?>