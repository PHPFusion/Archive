<?
if ($userdata[mod] == "Administrator") {
	if (empty($stage)) {
		$result = dbquery("SELECT * FROM articles ORDER BY posted DESC");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				$editlist .= "<option value=\"$data[aid]\">".stripslashes($data[subject])."</option>\n";
			}
			opentable("Edit Article");
			echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$_SELF?sub=editarticle&stage=2\">
<tr><td><div align=\"center\">
Please select the Article you want to edit or delete.</div></td></tr>
<tr><td><div align=\"center\"><br>
<select name=\"aid\" class=\"textbox\" style=\"width: 250px;\">
$editlist
</select>
</div></td></tr>
<tr><td><div align=\"center\"><br>
<input type=\"submit\" name=\"edit\" value=\"Edit Article\" class=\"button\" style=\"width: 100px;\">
<input type=\"submit\" name=\"deletearticle\" value=\"Delete Article\" class=\"button\" style=\"width: 100px;\">
</div></td></tr>
</form>
</table>\n";
			closetable();
		} else {
			opentable("Edit Article");
			echo "<br><div align=\"center\">There are no Articles to edit</div><br>\n";
			closetable();
		}
	}
	if ($stage == 2) {
		if (isset($_POST['deletearticle'])) {
			$result = dbquery("SELECT * FROM articles WHERE aid='$aid'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
				$filename = $data[filename];
				unlink("../articles/$filename");
				$result = dbquery("DELETE FROM articles WHERE aid='$aid'");
			}
			opentable("Delete Article");
			echo "<br><div align=\"center\">The Article has been deleted<br><br>
<a href=\"$_SELF?sub=editarticle\">Edit Another Article</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		} else {
			$result = dbquery("SELECT * FROM articles WHERE aid='$aid'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
				if ($data[breaks] == "y") { $breakcheck = "checked"; }
				$subject = stripslashes($data[subject]);
				$filename = $data[filename];
				$file = fOpen("../articles/$filename","rb");
				$article = fRead($file, fileSize("../articles/$filename"));
				fClose($file);
				opentable("Edit Article");
				echo "<table align=\"center\" width=\"530\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$_SELF?sub=editarticle&stage=3&aid=$aid&filename=$filename\">
<tr><td>Subject:</td></tr>
<tr><td><input type=\"textbox\" name=\"subject\" class=\"textbox\" value=\"$subject\" style=\"width: 250px;\"></td></tr>
<tr><td>Content:</td></tr>
<tr><td><textarea name=\"article\" rows=\"14\" class=\"textbox\" style=\"width: 100%;\" onselect=\"storeCaret(this);\" onclick=\"storeCaret(this);\" onkeyup=\"storeCaret(this);\">$article</textarea></td></tr>
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
<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td><input type=\"checkbox\" name=\"linebreaks\" value=\"yes\" $breakcheck> Automatic Line-Breaks<br>
<input type=\"checkbox\" name=\"update\" value=\"yes\"> Update Post Date</td></tr>
</table><br>
<input type=\"submit\" name=\"save\" value=\"Save Article\" class=\"button\" style=\"width: 80px;\">
</div></td></tr>
</form>
</table>\n";
				closetable();
			}
		}
	}
	if ($stage == 3) {
		if (isset($_POST['save'])) {
			$result = dbquery("SELECT * FROM articles WHERE aid='$aid'");
			$data = dbarray($result);			
			if (isset($linebreaks)) { $breaks = "y"; } else { $breaks = "n"; }
			$article = stripslashes($article);
			if (isset($update)) {
				$posted = ", posted='$servertime'";
				$postdate = gmdate("F d Y", $servertime);
				$posttime = gmdate("H:i", $servertime);
			} else {
				$postdate = gmdate("F d Y", $data[posted]);
				$posttime = gmdate("H:i", $data[posted]);
			}
			$result = dbquery("UPDATE articles SET subject='$subject'".$posted.", breaks='$breaks' WHERE aid='$aid'");
			$file = fOpen("../articles/".$filename,"wb");
			$write = fwrite($file, $article);
			fClose($file);
			$subject = stripslashes($subject);
			if ($breaks == "y") { $article = nl2br($article); }
			$article = str_replace("src=\"", "src=\"../", $article);
		}
		opentablex();
		echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td colspan=\"2\" class=\"newstitle\">Edit Article: $subject<br>
<span class=\"newssubtitle\">Posted by <a href=\"mailto:$data[postemail]\" class=\"x\">$data[postname]</a> on $postdate at $posttime</span></td></tr>
<tr><td colspan=\"2\" class=\"newsbody\">$article</td></tr>
</table>\n";
		closetablex();
	}
}
?>