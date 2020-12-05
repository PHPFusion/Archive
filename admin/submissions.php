<?
if ($userdata[mod] == "Administrator") {
	if ($stage == "" || $stage == "1") {
		if ($t == "l") {
			if (isset($delete)) {
				opentable("Delete Web Link Submission");
				$result = dbquery("DELETE FROM linksubmits WHERE slid='$delete'");
				echo "<br><div align=\"center\">The Link has been deleted<br><br>
<a href=\"$_SELF?sub=submissions\">Return to Submissions Menu</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
				closetable();
			}
		} else if ($t == "n") {
			if (isset($delete)) {
				opentable("Delete News Submission");
				$result = dbquery("DELETE FROM newssubmits WHERE snid='$delete'");
				echo "<br><div align=\"center\">The News has been deleted<br><br>
<a href=\"$_SELF?sub=submissions\">Return to Submissions Menu</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
				closetable();
			}
		} else {
			$result = dbquery("SELECT * FROM linksubmits ORDER BY submittime DESC");
			if (dbrows($result) != "0") {
				while ($data = dbarray($result)) {
					$links .= "<tr><td>$data[sitename]</td>
<td align=\"right\" width=\"100\"><span class=\"small\"><a href=\"$_SELF?sub=submissions&stage=2&t=l&slid=$data[slid]\">View</a></span> |
<span class=\"small\"><a href=\"$_SELF?sub=submissions&t=l&delete=$data[slid]\">Delete</a></span></td></tr>";
				}
			} else {
				$links = "<tr><td colspan=\"2\">No Links awaiting verification.</td></tr>";
			}
			$result = dbquery("SELECT * FROM newssubmits ORDER BY submittime DESC");
			if (dbrows($result) != "0") {
				while ($data = dbarray($result)) {
					$news .= "<tr><td>".stripslashes($data[subject])."</td>
<td align=\"right\" width=\"100\"><span class=\"small\"><a href=\"$_SELF?sub=submissions&stage=2&t=n&snid=$data[snid]\">View</a></span> |
<span class=\"small\"><a href=\"$_SELF?sub=submissions&t=n&delete=$data[snid]\">Delete</a></span></td></tr>";
				}
			} else {
				$news = "<tr><td colspan=\"2\">No News awaiting verification.</td></tr>";
			}
			opentable("Submissions");
			echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr><td class=\"altleft\">Submitted Web Links:</td><td class=\"altright\">&nbsp</td></tr>
$links
<tr><td colspan=\"2\">&nbsp;</td></tr>
<tr><td class=\"altleft\">Submitted News:</td><td class=\"altright\">&nbsp</td></tr>
$news
</table>\n";
			closetable();
		}
	}
	if ($stage == "2" && $t == "l") {
		if (isset($_POST['add'])) {
			$result = dbquery("INSERT INTO weblinks VALUES('', '$category', '$linkname', 'weblink',
			'$linkurl', '0', '0')");
			$result = dbquery("DELETE FROM linksubmits WHERE slid='$slid'");
			opentable("Add Web Link Submission");
			echo "<br><div align=\"center\">The Web Link has been added<br><br>
<a href=\"$_SELF?sub=submissions\">Return to Submissions Menu</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		} else if (isset($_POST['delete'])) {
			opentable("Delete Web Link Submission");
			$result = dbquery("DELETE FROM linksubmits WHERE slid='$slid'");
			echo "<br><div align=\"center\">The Link has been deleted<br><br>
<a href=\"$_SELF?sub=submissions\">Return to Submissions Menu</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		} else {
			$result = dbquery("SELECT * FROM weblinks WHERE linktype='category' ORDER BY linkorder");
			if (dbrows($result) != 0) {
				while($data = dbarray($result)) {
					$opts .= "<option value=\"$data[wlid]\">$data[linkname]</option>\n";
				}
			} else {
				$opts .= "<option value=\"0\">No Categories</option>\n";
			}
			$result = dbquery("SELECT * FROM linksubmits WHERE slid='$slid'");
			$data = dbarray($result);
			$posted = gmdate("d M y", $data[submittime]);
			opentable("View Submission");
			echo "<table align=\"center\" width=\"450\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr><td align=\"center\">The following link was submitted by <a href=\"mailto:$data[email]\">$data[name]</a> on $posted</td></tr>
<tr><td align=\"center\"><a href=\"$data[siteurl]\" target=\"_blank\">$data[sitename]</a> ($data[siteurl])</td></tr>
<tr><td align=\"center\"><span class=\"alt\">Category:</span> $data[category]</td></tr>
<form name=\"publish\" method=\"post\" action=\"$_SELF?sub=submissions&stage=2&t=l&slid=$slid\">
<tr><td align=\"center\"><br>
To add this Web Link check the details click the Add Link button<br><br>
<table>
<tr><td>Link Name:</td><td>Link URL</td><td>Category</td></tr>
<tr><td><input type=\"textbox\" name=\"linkname\" value=\"$data[sitename]\" class=\"textbox\" style=\"width: 100px\"></td>
<td><input type=\"textbox\" name=\"linkurl\" value=\"$data[siteurl]\" class=\"textbox\" style=\"width: 170px\"></td>
<td><select name=\"category\" class=\"textbox\" style=\"width: 130px\">
$opts</select></td></tr>
</table>
<input type=\"submit\" name=\"add\" value=\"Add Web Link\" class=\"button\"><br><br>
To delete this Web Link click this button<br>
<input type=\"submit\" name=\"delete\" value=\"Delete Web Link\" class=\"button\">
</td></tr>
</form>
</table>";
			closetable();
		}
	}
	if ($stage == "2" && $t == "n") {
		if (isset($_POST['publish'])) {
			$result = dbquery("SELECT * FROM newssubmits WHERE snid='$snid'");
			$data = dbarray($result);
			$subject = addslashes($data[subject]);
			$news = addslashes($data[news]);
			if ($data[extendednews] != "") {
				$extendednews = addslashes($data[extendednews]);
			}
			$result = dbquery("INSERT INTO news VALUES('', '$subject', '$news', '$extendednews',
			'$userdata[username]', '$userdata[email]', '$servertime', '0', '0')");
			$result = dbquery("DELETE FROM newssubmits WHERE snid='$snid'");
			opentable("Publish News Submission");
			echo "<br><div align=\"center\">The News has been published<br><br>
<a href=\"$_SELF?sub=submissions\">Return to Submissions Menu</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		} else if (isset($_POST['delete'])) {
			opentable("Delete News Submission");
			$result = dbquery("DELETE FROM newssubmits WHERE snid='$snid'");
			echo "<br><div align=\"center\">The News has been deleted<br><br>
<a href=\"$_SELF?sub=submissions\">Return to Submissions Menu</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		} else {	
			$result = dbquery("SELECT * FROM newssubmits WHERE snid='$snid'");
			$data = dbarray($result);
			$postdate = gmdate("F d Y", $data[submittime]);
			$posttime = gmdate("H:i", $data[submittime]);
			$subject = stripslashes($data[subject]);
			$news = stripslashes($data[news]);
			$news = nl2br($news);
			if ($data[extendednews] != "") {
				$extendednews = stripslashes($data[extendednews]);
				$extendednews = nl2br($extendednews);
			}
			opentablex();
			echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"newstitle\">$subject<br>
<span class=\"newssubtitle\">Posted by <a href=\"mailto:$userdatadata[email]\" class=\"x\">$userdata[username]</a> on $postdate at $posttime</span></td></tr>
<tr><td class=\"newsbody\">$news</td></tr>
</table>\n";
			closetablex();
			if ($extendednews != "") {
				tablebreak();
				opentablex();
				echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"newstitle\">$subject<br>
<span class=\"newssubtitle\">Posted by <a href=\"mailto:$userdata[email]\" class=\"x\">$userdata[username]</a> on $postdate at $posttime</span></td></tr>
<tr><td class=\"newsbody\">$extendednews</td></tr>
</table>\n";
				closetablex();
			}
			tablebreak();
			opentable("Options");
			echo "<form name=\"publish\" method=\"post\" action=\"$_SELF?sub=submissions&stage=2&t=n&snid=$snid\">
<center>
To publish this News item click this button<br>
<input type=\"submit\" name=\"publish\" value=\"Publish News\" class=\"button\"><br><br>
To delete this News click this button<br>
<input type=\"submit\" name=\"delete\" value=\"Delete News\" class=\"button\">
</center>
</form>\n";
			closetable();
		}
	}
}
?>