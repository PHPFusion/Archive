<?
if ($userdata[mod] == "Administrator") {
	if (empty($stage) || ($stage == "")) {
		if (isset($_POST[addlink])) {
			// check if all 3 entries are defined
			if ($linkname != "" && $linkurl != "" && $roworder != "") {
				$result = dbquery("INSERT INTO sitelinks VALUES('', '$linkname', '$linkurl', '$roworder')");
			}
		}
		$result = dbquery("SELECT * FROM sitelinks ORDER BY roworder");
		$nextrow = dbrows($result) + 1;
		if (dbrows($result) != 0) {
			while($data = dbarray($result)) {
				$navlinks .= "<tr><td>$data[linkname]</td><td>$data[linkurl]</td><td align=\"center\">$data[roworder]</td>
<td><span class=\"small\"><a href=\"$_SELF?sub=sitelinks&stage=2&lid=$data[lid]\">Edit</a></span> |
<span class=\"small\"><a href=\"$_SELF?sub=sitelinks&stage=3&delete=$data[lid]\">Delete</a></span></td></tr>\n";
			}
		} else {
			$navlinks .= "<tr class=\"body\" colspan=\"4\"><td>No Site Links have been defined.</td></tr>\n";
		}
		// show the resulting phtml
		opentable("Edit Site Links");
		echo "<table align=\"center\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td><span class=\"alt\">Link Name</span></td><td><span class=\"alt\">Link URL</span></td>
<td align=\"center\"><span class=\"alt\">Row Order</span></td><td><span class=\"alt\">Options</span></td></tr>
$navlinks
</table>\n";
		closetable();
		tablebreak();
		opentable("Add New Site Link");
		echo "<form name=\"layoutform\" method=\"post\" action=\"$_SELF?sub=sitelinks\">
<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td>Link Name:</td>
<td><input type=\"textbox\" name=\"linkname\" maxlength=\"64\" class=\"textbox\" style=\"width: 150px;\"></td></tr>
<tr><td>Link URL:</td>
<td><input type=\"textbox\" name=\"linkurl\" maxlength=\"255\" class=\"textbox\" style=\"width: 225px;\"></td></tr>
<tr><td>Row Order:</td>
<td><input type=\"textbox\" name=\"roworder\" value=\"$nextrow\" maxlength=\"2\" class=\"textbox\" style=\"width: 40px;\"></td></tr>
</table>
<br><div align=\"center\">
<input type=\"submit\" name=\"addlink\" value=\"Add Link\" class=\"button\" style=\"width: 100px;\"></div>
</form>\n";
		closetable();
	}
	if ($stage == 2) {
		if (isset($_POST['updatelink'])) {
			$result = dbquery("UPDATE sitelinks SET linkname='$linkname', linkurl='$linkurl', roworder='$roworder' WHERE lid='$lid'");
			opentable("Update Site Link");
			echo "<br><div align=\"center\">Site Link Updated<br><br>
<a href=\"$_SELF?sub=sitelinks\">Return to Edit Site Links</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		} else {
			$result = dbquery("SELECT * FROM sitelinks WHERE lid='$lid'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
			}
			opentable("Edit Site Link");
			echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"updateform\" method=\"post\" action=\"$_SELF?sub=sitelinks&stage=2&lid=$lid\">
<tr><td valign=\"top\">Link Name:</td>
<td><input type=\"textbox\" name=\"linkname\" value=\"$data[linkname]\" maxlength=\"64\" class=\"textbox\" style=\"width: 150px;\"></td></tr>
<tr><td valign=\"top\">Link URL:</td>
<td><input type=\"textbox\" name=\"linkurl\" value=\"$data[linkurl]\" maxlength=\"255\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">Row Order:</td>
<td><input type=\"textbox\" name=\"roworder\" value=\"$data[roworder]\" maxlength=\"2\" class=\"textbox\" style=\"width: 40px;\"></td></tr>
<tr><td colspan=\"2\"><br><div align=\"center\">
<input type=\"submit\" name=\"updatelink\" value=\"Update Link\" class=\"button\" style=\"width: 100px;\"></div>
</td></tr>
</form>
</table>\n";
			closetable();
		}
	}
	if ($stage == 3) {
		// check if delete variable is set
		if (isset($delete)) {
			opentable("Delete Site Link");
			// delete the navlink from the database
			$result = dbquery("DELETE FROM sitelinks WHERE lid='$delete'");
			echo "<br><div align=\"center\">The Site Link has been deleted<br><br>
<a href=\"$_SELF?sub=sitelinks\">Return to Edit Site Links</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		}
	}
}
?>