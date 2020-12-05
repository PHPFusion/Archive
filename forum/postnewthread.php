<?
if (isset($_POST[previewpost])) {
	if (isset($_POST[sticky])) {
		$stickycheck = "checked";
	}
	opentable("Preview New Thread - $caption");
	$subject = stripslashes($subject);
	$message = stripslashes($message);
	if ($subject == "") {
		$previewsubject = "No Subject";
	} else {
		$previewsubject = htmlentities($subject);
	}
	if ($message == "") {
		$previewmessage = "No Message, Post will be rejected if you do not include a Message";
	} else {
		$previewmessage = htmlentities($message);
		if (isset($_POST[showsig])) {
			$previewmessage = $previewmessage."\n\n".$userdata[sig];
			$sigchecked = "checked";
		}
		$previewmessage = parseubb($previewmessage);
		$previewmessage = nl2br($previewmessage);
	}
	$posted = gmdate("m.d.Y", $servertime)." at ".gmdate("H:i", $servertime);
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td valign=\"top\" width=\"145\" class=\"forumh1\">Author:</td>
<td class=\"forumh2\">Subject: $previewsubject - Posted on $posted</td></tr>
<tr><td valign=\"top\" width=\"145\" class=\"forumf1\">$userdata[username]<br>
$userdata[moderator]<br><br>
Posts: $userdata[posts]<br>
Location: $userdata[location]<br>
Joined: ".gmdate("m.d.Y", $userdata[joined])."</td>
<td valign=\"top\" height=\"70\" class=\"forumf2\">$previewmessage</td></tr>
</table>\n";
	closetable();
	tablebreak();
}
if (isset($_POST[postnewthread])) {
	if (isset($_POST[sticky])) {
		$makesticky = "y";
	} else {
		$makesticky = "n";
	}
	if (isset($_POST[showsig])) {
		$sig = "y";
	} else {
		$sig = "n";
	}
	$subject = htmlentities($subject);
	$message = htmlentities($message);
	$subject = addslashes($subject);
	$message = addslashes($message);
	if ($subject != "" && $message != "") {
		$result = dbquery("UPDATE forums SET threads=threads+1, lastpost='$servertime', lastuser='$userdata[userid]' WHERE fid='$fid'");
		$result = dbquery("INSERT INTO threads VALUES('$fid', '', '$subject', '$userdata[userid]', '0', '0', '$servertime', '$userdata[userid]', '$makesticky', 'n')");
		$tid = mysql_insert_id();
		$result = dbquery("INSERT INTO posts VALUES('$fid', '$tid', '', '$subject', '$message', '$sig', '$userdata[userid]', '$servertime', '', '0')");
		$result = dbquery("UPDATE users SET posts=posts+1 WHERE userid='$userdata[userid]'");
	} else {
		$error = "Error: You did not specify a Subject and/or Message";
	}
	opentable("Post New Thread - $caption");
	echo "<div align=\"center\">\n";
	if ($error) {
		echo "<br>$error<br><br>\n";
	} else {
		echo "<br>Your Thread has been Posted<br><br>\n";
	}
	echo "<a href=\"viewforum.php?fid=$fid&fup=$fup\">Return to Forum</a><br><br>
<a href=\"index.php\">Return to Forum Index</a><br><br></div>\n";
	closetable();
} else {
	opentable("Post New Thread - $caption");
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<form name=\"editform\" method=\"post\" action=\"$PHP_SELF?action=newthread&fid=$fid&fup=$fup\">
<tr><td valign=\"top\" width=\"145\" class=\"forumh1\">Subject:</td>
<td class=\"forumh1\"><input type=\"textbox\" name=\"subject\" value=\"$subject\" class=\"textbox\" maxlength=\"255\" style=\"width: 250px\"></td></tr>
<tr><td valign=\"top\" width=\"145\" class=\"forumf1\">Message:</td>
<td class=\"forumf1\"><textarea name=\"message\" rows=\"8\" class=\"textbox\" style=\"width: 369px\" onselect=\"storeCaret(this);\" onclick=\"storeCaret(this);\" onkeyup=\"storeCaret(this);\">$message</textarea><br>
<input type=\"button\" value=\"Bold\" class=\"button\" style=\"width: 35px;\" onClick=\"AddTags('[b]', '[/b]');\">
<input type=\"button\" value=\"Italic\" class=\"button\" style=\"width: 40px;\" onClick=\"AddTags('[i]', '[/i]');\">
<input type=\"button\" value=\"Underline\" class=\"button\" style=\"width: 60px;\" onClick=\"AddTags('[u]', '[/u]');\">
<input type=\"button\" value=\"Small\" class=\"button\" style=\"width: 60px;\" onClick=\"AddTags('[small]', '[/small]');\">
<input type=\"button\" value=\"Center\" class=\"button\" style=\"width: 45px;\" onClick=\"AddTags('[center]', '[/center]');\">
<input type=\"button\" value=\"Hyperlink\" class=\"button\" style=\"width: 60px;\" onClick=\"AddTags('[url]', '[/url]');\">
<input type=\"button\" value=\"Image\" class=\"button\" style=\"width: 45px;\" onClick=\"AddTags('[img]', '[/img]');\">
<br><br>\n";
if ($userdata[mod] == "Administrator" || $userdata[mod] == "Moderator") {
	echo "<input type=\"checkbox\" name=\"sticky\" value=\"makesticky\" $stickycheck> Make this Thread Sticky</br>\n";
}
echo "<input type=\"checkbox\" name=\"showsig\" value=\"sig\" $sigchecked> Show My Signature in this Post</td></tr>
<tr><td align=\"center\" colspan=\"2\" class=\"forumf1\">
<input type=\"submit\" name=\"postnewthread\" value=\"Post New Thread\" class=\"button\" style=\"width: 100px;\">
<input type=\"submit\" name=\"previewpost\" value=\"Preview Post\" class=\"button\" style=\"width: 100px;\"></td></tr>
</form>
</table>\n";
	closetable();
}
?>