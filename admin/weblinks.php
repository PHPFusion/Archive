<?
if ($userdata[mod] == "Administrator") {
	if (empty($stage) || ($stage == "")) {
		if (isset($_POST[addlink])) {
			// check if all 3 entries are defined
			if ($catname != "") {
				if ($catorder != "") {
					$result = dbquery("INSERT INTO weblinks VALUES('', '0', '$catname', 'category', '', '$catorder', '0')");
				}
			}
			if ($linkname != "") {
				if ($linkurl != "" && $linkorder != "" && $linkcat != "") {
					$result = dbquery("INSERT INTO weblinks VALUES('', '$linkcat', '$linkname', 'weblink', '$linkurl', '$linkorder', '0')");
				}
			}
		}
		$result = dbquery("SELECT * FROM weblinks WHERE linktype='category' ORDER BY linkorder");
		if (dbrows($result) != 0) {
			while($data = dbarray($result)) {
				$opts .= "<option value=\"$data[wlid]\">$data[linkname]</option>\n";
				$weblinks .= "<tr><td class=\"altleft\">
<span class=\"small\"><a href=\"$_SELF?sub=weblinks&stage=2&wlid=$data[wlid]&t=c\">Edit</a></span> |
<span class=\"small\"><a href=\"$_SELF?sub=weblinks&stage=3&delete=$data[wlid]&t=c\">Delete</a></span></td>
<td class=\"altmid\">$data[linkname]</td><td class=\"altmid\">Category</td><td class=\"altright\">$data[linkorder]</td></tr>\n";
				$result2 = dbquery("SELECT * FROM weblinks WHERE parentlink='$data[wlid]' AND linktype='weblink' ORDER BY linkorder");
				if (dbrows($result2) != 0) {
					while($data2 = dbarray($result2)) {
						$weblinks .= "<tr><td>
<span class=\"small\"><a href=\"$_SELF?sub=weblinks&stage=2&wlid=$data2[wlid]&t=wl\">Edit</a></span> |
<span class=\"small\"><a href=\"$_SELF?sub=weblinks&stage=3&delete=$data2[wlid]&t=wl\">Delete</a></span></td>
<td>$data2[linkname]</td><td>$data2[linkurl]</td><td>$data2[linkorder]</td></tr>\n";
					}
				}
			}
		}
		$result = dbquery("SELECT * FROM weblinks ORDER BY linkorder");
		$nextrow = dbrows($result) + 1;
		// show the resulting phtml
		opentable("Web Links");
		echo "<table align=\"center\" width=\"550\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
$weblinks
</table>\n";
		closetable();
		tablebreak();
		opentable("Add New Link");
		echo "<form name=\"layoutform\" method=\"post\" action=\"$_SELF?sub=weblinks\">
<table align=\"center\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td>New Category:</td>
<td colspan=\"2\"><input type=\"textbox\" name=\"catname\" maxlength=\"64\" class=\"textbox\" style=\"width: 200px;\"></td>
<td colspan=\"2\"><input type=\"textbox\" name=\"catorder\" maxlength=\"2\" value=\"$nextrow\" maxlength=\"2\" class=\"textbox\" style=\"width: 40px;\"></td></tr>
<tr><td>New Web Link:</td>
<td><input type=\"textbox\" name=\"linkname\" maxlength=\"64\" class=\"textbox\" style=\"width: 125px;\"></td>
<td><input type=\"textbox\" name=\"linkurl\" value=\"http://\" maxlength=\"255\" class=\"textbox\" style=\"width: 125px;\"></td>
<td><input type=\"textbox\" name=\"linkorder\" value=\"$nextrow\" maxlength=\"2\" class=\"textbox\" style=\"width: 40px;\"></td>
<td><select name=\"linkcat\" class=\"textbox\" style=\"width: 150px;\">
$opts
</select></td></tr>
</table>
<br><div align=\"center\">
<input type=\"submit\" name=\"addlink\" value=\"Add Category / Link\" class=\"button\" style=\"width: 125px;\"></div>
</form>\n";
		closetable();
	} 
	if ($stage == 2) {
		if (isset($_POST['updatelink'])) {
			if ($t == "wl") { $parent = "parentlink='$linkcat', "; }
			$result = dbquery("UPDATE weblinks SET ".$parent."linkname='$linkname', linkurl='$linkurl', linkorder='$linkorder' WHERE wlid='$wlid'");
			opentable("Update Link");
			echo "<br><div align=\"center\">Web Link Updated<br><br>
<a href=\"$_SELF?sub=weblinks\">Return to Edit Web Links</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		} else {
			$result = dbquery("SELECT * FROM weblinks WHERE wlid='$wlid'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
			}
			if ($t == "wl") {
				$result = dbquery("SELECT * FROM weblinks WHERE linktype='weblink' and wlid='$wlid'");
				$data = dbarray($result);
				$result2 = dbquery("SELECT * FROM weblinks WHERE linktype='category'");
				while($data2 = dbarray($result2)) {
					if ($data[parentlink] == $data2[wlid]) {
						$selected = " selected";
					} else {
						$selected = "";
					}
					$opts .= "<option value=\"$data2[wlid]\"$selected>$data2[linkname]</option>";
				}
			}
			opentable("Edit Link");
			echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"updateform\" method=\"post\" action=\"$_SELF?sub=weblinks&stage=2&wlid=$wlid&t=$t\">
<tr><td valign=\"top\">Link Name:</td>
<td><input type=\"textbox\" name=\"linkname\" value=\"$data[linkname]\" maxlength=\"64\" class=\"textbox\" style=\"width: 150px;\"></td></tr>
<tr><td valign=\"top\">Link URL:</td>
<td><input type=\"textbox\" name=\"linkurl\" value=\"$data[linkurl]\" maxlength=\"255\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">Link Order:</td>
<td><input type=\"textbox\" name=\"linkorder\" value=\"$data[linkorder]\" maxlength=\"2\" class=\"textbox\" style=\"width: 40px;\"></td></tr>\n";
		if ($t == "wl") {
			echo "<tr><td>Link Category:</td>
<td><select name=\"linkcat\" class=\"textbox\" style=\"width: 150px;\">
$opts</select></td></tr>\n";
		}
echo "<tr><td colspan=\"2\"><br><div align=\"center\">
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
			opentable("Delete Web Link / Category");
			// if it is a weblink, delete it
			if ($t == "wl") {
				$result = dbquery("DELETE FROM weblinks WHERE wlid='$delete' AND linktype='weblink'");
				echo "<br><div align=\"center\">The Web Link has been deleted<br><br>\n";
			} else {
				// if it is a category, check if any weblinks are defined, if so, don't delete it
				$result = dbquery("SELECT * FROM weblinks WHERE parentlink='$delete' AND linktype='weblink'");
				if (dbrows($result) == 0) {
					$result = dbquery("DELETE FROM weblinks WHERE linktype='category' AND wlid='$delete'");
					echo "<br><div align=\"center\">The Category has been deleted<br><br>\n";
				} else {
					echo "<br><div align=\"center\">This Category is not empty and cannot be deleted<br><br>\n";
				}
			}
			echo "<a href=\"$_SELF?sub=weblinks\">Return to Edit Web Links</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		}
	}
}
?>