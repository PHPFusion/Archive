<?
if ($userdata[mod] == "Administrator") {
	if (isset($_POST['save'])) {
		if (isset($linebreaks)) { $breaks = "y"; } else { $breaks = "n"; }
		$fileid = $settings[articles]+1;
		$filename = $fileid.".txt";
		$result = dbquery("INSERT INTO articles VALUES('', '$subject', '$filename', '$breaks', '$userdata[username]', '$userdata[email]', '$servertime', '0', '0')");
		$result = dbquery("UPDATE settings SET articles=articles+1");
		$postdate = gmdate("F d Y", $servertime);
		$posttime = gmdate("H:i", $servertime);
		$article = stripslashes($article);
		$file = fOpen("../articles/".$filename,"wb");
		$write = fwrite($file, $article);
		fClose($file);
		$subject = stripslashes($subject);
		if ($breaks == "y") { $article = nl2br($article); }
		$article = str_replace("src=\"", "src=\"../", $article);
		opentablex();
		echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td colspan=\"2\" class=\"newstitle\">Add Article: $subject<br>
<span class=\"newssubtitle\">Posted by <a href=\"mailto:$userdata[email]\" class=\"x\">$userdata[username]</a> on $postdate at $posttime</span></td></tr>
<tr><td colspan=\"2\" class=\"newsbody\">$article</td></tr>
</table>\n";
		closetablex();
	} else {
		opentable("Add Article");
		echo "<table align=\"center\" width=\"530\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$PHP_SELF?sub=addarticle\">
<tr><td>Subject:</td></tr>
<tr><td><input type=\"textbox\" name=\"subject\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td>Content:</td></tr>
<tr><td><textarea name=\"article\" rows=\"14\" class=\"textbox\" style=\"width: 100%;\" onselect=\"editBox='article'; storeCaret(this);\" onclick=\"editBox='article'; storeCaret(this);\" onkeyup=\"editBox='article'; storeCaret(this);\"></textarea></td></tr>
<tr><td><div align=\"center\">
<input type=\"button\" value=\"Mail Me\" class=\"button\" style=\"width: 55px;\" onClick=\"AddText('<a href=\u0022mailto:$settings[siteemail]\u0022>$settings[siteemail]</a>');\">
<input type=\"button\" value=\"Bold\" class=\"button\" style=\"width: 40px;\" onClick=\"AddTags('<b>', '</b>');\">
<input type=\"button\" value=\"Italic\" class=\"button\" style=\"width: 45px;\" onClick=\"AddTags('<i>', '</i>');\">
<input type=\"button\" value=\"Hyperlink\" class=\"button\" style=\"width: 65px;\" onClick=\"AddTags('<a href=\u0022\u0022 target=\u0022_blank\u0022>', '</a>');\">
<input type=\"button\" value=\"Image\" class=\"button\" style=\"width: 50px;\" onClick=\"AddText('<img src=\u0022images/\u0022 align=\u0022left\u0022>');\">
<input type=\"button\" value=\"Center\" class=\"button\" style=\"width: 50px;\" onClick=\"AddTags('<div align=\u0022center\u0022>', '</div>');\">
<input type=\"button\" value=\"Small\" class=\"button\" style=\"width: 45px;\" onClick=\"AddTags('<span class=\u0022small\u0022>', '</span>');\">
<input type=\"button\" value=\"Small2\" class=\"button\" style=\"width: 40px;\" onClick=\"AddTags('<span class=\u0022small2\u0022>', '</span>');\">
<input type=\"button\" value=\"Alt\" class=\"button\" style=\"width: 40px;\" onClick=\"AddTags('<span class=\u0022alt\u0022>', '</span>');\">
<input type=\"button\" value=\"Break\" class=\"button\" style=\"width: 50px;\" onClick=\"AddText('<--PAGEBREAK-->');\"><br>
<br>
<input type=\"checkbox\" name=\"linebreaks\" value=\"yes\"> Automatic Line-Breaks<br><br>
<input type=\"submit\" name=\"save\" value=\"Save Article\" class=\"button\" style=\"width: 80px;\">
</div></td></tr>
</form>
</table>\n";
		closetable();
	}
}
?>