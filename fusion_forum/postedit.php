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
$data = dbarray($result);
$locked = $data[thread_locked];

$result = dbquery("SELECT * FROM ".$fusion_prefix."posts INNER JOIN ".$fusion_prefix."users ON ".$fusion_prefix."posts.post_author=".$fusion_prefix."users.user_id WHERE post_id='$post_id' and thread_id='$thread_id'");
$data = dbarray($result);

if ($locked != "y") {
	if ($userdata['user_id'] == $data['post_author'] || UserLevel() > 1) { $access = "ok"; }
} else {
	if (UserLevel() > 1) { $access = "ok"; }
}
		
if ($access == "ok") {

if (isset($_POST['previewchanges'])) {
	if (isset($_POST['disable_smileys'])) { $disable_smileys_check = " checked"; }
	if (isset($_POST['delete'])) { $del_check = " checked"; }
	opentable(LAN_405);
	$subject = stripinput($subject);
	$message = stripinput($message);
	if ($subject == "") {
		$subject = $data['post_subject'];
	}
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
<span class='alt'>".LAN_425."</span> ".strftime("%d.%m.%y", $data['user_joined']+($settings['timeoffset']*3600))."</td>
<td class='tbl1'><span class='small'>".LAN_426.strftime($settings['forumdate'], $data['post_datestamp']+($settings['timeoffset']*3600))."</span></td>
</tr>
<tr>
<td height='50' valign='top' class='tbl1'>$previewmessage<br>
<br>
<span class='small'>".LAN_427.$userdata['user_name'].LAN_428.strftime($settings['forumdate'], time()+($settings['timeoffset']*3600))."</span>
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
			while ($attach = dbarray($result)) {
				unlink(fusion_basedir."fusion_public/attachments/".$attach['attach_name']."[".$attach['attach_id']."]".$attach['attach_ext']);
			}
		}
		$result = dbquery("DELETE FROM ".$fusion_prefix."forum_attachments WHERE post_id='$post_id'");
		$result = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id' AND forum_id='$forum_id'");
		if (dbrows($result) == 0) {
			$result = dbquery("DELETE FROM ".$fusion_prefix."threads WHERE thread_id='$thread_id' AND forum_id='$forum_id'");
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_threads=forum_threads-1, forum_posts=forum_posts-1 WHERE forum_id='$forum_id'");
		} else {
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_posts=forum_posts-1 WHERE forum_id='$forum_id'");
			$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_replies=thread_replies-1 WHERE thread_id='$thread_id'");
		}
		opentable(LAN_407);
		echo "<center><br>
		".LAN_445."<br><br>
<a href='viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat'>".LAN_448."</a><br><br>
<a href='index.php'>".LAN_449."</a><br><br>
</center>\n";
		closetable();
	} else {
		if (isset($_POST['disable_smileys'])) { $smileys = "n"; } else { $smileys = "y"; }
		$subject = stripinput($subject);
		$message = stripinput($message);
		if (Member()) {
			if ($subject != "" && $message != "") {
				$result = dbquery("UPDATE ".$fusion_prefix."posts SET post_subject='$subject', post_message='$message', post_smileys='$smileys', post_edituser='".$userdata['user_id']."', post_edittime='".time()."' WHERE post_id='$post_id'");
				$result = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id' ORDER BY post_id ASC LIMIT 1");
				$data = dbarray($result);
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
		echo "<a href='viewthread.php?forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id'>".LAN_447."</a> |
<a href='viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat'>".LAN_448."</a> |
<a href='index.php'>".LAN_449."</a><br><br>
</center>\n";
		closetable();
	}
} else {
	if (!isset($_POST['previewchanges'])) {
		$subject = $data['post_subject'];
		$message = $data['post_message'];
		if ($data['post_smileys'] == "n") { $disable_smileys_check = " checked"; }
	}
	opentable(LAN_408);
	echo "<form name='editform' method='post' action='$PHP_SELF?action=edit&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id&post_id=$post_id'>
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
<td class='tbl1'><textarea name='message' cols='80' rows='15' class='textbox' onselect='updatePos(this);' onkeyup='updatePos(this);' onclick='updatePos(this);' ondblclick='updatePos(this);'>$message</textarea></td>
</tr>
<tr>
<td width='145' class='tbl2'>&nbsp;</td>
<td class='tbl2'>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"AddText('b', '/b');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"AddText('i', '/i');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"AddText('u', '/u');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"AddText('url', '/url');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"AddText('mail', '/mail');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"AddText('img', '/img');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"AddText('center', '/center');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"AddText('small', '/small');\">
<input type='button' value='code' class='button' style='width:40px;' onClick=\"AddText('code', '/code');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"AddText('quote', '/quote');\">
</td>
</tr>
<td width='145' class='tbl2'>&nbsp;</td>
<td class='tbl1'>
".LAN_462."<select name='bbcolor' class='textbox' style='width:90px;' onChange=\"AddText('color=' + this.options[this.selectedIndex].value + '', '/color');this.selectedIndex=0;\">
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
<a href=\"javascript:insertText(':)');\"><img src='".fusion_basedir."fusion_images/smiley/smile.gif' border='0'></a>
<a href=\"javascript:insertText(';)');\"><img src='".fusion_basedir."fusion_images/smiley/wink.gif' border='0'></a>
<a href=\"javascript:insertText(':|');\"><img src='".fusion_basedir."fusion_images/smiley/frown.gif' border='0'></a>
<a href=\"javascript:insertText(':(');\"><img src='".fusion_basedir."fusion_images/smiley/sad.gif' border='0'></a>
<a href=\"javascript:insertText(':o');\"><img src='".fusion_basedir."fusion_images/smiley/shock.gif' border='0'></a>
<a href=\"javascript:insertText(':p');\"><img src='".fusion_basedir."fusion_images/smiley/pfft.gif' border='0'></a>
<a href=\"javascript:insertText('B)');\"><img src='".fusion_basedir."fusion_images/smiley/cool.gif' border='0'></a>
<a href=\"javascript:insertText(':D');\"><img src='".fusion_basedir."fusion_images/smiley/grin.gif' border='0'></a>
<a href=\"javascript:insertText(':@');\"><img src='".fusion_basedir."fusion_images/smiley/angry.gif' border='0'></a>
</td>
</tr>
<tr>
<td valign='top' width='145' class='tbl2'>".LAN_463."</td>
<td class='tbl1'>
<input type='checkbox' name='disable_smileys' value='y'$disable_smileys_check>".LAN_483."<br>
<input type='checkbox' name='delete' value='y'$del_check>".LAN_482."
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
	echo "<script language='JavaScript'>
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

} else {
	opentable(LAN_408);
	echo "<center><br>\n".LAN_300."<br><br>
<a href='viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat'>".LAN_447."</a><br><br>
<a href='index.php'>".LAN_448."</a>\n</center></br>\n";
	closetable();
}
?>