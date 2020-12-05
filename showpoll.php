<?
if (isset($HTTP_POST_VARS['vote'])) {
	$result2 = dbquery("SELECT * FROM votes WHERE voteid='$userid' AND pollid='$pollid'");
	if (dbrows($result2) == "0") {
		$result = dbquery("INSERT INTO votes VALUES ('$userid', '$polloption', '$pollid')");
		$result = dbquery("UPDATE polls SET votes=votes+1 WHERE pollid='$pollid'");
	}
}
$result = dbquery("SELECT * FROM polls ORDER BY started DESC LIMIT 1");
if (dbrows($result) != "0") {
	$data = dbarray($result);
	if ($data[ended] == 0) {
		$result2 = dbquery("SELECT * FROM votes WHERE voteid='$userid' AND pollid='$data[pollid]'");
		if (dbrows($result2) != "0") { $voted = "y"; } else { $voted = "n"; }
		if (($voted == "y") || ($data[ended] != 0)) {
			$options = explode("|", $data[options]);
			$optioncount = count($options);
			$i = 0;
			while ($i != $optioncount) {
				if ($i != 0) { $pollvotes .= "|"; }	
				$result2 = dbquery("SELECT * FROM votes WHERE vote='$i' AND pollid='$data[pollid]'");
				$pollvotes .= dbrows($result2);
				$i++;
			}
			$pollvotes = explode("|", $pollvotes);
			$i = 0;
			while ($i != $optioncount) {
				if ($data[votes] != 0) {
					$pollvote = number_format(100/$data[votes]*$pollvotes[$i]);
				} else {
					$pollvote = 0;
				}
				$poll .= "$options[$i]<br>
<img src=\"themes/$settings[theme]/images/pollbar.gif\" height=\"12\" width=\"$pollvote\"> <span class=\"alt\">$pollvote%</span><br>\n";
				$i++;
			}
		} else {
			$options = explode("|", $data[options]);
			$optioncount = count($options);
			$i = 0;
			while ($i != $optioncount) {
				$poll .= "<input type=\"radio\" name=\"polloption\" value=\"$i\">".$options[$i]."<br>\n";
				$i++;
			}
		}
		opentables("Members Poll");
		echo "<form name=\"voteform\" method=\"post\" action=\"$_SELF\">
<span class=\"small\">
$data[title]<br><br>
$poll
<br>
Votes Cast: <span class=\"alt\">$data[votes]</span><br><br>";
		if ($userdata[username] != "") {
			if ($voted == "n") {
				if ($data[ended] == "0") {
					echo "<input type=\"hidden\" name=\"pollid\" value=\"$data[pollid]\">
<input type=\"submit\" name=\"vote\" value=\"Cast Vote\" class=\"button\" style=\"width: 100%;\">";
				} else {
					echo "This Poll is closed.";
				}
			} else {
				echo "Thankyou for voting.";
			}
		} else {
			echo "You must be a member to vote here.";
		}
		echo "</span>
</form>\n";
closetable();
	}
}
?>
