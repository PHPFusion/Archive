<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
if ($settings[displaypoll] == 1) {
	if (isset($_POST['vote'])) {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."poll_votes WHERE vote_user='$userdata[user_id].$userdata[user_name]' AND poll_id='$poll_id'");
		if (dbrows($result) == "0") {
			$result = dbquery("INSERT INTO ".$fusion_prefix."poll_votes VALUES('', '$userdata[user_id].$userdata[user_name]', '$voteoption', '$poll_id')");
			$result = dbquery("UPDATE ".$fusion_prefix."polls SET poll_votes=poll_votes+1 WHERE poll_id='$poll_id'");
		}
	}
	$result = dbquery("SELECT * FROM ".$fusion_prefix."polls ORDER BY poll_started DESC LIMIT 1");
	if (dbrows($result) != 0) {
		openside(LAN_80);
		$data = dbarray($result);
		$polltitle = stripslashes($data[poll_title]);
		$count = $data[poll_optcount];
		$i = 0;
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."poll_votes WHERE vote_user='$userdata[user_id].$userdata[user_name]' AND poll_id='$data[poll_id]'");
		if (dbrows($result2) != "0") { $voted = "y"; } else { $voted = "n"; }
		if ($voted == "n" && $data[poll_ended] == 0) {
			while ($i != $count) {
				$polloption[$i] = stripslashes($data["poll_opt_".$i]);
				$poll .= "<input type=\"radio\" name=\"voteoption\" value=\"$i\"> $polloption[$i]<br><br>\n";
				$i++;
			}
			echo "<form name=\"voteform\" method=\"post\" action=\"$PHP_SELF\">
$polltitle<hr class=\"shr\"><br>
$poll<hr class=\"shr\">
<center><input type=\"hidden\" name=\"poll_id\" value=\"$data[poll_id]\">\n";
			if ($userdata[user_name] != "") {
				echo "<input type=\"submit\" name=\"vote\" value=\"".LAN_81."\" class=\"button\" style=\"width:70px\"></center>\n";
			} else {
				echo LAN_82."</center>\n";
			}
echo "</form>\n";
		} else {
			while ($i != $count) {
				$result = dbquery("SELECT count(vote_opt) FROM ".$fusion_prefix."poll_votes WHERE vote_opt='$i' AND poll_id='$data[poll_id]'");
				$numvotes = dbresult($result, 0);
				$optvotes = number_format(100/$data[poll_votes]*$numvotes);
				if ($numvotes == 1) {
					$votecount = "[1 ".LAN_83."]";
				} else {
					$votecount = "[$numvotes ".LAN_84."]";
				}
				$polloption[$i] = stripslashes($data["poll_opt_".$i]);
				$poll .= "$polloption[$i]<br>
<img src=\"".fusion_themedir."images/pollbar.gif\" height=\"12\" width=\"$optvotes%\" class=\"poll\"><br>
".$optvotes."% ".$votecount."<br><br>\n";
				$i++;
			}
			echo "$polltitle
<hr class=\"shr\">
$poll
<center>".LAN_85."$data[poll_votes]<br>
".LAN_86.strftime($settings[shortdate], $data[poll_started]+($settings[timeoffset]*3600));
			if ($data[poll_ended] > 0) {
				echo "<br>
".LAN_87.strftime($settings[shortdate], $data[poll_ended]+($settings[timeoffset]*3600))."\n";
			}
			$result = dbquery("SELECT * FROM ".$fusion_prefix."polls");
			if (dbrows($result) > 1) {
				echo "<br><br>
[<a href=\"polls_archive.php\" class=\"slink\">".LAN_88."</a>]\n";
			}
			echo "</center>\n";
		}
		closeside();
	}
}
?>