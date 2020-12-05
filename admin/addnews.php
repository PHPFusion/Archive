<?
if ($userdata[mod] == "Administrator") {
	if (isset($_POST['save'])) {
		$subject = addslashes($subject);
		$news = addslashes($news);
		if ($article != "") {
			$article = addslashes($article);
		}
		$result = dbquery("INSERT INTO news VALUES('', '$subject', '$news', '$article', '$userdata[username]', '$userdata[email]', '$servertime', '0', '0')");
		$postdate = gmdate("F d Y", $servertime);
		$posttime = gmdate("H:i", $servertime);
		$subject = stripslashes(stripslashes($subject));
		$news = stripslashes(stripslashes($news));
		$news = nl2br($news);
		$news = str_replace("src=\"", "src=\"../", $news);
		if ($article != "") {
			$article = stripslashes(stripslashes($article));
			$article = nl2br($article);
			$article = str_replace("src=\"", "src=\"../", $article);
		}
		opentablex();
		echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"newstitle\">$subject<br>
<span class=\"newssubtitle\">Posted by <a href=\"mailto:$userdata[email]\" class=\"x\">".$userdata[username]."</a> on $postdate at $posttime</span></td></tr>
<tr><td class=\"newsbody\">$news</td></tr>
</table>\n";
		closetablex();
		if ($article != "") {
			tablebreak();
			opentablex();
			echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"newstitle\">$subject<br>
<span class=\"newssubtitle\">Posted by <a href=\"mailto:$userdata[email]\" class=\"x\">".$userdata[username]."</a> on $postdate at $posttime</span></td></tr>
<tr><td class=\"newsbody\">$article</td></tr>
</table>\n";
			closetablex();
		}
	} else {
		opentable("Add News");
		echo "<table align=\"center\" width=\"500\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$_SELF?sub=addnews\">
<tr><td>Subject:</td></tr>
<tr><td><input type=\"textbox\" name=\"subject\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td>News:</td></tr>
<tr><td><textarea name=\"news\" rows=\"5\" class=\"textbox\" style=\"width: 100%;\"></textarea></td></tr>
<tr><td>Extended News:</td></tr>
<tr><td><textarea name=\"article\" rows=\"10\" class=\"textbox\" style=\"width: 100%;\" onselect=\"storeCaret(this);\" onclick=\"storeCaret(this);\" onkeyup=\"storeCaret(this);\"></textarea></td></tr>
<tr><td><div align=\"center\">
<input type=\"button\" value=\"Mail Me\" class=\"button\" style=\"width: 55px;\" onClick=\"AddText('<a href=\u0022mailto:$settings[siteemail]\u0022>$settings[siteemail]</a>');\">
<input type=\"button\" value=\"Bold\" class=\"button\" style=\"width: 40px;\" onClick=\"AddTags('<b>', '</b>');\">
<input type=\"button\" value=\"Italic\" class=\"button\" style=\"width: 45px;\" onClick=\"AddTags('<i>', '</i>');\">
<input type=\"button\" value=\"Hyperlink\" class=\"button\" style=\"width: 65px;\" onClick=\"AddTags('<a href=\u0022\u0022 target=\u0022_blank\u0022>', '</a>');\">
<input type=\"button\" value=\"Image\" class=\"button\" style=\"width: 50px;\" onClick=\"AddText('<img src=\u0022images/\u0022 align=\u0022left\u0022>');\">
<input type=\"button\" value=\"Center\" class=\"button\" style=\"width: 50px;\" onClick=\"AddTags('<div align=\u0022center\u0022>', '</div>');\">
<input type=\"button\" value=\"Small\" class=\"button\" style=\"width: 45px;\" onClick=\"AddTags('<span class=\u0022small\u0022>', '</span>');\">
<input type=\"button\" value=\"Small2\" class=\"button\" style=\"width: 40px;\" onClick=\"AddTags('<span class=\u0022small2\u0022>', '</span>');\">
<input type=\"button\" value=\"Alt\" class=\"button\" style=\"width: 40px;\" onClick=\"AddTags('<span class=\u0022alt\u0022>', '</span>');\"><br>
<br>
<input type=\"submit\" name=\"save\" value=\"Save News\" class=\"button\" style=\"width: 75px;\">
</div></td></tr>
</form>
</table>\n";
		closetable();
	}
}
?>