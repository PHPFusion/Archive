<?php
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }
if (isset($poll_id) && !isNum($poll_id)) { header("Location:index.php"); exit; }

openside(LAN_100);
if (isset($_POST['cast_vote'])) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."poll_votes WHERE vote_user='".$userdata['user_id']."' AND poll_id='$poll_id'");
	if (dbrows($result) == "0") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."poll_votes VALUES('', '".$userdata['user_id']."', '$voteoption', '$poll_id')");
		header("Location: ".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : ""));
	}
}
$result = dbquery("SELECT * FROM ".$fusion_prefix."polls ORDER BY poll_started DESC LIMIT 1");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$poll_title = $data['poll_title'];
	for ($i=0; $i<=9; $i++) {
		if ($data["poll_opt_".$i]) $poll_option[$i] = $data["poll_opt_".$i];
	}
	if (iMEMBER) $result2 = dbquery("SELECT * FROM ".$fusion_prefix."poll_votes WHERE vote_user='".$userdata['user_id']."' AND poll_id='".$data['poll_id']."'");
	if ((!iMEMBER || !dbrows($result2)) && $data['poll_ended'] == 0) {
		$poll = ""; $i = 0; $num_opts = count($poll_option);
		while ($i < $num_opts) {
			$poll .= "<input type='radio' name='voteoption' value='$i'> $poll_option[$i]<br><br>\n";
			$i++;
		}
		echo "<form name='voteform' method='post' action='".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "")."'>
<b>$poll_title</b><br><br>
$poll<center><input type='hidden' name='poll_id' value='".$data['poll_id']."'>\n";
		if (iMEMBER) {
			echo "<input type='submit' name='cast_vote' value='".LAN_101."' class='button'></center>\n";
		} else {
			echo LAN_102."</center>\n";
		}
		echo "</form>\n";
	} else {
		$poll =  ""; $i = 0; $num_opts = count($poll_option);
		$poll_votes = dbcount("(vote_opt)", "poll_votes", "poll_id='".$data['poll_id']."'");
		while ($i < $num_opts) {
			$num_votes = dbcount("(vote_opt)", "poll_votes", "vote_opt='$i' AND poll_id='".$data['poll_id']."'");
			$opt_votes = ($poll_votes ? number_format(100 / $poll_votes * $num_votes) : 0);
			$poll .= "<div>".$poll_option[$i]."</div>
<div><img src='".FUSION_THEME."images/pollbar.gif' alt='".$poll_option[$i]."' height='12' width='$opt_votes' class='poll'></div>
<div>".$opt_votes."% [".$num_votes." ".($num_votes == 1 ? LAN_103 : LAN_104)."]</div><br>\n";
			$i++;
		}
		echo "<b>".$poll_title."</b><br><br>
$poll
<center>".LAN_105.$poll_votes."<br>
".LAN_106.showdate("shortdate", $data['poll_started']);
		if ($data['poll_ended'] > 0) {
			echo "<br>\n".LAN_107.showdate("shortdate", $data['poll_ended'])."\n";
		}
		$result = dbquery("SELECT * FROM ".$fusion_prefix."polls");
		if (dbrows($result) > 1) {
			echo "<br><br><img src='".FUSION_THEME."images/bullet.gif'>
<a href='".FUSION_INFUSIONS."member_poll_panel/polls_archive.php' class='side'>".LAN_108."</a> <img src='".FUSION_THEME."images/bulletb.gif'>\n";
		}
		echo "</center>\n";
	}
} else {
	echo "<center>".LAN_04."</center>\n";
}
closeside();
?>