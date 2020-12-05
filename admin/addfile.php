<?
if ($userdata[mod] == "Administrator") {
	if (empty($stage) || ($stage == "")) {
		if (isset($_POST['addfile'])) {
			$result = dbquery("INSERT INTO downloads VALUES('', '$catid', 'file', '$title', '$details', '$opsys', '$version', '$filesize', '$url', '$servertime', '0')");
		}
		$result = dbquery("SELECT * FROM downloads WHERE dlcat='$catid' AND dltype='file' ORDER BY title ASC");
		if (dbrows($result) != 0) {
			$dltable = "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">\n";
			while ($data = dbarray($result)) {
				$dltable .= "<tr><td>$data[title]</td><td align=\"right\">&middot;&nbsp;<span class=\"small\"><a href=\"$PHP_SELF?sub=addfile&stage=2&catid=$catid&dlid=$data[dlid]\">Edit</a></span>&nbsp;&middot;
<span class=\"small\"><a href=\"$PHP_SELF?sub=addfile&stage=3&catid=$catid&delete=$data[dlid]\">Delete</a></span>&nbsp;&middot;</td></tr>";
			}
			$dltable .= "</table>\n";
		} else {
			$dltable = "<table width=\"100%\">
<tr><td>There are no Downloads defined in this Category</td></tr>
</table>\n";
		}
		opentable("Category Files", "content");
		echo $dltable;
		closetable();
		tablebreak();
		opentable("Add File", "content");
		echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"addform\" method=\"post\" action=\"$PHP_SELF?sub=addfile&catid=$catid\">
<tr><td valign=\"top\">Title:</td>
<td><input type=\"textbox\" name=\"title\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">Details:</td>
<td><input type=\"textbox\" name=\"details\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">O/S:</td>
<td><input type=\"textbox\" name=\"opsys\" value=\"Windows \" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">Version:</td>
<td><input type=\"textbox\" name=\"version\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">Filesize:</td>
<td><input type=\"textbox\" name=\"filesize\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">Web Link:</td>
<td><input type=\"textbox\" name=\"url\" value=\"http://\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td colspan=\"2\"><br><div align=\"center\">
<input type=\"submit\" name=\"addfile\" value=\"Add File\" class=\"button\" style=\"width: 100px;\"></div>
</td></tr>
</form>
</table>\n";
		closetable();
	}
	if ($stage == 2) {
		if (isset($_POST['updatefile'])) {
			$result = dbquery("UPDATE downloads SET title='$title', details='$details', opsys='$opsys', version='$version', filesize='$filesize', url='$url', posted='$servertime' WHERE dlid='$dlid'");
			opentable("edit file", "content");
			echo "<br><div align=\"center\">File Updated<br><br>
<a href=\"$PHP_SELF?sub=addfile&catid=$catid\">Return to Category Files</a><br><br>
<a href=\"$PHP_SELF?sub=downloads\">Return to Downloads</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		} else {
			$result = dbquery("SELECT * FROM downloads WHERE dlid='$dlid' AND dltype='file'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
			}
			opentable("Edit File", "content");
			echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"updateform\" method=\"post\" action=\"$PHP_SELF?sub=addfile&stage=2&catid=$catid&dlid=$dlid\">
<tr><td valign=\"top\">Title:</td>
<td><input type=\"textbox\" value=\"$data[title]\" name=\"title\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">Details:</td>
<td><input type=\"textbox\" value=\"$data[details]\" name=\"details\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">O/S:</td>
<td><input type=\"textbox\" value=\"$data[opsys]\" name=\"opsys\" value=\"Windows \" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">Version:</td>
<td><input type=\"textbox\" value=\"$data[version]\" name=\"version\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">Filesize:</td>
<td><input type=\"textbox\" value=\"$data[filesize]\" name=\"filesize\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">Web Link:</td>
<td><input type=\"textbox\" value=\"$data[url]\" name=\"url\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td colspan=\"2\"><br><div align=\"center\">
<input type=\"submit\" name=\"updatefile\" value=\"Update File\" class=\"button\" style=\"width: 100px;\"></div>
</td></tr>
</form>
</table>\n";
			closetable();
		}
	}
	if ($stage == 3) {
		if (isset($delete)) {
			opentable("Delete Category", "content");
			$result = dbquery("DELETE FROM downloads WHERE dltype='file' AND dlid='$delete'");
			echo "<br><div align=\"center\">The File has been deleted<br><br>
<a href=\"$PHP_SELF?sub=addfile&catid=$catid\">Return to Category Files</a><br><br>
<a href=\"$PHP_SELF?sub=downloads\">Return to Downloads</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		}
	}
}
?>