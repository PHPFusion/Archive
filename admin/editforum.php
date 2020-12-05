<?
if ($userdata[mod] == "Administrator") {
	if (isset($_POST[save])) {
		if ($t == "f") {
			$result = dbquery("UPDATE forums SET forumname='$forumname', forumorder='$roworder', fup='$forumcategory', forumdetails='$forumdetails' WHERE fid='$fid'");
			$message = "the forum details have been updated";
		} else {
			$result = dbquery("UPDATE forums SET forumname='$forumname', forumorder='$roworder', forumdetails='$forumdetails' WHERE fid='$fid'");
			$message = "the category details have been updated";
		}
		opentable("Changes Saved");
		echo "<div align=\"center\">
<br>$message<br><br>
<a href=\"$_SELF?sub=forumadmin\">Return to the Forum Admin Panel</a><br><br>
<a href=\"$_SELF?sub=forumadmin&stage=2\">Return to the Forum Layout Panel</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
		closetable();
	} else {
		if ($t == "f") {
			$result = dbquery("SELECT * FROM forums WHERE forumtype='forum' AND fid='$fid'");
			$data = dbarray($result);
			$result2 = dbquery("SELECT * FROM forums WHERE forumtype='category'");
			while($data2 = dbarray($result2)) {
				if ($data[fup] == $data2[fid]) {
					$selected = " selected";
				} else {
					$selected = "";
				}
				$opts .= "<option value=\"$data2[fid]\"$selected>$data2[forumname]</option>";
			}
		} else {
			$result = dbquery("SELECT * FROM forums WHERE forumtype='category' AND fid='$fid'");
			$data = dbarray($result);
		}
		opentable("Edit Forum");
		echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"detailsform\" method=\"post\" action=\"$_SELF?sub=editforum&fid=$fid&t=$t\">
<tr><td>Forum Name:</td>
<td><input type=\"textbox\" name=\"forumname\" value=\"$data[forumname]\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td>Row Order:</td>
<td><input type=\"textbox\" name=\"roworder\" value=\"$data[forumorder]\" class=\"textbox\" style=\"width: 35px;\"></td></tr>\n";
		if ($t == "f") {
			echo "<tr><td>Forum Category:</td>
<td><select name=\"forumcategory\" class=\"textbox\" style=\"width: 250px;\">
$opts</select></td></tr>\n";
		}
echo "<tr><td>forum details:</td>
<td><input  type=\"textbox\" name=\"forumdetails\" value=\"$data[forumdetails]\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td colspan=\"2\"><div align=\"center\"><br>
<input type=\"submit\" name=\"save\" value=\"Save Changes\" class=\"button\" style=\"width: 100px;\">
</div></td></tr>
</form>
</table>\n";
		closetable();
	}
}
?>