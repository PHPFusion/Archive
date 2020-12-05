<?
if ($userdata[mod] == "Administrator") {
	if (empty($stage)) {
		$result = dbquery("SELECT * FROM polls ORDER BY pollid DESC");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				$editlist .= "<option value=\"$data[pollid]\">$data[title]</option>\n";
			}
			opentable("Edit Poll");
			echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$_SELF?sub=editpoll&stage=2\">
<tr><td><div align=\"center\">
Select the Poll you want to edit:</div></td></tr>
<tr><td><div align=\"center\"><br>
<select name=\"pollid\" class=\"textbox\" style=\"width: 250px;\">
$editlist
</select></div></td></tr>
<tr><td><div align=\"center\"><br>
<input type=\"submit\" name=\"edit\" value=\"Edit Poll\" class=\"button\" style=\"width: 80px;\">
<input type=\"submit\" name=\"deletepoll\" value=\"Delete Poll\" class=\"button\" style=\"width: 80px;\">
</div></td></tr>
</form>
</table>\n";
			closetable();
		} else {
			opentable("Edit Poll");
			echo "<br><div align=\"center\">There are no Polls to edit</div><br>\n";
			closetable();
		}
	}
	if ($stage == 2) {
		if (isset($_POST['deletepoll'])) {
		$result = dbquery("SELECT * FROM polls WHERE pollid='$pollid'");
			if (dbrows($result) != 0) {
				$result = dbquery("DELETE FROM polls WHERE pollid='$pollid'");
				$result = dbquery("DELETE FROM votes WHERE pollid='$pollid'");
			}
			opentable("Delete Poll");
			echo "<div align=\"center\"><br>
The Poll has been deleted.<br>
<br>
<a href=\"cp.php?sub=editpoll\">Edit another Poll</a><br><br>
<a href=\"index.php\">Return to Admin Panel Home</a></div>\n";
			closetable();
		} else {
			$result = dbquery("SELECT * FROM polls WHERE pollid='$pollid'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
				$ended = $data[ended];
				if ($ended != 0) {
					$options = explode("|", $data[options]);
					$optioncount = count($options);
					$i = 0;
					while ($i != $optioncount) {
						if ($i != 0) { $pollvotes .= "|"; }	
							$result2 = dbquery("SELECT * FROM votes WHERE vote='$i' AND pollid='$pollid'");
							$pollvotes .= dbrows($result2);
							$i++;
					}
					$pollvotes = explode("|", $pollvotes);
					$i = 0;
					$poll = "<span class=\"small\">\n";
					while ($i != $optioncount) {
						if ($data[votes] != 0) {
							$pollvote = number_format(100/$data[votes]*$pollvotes[$i]);
						} else {
							$pollvote = 0;
						}
						$poll .= "$options[$i]<br>
<img src=\"../themes/$settings[theme]/images/pollbar.gif\" align=\"left\" height=\"12\" width=\"$pollvote\"> <span class=\"alt\">$pollvote%</span><br>\n";
						$i++;
					}
					$poll .= "</span>
<div align=\"center\"><br>This poll was closed on ".gmdate("F d Y", $data[ended])."<br>
<br>
<a href=\"cp.php?sub=editpoll\">Edit another Poll</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div>\n";
				} else {
					if (isset($_POST['addoptions'])) {
						$options = $data[options];
						$i = 0;
						while ($i != $newoptions) {
							$i++;
							$options .= "|newoption$i";
						}
						$options = explode("|", $options);
						$optioncount = count($options);
						$i = 0;
						while ($i != $optioncount) {
							$poll .= "<tr><td><input type=\"textbox\" name=\"option$i\" value=\"$options[$i]\" class=\"textbox\" style=\"width: 200px;\"></td></tr>\n";
							$i++;
						}
					} else if (isset($_POST['savechanges'])) {
						if (isset($_POST['closepoll'])) {
							$result = dbquery("UPDATE polls SET ended='$servertime' WHERE pollid='$pollid'");
							$ended = $servertime;
							$result = dbquery("SELECT * FROM polls WHERE pollid='$pollid'");
							$data = dbarray($result);
							$options = explode("|", $data[options]);
							$optioncount = count($options);
							$i = 0;
							while ($i != $optioncount) {
								if ($i != 0) { $pollvotes .= "|"; }	
									$result2 = dbquery("SELECT * FROM votes WHERE vote='$i' AND pollid='$pollid'");
									$pollvotes .= dbrows($result2);
									$i++;
							}
							$pollvotes = explode("|", $pollvotes);
							$i = 0;
							$poll = "<span class=\"small\">";
							while ($i != $optioncount) {
								if ($data[votes] != 0) {
									$pollvote = number_format(100/$data[votes]*$pollvotes[$i]);
								} else {
									$pollvote = 0;
								}
								$poll .= "$options[$i]<br>
<img src=\"../themes/$settings[theme]/images/pollbar.gif\" align=\"left\" height=\"12\" width=\"$pollvote\"> $pollvote%<br>\n";
								$i++;
							}
							$poll .= "</span>
<div align=\"center\"><br>This poll is now closed<br>
<br>
<a href=\"cp.php?sub=editpoll\">Edit another Poll</a><br><br>
<a href=\"index.php\">Return to Admin Panel Home</a></div>\n";
						} else {
							$i = 0;
							while ($i != $optioncount) {
								$opt = "option".$i;
								$opt = stripslashes($$opt);
								$poll .= "<tr><td><input type=\"textbox\" name=\"option$i\" value=\"$opt\" class=\"textbox\" style=\"width: 200px;\"></td></tr>\n";
								$i++;
								if ($i != $optioncount) { 
									$opttext .= $opt."|"; 
								} else { 
									$opttext .= $opt; 
								}
							}
							$result = dbquery("UPDATE polls SET title='$title', options='$opttext', datestamp='$servertime' WHERE pollid='$pollid'");
						}
					} else {
						$options = explode("|", $data[options]);
						$optioncount = count($options);
						$i = 0;
						while ($i != $optioncount) {
							$poll .= "<tr><td><input type=\"textbox\" name=\"option$i\" value=\"$options[$i]\" class=\"textbox\" style=\"width: 200px;\"></td></tr>\n";
							$i++;
						}
					}
					$i = 0;
					while ($i != 10) {
						$i++;
						$newopts .= "<option>$i</option>\n";
					}
				}
				opentable("Edit Poll");
				echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$_SELF?sub=editpoll&stage=2&pollid=$pollid\">
<tr><td>\n";
				if ($ended == 0) {
					echo "Poll Title:</td></tr>
<tr><td><input type=\"textbox\" name=\"title\" value=\"$data[title]\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td>Poll Options:</td></tr>
$poll
<tr><td>
<tr><td><div align=\"center\"><br>
<input type=\"submit\" name=\"addoptions\" value=\"Add More Options\" class=\"button\" style=\"width: 100px;\"> <select name=\"newoptions\" class=\"textbox\" style=\"width: 50px;\">
$newopts</select><br><br>
<input type=\"checkbox\" name=\"closepoll\" value=\"y\"> Close this Poll<br><br>
<input type=\"hidden\" name=\"optioncount\" value=\"$optioncount\">
<input type=\"submit\" name=\"savechanges\" value=\"Save Changes\" class=\"button\" style=\"width: 100px;\">
</div></td></tr>\n";
				} else {
					echo "<span class=\"small\">$data[title]</span><br><br>
$poll\n";
				}
				echo "</form>
</table>\n";
				closetable();
			}
		}
	}
}
?>