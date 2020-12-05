<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE thread_id='$thread_id'");
$data = dbarray($result);
$locked = $data[thread_locked];

$result = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE post_id='$post_id' and thread_id='$thread_id'");
$data = dbarray($result);

if ($locked != "y") {
	if ("$userdata[user_id].$userdata[user_name]" == $data[post_author] || $userdata[user_mod] > "0") { $access = "ok"; }
} else {
	if ($userdata[user_mod] > "0") { $access = "ok"; }
}
		
if ($access == "ok") {

if (isset($_POST['previewchanges'])) {
	if (isset($_POST['disable_smileys'])) { $disable_smileys_check = " checked"; }
	if (isset($_POST['delete'])) { $del_check = " checked"; }
	if (isset($_POST['delete_thread'])) { $del_thread_check = " checked"; }
	if (isset($_POST['lock'])) { $lock_check = " checked"; }
	if (isset($_POST['unlock'])) { $unlock_check = " checked"; }
	opentable(LAN_205);
	$subject = stripinput($subject);
	$message = stripinput($message);
	if ($subject == "") {
		$subject = $data[post_subject];
	}
	if ($message == "") {
		$previewmessage = LAN_221;
	} else {
		$previewmessage = $message;
		if (!$disable_smileys_check) { $previewmessage = parsesmileys($previewmessage); }
		$previewmessage = parseubb($previewmessage);
		$previewmessage = nl2br($previewmessage);
	}
	$posted = strftime($settings[forumdate], $data[post_datestamp]+($settings[timeoffset]*3600));
	$post_author = explode(".", $data[post_author]);
	if ($data[post_author] != $userdata[user_id].$userdata[user_name]) {
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$post_author[0]' AND user_name='$post_author[1]'");
		$data2 = dbarray($result2);
		$moderator = getmodlevel($data2[user_mod]);
		$posts = $data2[user_posts];
		$location = $data2[user_location];
		$joined = strftime("%d.%m.%y", $data2[user_joined]+($settings[timeoffset]*3600));
	} else {
		$moderator = getmodlevel($userdata[user_mod]);
		$posts = $userdata[user_posts];
		$location = $userdata[location];
		$joined = strftime("%d.%m.%y", $userdata[user_joined]+($settings[timeoffset]*3600));
	}
	echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"forum-border\">
<tr>
<td>
<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\">
<tr>
<td width=\"145\" class=\"forum2\">".LAN_222."</td>
<td class=\"forum2\">$subject</td>
</tr>
<tr>
<td valign=\"top\" rowspan=\"2\" width=\"145\" class=\"forum1\">$post_author[1]<br>
<span class=\"alt\">$moderator</span><br><br>
<span class=\"alt\">".LAN_223."</span> $posts<br>
<span class=\"alt\">".LAN_224."</span> $location<br>
<span class=\"alt\">".LAN_225."</span> $joined</td>
<td class=\"forum1\"><span class=\"small\">".LAN_226."$posted</span></td>
</tr>
<tr>
<td height=\"50\" valign=\"top\" class=\"forum1\">$previewmessage<br>
<br>
<span class=\"small\">".LAN_227.$userdata[user_name].LAN_228.strftime($settings[forumdate], time()+($settings[timeoffset]*3600))."</span>
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
	if (isset($_POST['delete_thread'])) {
		$result = dbquery("SELECT count(thread_id) FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id'");
	        $postcount = dbresult($result, 0);
	        $postcount--;
		$result = dbquery("SELECT count(forum_id) FROM ".$fusion_prefix."threads WHERE forum_id='$forum_id'");
	        $threadcount = dbresult($result, 0);
	        $threadcount--;
	        if ($postcount != 0) {
	        	$posts = ", forum_posts=forum_posts-$postcount";     	
		}
        	if ($threadcount == 0) {
        		$lastuser = ", forum_lastuser='0', forum_lastpost='0'";
        	}
		$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_threads=forum_threads-1".$posts."".$lastuser." WHERE forum_id='$forum_id'");
		$result = dbquery("DELETE FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id'");
		$result = dbquery("DELETE FROM ".$fusion_prefix."threads WHERE thread_id='$thread_id' AND forum_id='$forum_id'");
		$result = dbquery("SELECT * FROM ".$fusion_prefix."forum_attachments WHERE thread_id='$thread_id'");
		if (dbrows($result) != 0) {
			while ($attach = dbarray($result)) {
				unlink(fusion_basedir."fusion_public/attachments/".$attach[attach_name]."[".$attach[attach_id]."]".$attach[attach_ext]);
			}
		}
		$result = dbquery("DELETE FROM ".$fusion_prefix."forum_attachments WHERE thread_id='$thread_id'");
		opentable(LAN_206);
		echo "<center><br>
".LAN_244."<br><br>
<a href=\"viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat\">".LAN_247."</a><br><br>
<a href=\"index.php\">".LAN_248."</a><br><br>
</center>\n";
		closetable();
	} else if (isset($_POST['delete'])) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."posts WHERE post_id='$post_id' AND thread_id='$thread_id'");
		$result = dbquery("SELECT * FROM ".$fusion_prefix."forum_attachments WHERE post_id='$post_id'");
		if (dbrows($result) != 0) {
			while ($attach = dbarray($result)) {
				unlink(fusion_basedir."fusion_public/attachments/".$attach[attach_name]."[".$attach[attach_id]."]".$attach[attach_ext]);
			}
		}
		$result = dbquery("DELETE FROM ".$fusion_prefix."forum_attachments WHERE post_id='$post_id'");
		$result = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id' AND forum_id='$forum_id'");
		if (dbrows($result) == 0) {
			$result = dbquery("DELETE FROM ".$fusion_prefix."threads WHERE thread_id='$thread_id' AND forum_id='$forum_id'");
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_threads=forum_threads-1 WHERE forum_id='$forum_id'");
		} else {
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_posts=forum_posts-1 WHERE forum_id='$forum_id'");
			$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_replies=thread_replies-1 WHERE thread_id='$thread_id'");
		}
		opentable(LAN_207);
		echo "<center><br>
		".LAN_245."<br><br>
<a href=\"viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat\">".LAN_247."</a><br><br>
<a href=\"index.php\">".LAN_248."</a><br><br>
</center>\n";
		closetable();
	} else {
		if (isset($_POST['disable_smileys'])) { $smileys = "n"; } else { $smileys = "y"; }
		$subject = stripinput($subject);
		$message = stripinput($message);
		$subject = addslashes($subject);
		$message = addslashes($message);
		if ($subject != "" && $message != "") {
			if (isset($_POST['lock'])) { 
				$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_locked='y' WHERE thread_id='$thread_id' AND forum_id='$forum_id'");
			} else if (isset($_POST['unlock'])) {
				$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_locked='n' WHERE thread_id='$thread_id' AND forum_id='$forum_id'");
			}
			$result = dbquery("UPDATE ".$fusion_prefix."posts SET post_subject='$subject', post_message='$message', post_smileys='$smileys', post_edituser='$userdata[user_id].$userdata[user_name]', post_edittime='".time()."' WHERE post_id='$post_id'");
			$result = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id' ORDER BY post_id ASC LIMIT 1");
			$data = dbarray($result);
			if ($data[post_id] == $post_id) {
				$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_subject='$subject' WHERE thread_id='$thread_id'");
			}
		} else {
			$error = LAN_241;
		}
		opentable(LAN_209);
		echo "<center><br>\n";
		if ($error) {
			echo "$error<br><br>\n";
		} else {
			echo LAN_246."<br><br>\n";
		}
		echo "<a href=\"viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat\">".LAN_247."</a><br><br>
<a href=\"index.php\">".LAN_248."</a><br><br>
</center>\n";
		closetable();
	}
} else {
	if (!isset($_POST['previewchanges'])) {
		$subject = $data[post_subject];
		$message = $data[post_message];
		if ($data[post_smileys] == "n") { $disable_smileys_check = " checked"; }
	}
	opentable(LAN_208);
	echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"forum-border\">
<form name=\"editform\" method=\"post\" action=\"$PHP_SELF?action=edit&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id&post_id=$post_id\">
<tr>
<td>
<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
<tr>
<td width=\"145\" class=\"forum2\">".LAN_260."</td>
<td class=\"forum2\"><input type=\"textbox\" name=\"subject\" value=\"$subject\" class=\"textbox\" maxlength=\"255\" style=\"width:250px\"></td>
</tr>
<tr>
<td valign=\"top\" width=\"145\" class=\"forum2\">".LAN_261."</td>
<td class=\"forum1\"><textarea name=\"message\" rows=\"10\" class=\"textbox\" style=\"width:350px\" onselect=\"updatePos(this);\" onkeyup=\"updatePos(this);\" onclick=\"updatePos(this);\" ondblclick=\"updatePos(this);\">$message</textarea></td>
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
</td>
</tr>
<td width=\"145\" class=\"forum2\">&nbsp;</td>
<td class=\"forum1\">
".LAN_262."<select name=\"bbcolor\" class=\"textbox\" style=\"width:90px;\" onChange=\"AddText('color=' + this.options[this.selectedIndex].value + '', '/color');this.selectedIndex=0;\">
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
<td valign=\"top\" width=\"145\" class=\"forum2\">Options:</td>
<td class=\"forum1\">
<input type=\"checkbox\" name=\"disable_smileys\" value=\"y\"$disable_smileys_check>".LAN_286."<br>
<input type=\"checkbox\" name=\"delete\" value=\"y\"$del_check>".LAN_282;
	if ($userdata[user_mod] > "0") {
		echo "<br>\n<input type=\"checkbox\" name=\"delete_thread\" value=\"y\"$del_thread_check>".LAN_283."<br>\n";
		if ($locked != "y") {
			echo "<input type=\"checkbox\" name=\"lock\" value=\"y\"$lock_check>".LAN_284."\n";
		} else {
			echo "<input type=\"checkbox\" name=\"unlock\" value=\"y\"$unlock_check>".LAN_285."\n";
		}
	}
	echo "</td>
</tr>
</table>
</td>
</tr>
</table>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td align=\"center\" colspan=\"2\" class=\"forum1\">
<input type=\"submit\" name=\"previewchanges\" value=\"".LAN_205."\" class=\"button\">
<input type=\"submit\" name=\"savechanges\" value=\"".LAN_209."\" class=\"button\">
</td>
</tr>
</form>
</table>\n";
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

} else {
	opentable(LAN_208);
	echo "<center><br>\n".LAN_300."<br><br>
<a href=\"viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat\">".LAN_247."</a><br><br>
<a href=\"index.php\">".LAN_248."</a>\n</center></br>\n";
	closetable();
}
?>