<?
if ($userdata[mod] == "Administrator") {
	if (empty($stage)) {
		$result = dbquery("SELECT * FROM news ORDER BY posted DESC");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				$editlist .= "<option value=\"$data[nid]\">".stripslashes($data[subject])."</option>\n";
			}
			opentable("Edit News");
			echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$_SELF?sub=editnews&stage=2\">
<tr><td><div align=\"center\">
Please select the News you want to edit or delete.</div></td></tr>
<tr><td><div align=\"center\"><br>
<select name=\"nid\" class=\"textbox\" style=\"width: 250px;\">
$editlist
</select>
</div></td></tr>
<tr><td><div align=\"center\"><br>
<input type=\"submit\" name=\"edit\" value=\"Edit News\" class=\"button\" style=\"width: 100px;\">
<input type=\"submit\" name=\"deletenews\" value=\"Delete News\" class=\"button\" style=\"width: 100px;\">
</div></td></tr>
</form>
</table>\n";
			closetable();
		} else {
			opentable("Edit News");
			echo "<br><div align=\"center\">There are no News items to edit</div><br>\n";
			closetable();
		}
	}
	if ($stage == 2) {
		if (isset($_POST['deletenews'])) {
			$result = dbquery("SELECT * FROM news WHERE nid='$nid'");
			if (dbrows($result) != 0) {
				$result = dbquery("DELETE FROM news WHERE nid='$nid'");
			}
			opentable("Delete News");
			echo "<br><div align=\"center\">The News Item has been deleted<br><br>
<a href=\"$_SELF?sub=editnews\">Edit Another News Item</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		} else {
			$result = dbquery("SELECT * FROM news WHERE nid='$nid'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
				$subject = stripslashes($data[subject]);
				$news = stripslashes($data[news]);
				$article = stripslashes($data[extendednews]);
				opentable("Edit News");
				echo "<table align=\"center\" width=\"500\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$_SELF?sub=editnews&stage=3&nid=$nid&filename=$filename\">
<tr><td>Subject:</td></tr>
<tr><td><input type=\"textbox\" name=\"subject\" class=\"textbox\" value=\"$subject\" style=\"width: 250px;\"></td></tr>
<tr><td>News:</td></tr>
<tr><td><textarea name=\"news\" rows=\"5\" class=\"textbox\" style=\"width: 100%;\">$news</textarea></td></tr>
<tr><td>Extended News:</td></tr>
<tr><td><textarea name=\"article\" rows=\"10\" class=\"textbox\" style=\"width: 100%;\" onselect=\"storeCaret(this);\" onclick=\"storeCaret(this);\" onkeyup=\"storeCaret(this);\">$article</textarea></td></tr>
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
	}
	if ($stage == 3) {
		if (isset($_POST['save'])) {
			$result = dbquery("SELECT * FROM news WHERE nid='$nid'");
			$data = dbarray($result);			
			$subject = addslashes($subject);
			$news = addslashes($news);
			if ($article != "") {
				$article = addslashes($article);
			}
			$result = dbquery("UPDATE news SET subject='$subject', news='$news', extendednews='$article' WHERE nid='$nid'");
			$postdate = gmdate("F d Y", $data[posted]);
			$posttime = gmdate("H:i", $data[posted]);
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
<span class=\"newssubtitle\">Posted by <a href=\"mailto:$data[postemail]\" class=\"x\">".$data[postname]."</a> on $postdate at $posttime</span></td></tr>
<tr><td class=\"newsbody\">$news</td></tr>
</table>\n";
			closetablex();
			if ($article != "") {
				tablebreak();
				opentablex();
				echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"newstitle\">$subject<br>
<span class=\"newssubtitle\">Posted by <a href=\"mailto:$data[postemail]\" class=\"x\">".$data[postname]."</a> on $postdate at $posttime</span></td></tr>
<tr><td class=\"newsbody\">$article</td></tr>
</table>\n";
				closetablex();
			}
		}
	}
}
?>