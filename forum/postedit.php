<?
$result = dbquery("SELECT * FROM posts WHERE pid='$pid' and tid='$tid'");
$data = dbarray($result);
if (isset($_POST[previewchanges])) {
	if (isset($_POST[delete])) {
		$delcheck = "checked";
	}
	if (isset($_POST[lock])) {
		$lockcheck = "checked";
	}
	opentable("Preview Changes - $caption");
	$subject = stripslashes($subject);
	$message = stripslashes($message);
	if ($subject == "") {
		$previewsubject = "RE: ".stripslashes($data[subject]);
	} else {
		$previewsubject = "RE: ".htmlentities($subject);
	}
	if ($message == "") {
		$previewmessage = "No Message, Post will be rejected if you do not include a Message";
	} else {
		$previewmessage = htmlentities($message);
		$previewmessage = parseubb($message);
		$previewmessage = nl2br($previewmessage);
	}
	$posted = gmdate("m.d.Y", $data[posted])." at ".gmdate("H:i", $data[posted]);
	if ($data[author] != $userdata[userid]) {
		$result2 = dbquery("SELECT * FROM users WHERE userid='$data[author]'");
		$data2 = dbarray($result2);
		$author = $data2[username];
		$moderator = $data2[mod];
		$posts = $data2[posts];
		$location = $data2[location];
		$joined = gmdate("m.d.Y", $data2[joined]);
	} else {
		$author = $userdata[username];
		$moderator = $userdata[mod];
		$posts = $userdata[posts];
		$location = $userdata[location];
		$joined = gmdate("m.d.Y", $userdata[joined]);
	}
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td valign=\"top\" width=\"145\" class=\"forumh1\">Author:</td>
<td class=\"forumh2\">Subject: $previewsubject - Posted on $posted</td></tr>
<tr><td valign=\"top\" width=\"145\" class=\"forumf1\">$author<br>
$moderator<br><br>
Posts: $posts<br>
Location: $location<br>
Joined: $joined</td>
<td valign=\"top\" height=\"70\" class=\"forumf2\">$previewmessage<br>
Edited by: $userdata[username] on ".gmdate("m.d.Y", $servertime)." at ".gmdate("H:i", $servertime)."</td></tr>
</table>\n";
	closetable();
	tablebreak();
}
if (isset($_POST[savechanges])) {
	if (isset($_POST[deletethread])) {
		$result = dbquery("SELECT count(tid) FROM posts WHERE tid='$tid'");
	        $postcount = dbresult($result, 0);
	        $postcount--;
		$result = dbquery("SELECT count(fid) FROM threads WHERE fid='$fid'");
	        $threadcount = dbresult($result, 0);
	        $threadcount--;
	        if ($postcount != 0) {
	        	$posts = ", posts=posts-$postcount";     	
		}
        	if ($threadcount == 0) {
        		$lastuser = ", lastuser='0', lastpost='0'";
        	}
		$result = dbquery("UPDATE forums SET threads=threads-1".$posts."".$lastuser." WHERE fid='$fid'");
		$result = dbquery("DELETE FROM posts WHERE tid='$tid'");
		$result = dbquery("DELETE FROM threads WHERE tid='$tid' AND fid='$fid'");
		opentable("Delete Thread");
		echo "<div align=\"center\">
<br>The Thread has been deleted<br><br>
<a href=\"viewforum.php?fid=$fid&fup=$fup\">Return to Forum</a><br><br>
<a href=\"index.php\">Return to Forum Index</a><br><br></div>\n";
		closetable();
	} else if (isset($_POST[delete])) {
		$result = dbquery("DELETE FROM posts WHERE pid='$pid' AND tid='$tid'");
		$result = dbquery("SELECT * FROM posts WHERE tid='$tid' AND fid='$fid'");
		if (dbrows($result) == 0) {
			$result = dbquery("DELETE FROM threads WHERE tid='$tid' AND fid='$fid'");
			$result = dbquery("UPDATE forums SET threads=threads-1 WHERE fid='$fid'");
		} else {
			$result = dbquery("UPDATE forums SET posts=posts-1 WHERE fid='$fid'");
			$result = dbquery("UPDATE threads SET replies=replies-1 WHERE tid='$tid'");
		}
		opentable("Delete Post");
		echo "<div align=\"center\">
<br>The Post has been deleted<br><br>
<a href=\"viewforum.php?fid=$fid&fup=$fup\">Return to Forum</a><br><br>
<a href=\"index.php\">Return to Forum Index</a><br><br></div>\n";
		closetable();
	} else {
		$subject = htmlentities($subject);
		$message = htmlentities($message);
		$subject = addslashes($subject);
		$message = addslashes($message);
		if ($subject != "" && $message != "") {
			if (isset($_POST[lock])) {
				$result = dbquery("UPDATE posts SET subject='$subject', message='$message', edituser='$userdata[userid]', edittime='$servertime' WHERE pid='$pid'");
				$result = dbquery("UPDATE threads SET locked='y' WHERE tid='$tid' AND fid='$fid'");
			} else {
				$result = dbquery("UPDATE posts SET subject='$subject', message='$message', edituser='$userdata[userid]', edittime='$servertime' WHERE pid='$pid'");
			}
		} else {
			$error = "Error: You did not specify a Subject and/or Message";
		}
		opentable("Edit Post");
		echo "<div align=\"center\">\n";
		if ($error) {
			echo "<br>$error<br><br>\n";
		} else {
			echo "<br>Your changes have been saved<br><br>\n";
		}
		echo "<a href=\"viewforum.php?fid=$fid&fup=$fup\">Return to Forum</a><br><br>
<a href=\"index.php\">Return to Forum Index</a><br><br></div>\n";
		closetable();
	}
} else {
	if (!isset($_POST[previewchanges])) {
		$subject = stripslashes($data[subject]);
		$message = stripslashes($data[message]);
	}
	opentable("Edit Post");
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<form name=\"editform\" method=\"post\" action=\"$PHP_SELF?action=edit&fid=$fid&fup=$fup&tid=$tid&pid=$pid\">
<tr><td valign=\"top\" width=\"145\" class=\"forumh1\">subject</td>
<td class=\"forumh1\"><input type=\"textbox\" name=\"subject\" value=\"$subject\" class=\"textbox\" maxlength=\"255\" style=\"width: 250px\"></td></tr>
<tr><td valign=\"top\" width=\"145\" class=\"forumf1\">message</td>
<td class=\"forumf1\"><textarea name=\"message\" rows=\"8\" class=\"textbox\" style=\"width: 369px\" onselect=\"storeCaret(this);\" onclick=\"storeCaret(this);\" onkeyup=\"storeCaret(this);\">$message</textarea><br>
<input type=\"button\" value=\"Bold\" class=\"button\" style=\"width: 35px;\" onClick=\"AddTags('[b]', '[/b]');\">
<input type=\"button\" value=\"Italic\" class=\"button\" style=\"width: 40px;\" onClick=\"AddTags('[i]', '[/i]');\">
<input type=\"button\" value=\"Underline\" class=\"button\" style=\"width: 60px;\" onClick=\"AddTags('[u]', '[/u]');\">
<input type=\"button\" value=\"Small\" class=\"button\" style=\"width: 60px;\" onClick=\"AddTags('[small]', '[/small]');\">
<input type=\"button\" value=\"Center\" class=\"button\" style=\"width: 45px;\" onClick=\"AddTags('[center]', '[/center]');\">
<input type=\"button\" value=\"Hyperlink\" class=\"button\" style=\"width: 60px;\" onClick=\"AddTags('[url]', '[/url]');\">
<input type=\"button\" value=\"Image\" class=\"button\" style=\"width: 45px;\" onClick=\"AddTags('[img]', '[/img]');\">
<br><br>
<input type=\"checkbox\" name=\"delete\" value=\"deletepost\" $delcheck> Delete this Post";
if ($userdata[mod] == "Administrator" || $userdata[Mod] == "Moderator") {
	echo "<br><input type=\"checkbox\" name=\"deletethread\" value=\"deletethread\" $delthreaadcheck> Delete this Thread<br>
<input type=\"checkbox\" name=\"lock\" value=\"lockthread\" $lockcheck> Lock this Thread</td></tr>\n";
} else {
	echo "</td></tr>\n";
}
echo "<tr><td align=\"center\" colspan=\"2\" class=\"forumf1\">
<input type=\"submit\" name=\"savechanges\" value=\"Save Changes\" class=\"button\" style=\"width: 100px;\">
<input type=\"submit\" name=\"previewchanges\" value=\"Preview Changes\" class=\"button\" style=\"width: 100px;\"></td></tr>
</form>
</table>\n";
	closetable();
}
?>