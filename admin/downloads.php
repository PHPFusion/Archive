<?
if ($userdata[mod] == "Administrator") {
	if (empty($stage) || ($stage == "")) {
		if (isset($_POST['addcategory'])) {
			$result = dbquery("INSERT INTO downloads VALUES('', '', 'category', '$catname', '', '', '', '', '', '$servertime', '0')");
		}
		$result = dbquery("SELECT * FROM downloads WHERE dltype='category' ORDER BY title ASC");
		if (dbrows($result) != 0) {
			$dltable = "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">\n";
			while ($data = dbarray($result)) {
				$dltable .= "<tr><td><span class=\"small\"><a href=\"$PHP_SELF?sub=downloads&stage=2&dlid=$data[dlid]\">Edit</a></span>
| <span class=\"small\"><a href=\"$_SELF?sub=downloads&stage=3&delete=$data[dlid]\">Delete</a></span></td></td><td>$data[title]</td>
<td align=\"right\"><span class=\"small\"><a href=\"$_SELF?sub=addfile&catid=$data[dlid]\">Edit Files</a></span></td></tr>\n";
			}
			$dltable .= "</table>\n";
		} else {
			$dltable = "<table width=\"100%\">
<tr><td>There are no Categories defined</td></tr>
</table>\n";
		}
		opentable("Download Categories");
		echo $dltable;
		closetable();
		tablebreak();
		opentable("Add Category", "content");
		echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"addform\" method=\"post\" action=\"$_SELF?sub=downloads\">
<tr><td>Category Name:</td>
<td><input type=\"textbox\" name=\"catname\" class=\"textbox\" style=\"width: 225px;\"></td></tr>
<tr><td colspan=\"2\"><br><div align=\"center\">
<input type=\"submit\" name=\"addcategory\" value=\"Add Category\" class=\"button\" style=\"width: 100px;\"></div>
</td></tr>
</form>
</table>\n";
		closetable();
	}
	if ($stage == 2) {
		if (isset($_POST['updatecategory'])) {
			$result = dbquery("UPDATE downloads SET title='$title', details='$details' WHERE dlid='$dlid'");
			opentable("Update edit category");
			echo "<br><div align=\"center\">Category Updated<br><br>
<a href=\"$_SELF?sub=downloads\">Return to Downloads</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		} else {
			$result = dbquery("SELECT * FROM downloads WHERE dlid='$dlid' AND dltype='category'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
			}
			opentable("Edit Category");
			echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"updateform\" method=\"post\" action=\"$_SELF?sub=downloads&stage=2&dlid=$dlid\">
<tr><td valign=\"top\">Category Title:</td>
<td><input type=\"textbox\" value=\"$data[title]\" name=\"title\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td valign=\"top\">Category Details:</td>
<td><input type=\"textbox\" value=\"$data[details]\" name=\"details\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td colspan=\"2\"><br><div align=\"center\">
<input type=\"submit\" name=\"updatecategory\" value=\"Update Category\" class=\"button\" style=\"width: 100px;\"></div>
</td></tr>
</form>
</table>\n";
			closetable();
		}

	}
	if ($stage == 3) {
		if (isset($delete)) {
			opentable("Delete Category");
			$result = dbquery("SELECT * FROM downloads WHERE dltype='file' AND dlcat='$delete'");
			if (dbrows($result) == 0) {
				$result = dbquery("DELETE FROM downloads WHERE dltype='category' AND dlid='$delete'");
				echo "<br><div align=\"center\">The Category has been deleted<br><br>\n";
			} else {
				echo "<br><div align=\"center\">This Category is not empty and cannot be deleted<br><br>\n";
			}
			echo "<a href=\"$_SELF?sub=downloads\">Return to Downloads</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
			closetable();
		}
	}
}
?>