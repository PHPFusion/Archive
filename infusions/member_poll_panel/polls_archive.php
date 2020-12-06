<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright � 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";

$result = dbquery("SELECT * FROM ".$db_prefix."polls WHERE poll_ended!='0' ORDER BY poll_id DESC");
if (dbrows($result) != 0) {
	$view_list = "";
	while ($data = dbarray($result)) {
		$view_list .= "<option value='".$data['poll_id']."'>".$data['poll_title']."</option>\n";
	}
	opentable($locale['108']);
	echo "<form name='pollsform' method='post' action='".FUSION_SELF."'>
<center>
".$locale['109']."<br>
<select name='oldpoll_id' class='textbox'>
$view_list</select>
<input type='submit' name='view' value='".$locale['110']."' class='button'>
</center>
</form>\n";
	closetable();
}
if (isset($oldpoll_id)) {
	if (!isNum($oldpoll_id)) { header("Location: ".FUSION_SELF); exit; }
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."polls WHERE poll_id='$oldpoll_id' AND poll_ended!='0'"));
	for ($i=0; $i<=9; $i++) {
		if ($data["poll_opt_".$i]) $oldpoll_option[$i] = $data["poll_opt_".$i];
	}
	$poll_archive = ""; $i = 0;
	$oldpoll_votes = dbcount("(vote_opt)", "poll_votes", "poll_id='".$data['poll_id']."'");
	while ($i < count($oldpoll_option)) {
		$oldnum_votes = dbcount("(vote_opt)", "poll_votes", "vote_opt='$i' AND poll_id='".$data['poll_id']."'");
		$oldopt_votes = ($oldpoll_votes ? number_format(100 / $oldpoll_votes * $oldnum_votes) : 0);
		$poll_archive .= $oldpoll_option[$i]."<br>
<img src='".THEME."images/pollbar.gif' alt='".$oldpoll_option[$i]."' height='12' width='$oldopt_votes%' class='poll'><br>
".$oldopt_votes."% [".$oldnum_votes." ".($oldnum_votes == 1 ? $locale['103'] : $locale['104'])."]<br>\n";
		if (iADMIN) {
			$result = dbquery(
				"SELECT tp.*,user_id,user_name FROM ".$db_prefix."poll_votes tp
				LEFT JOIN ".$db_prefix."users tu ON tp.vote_user=tu.user_id
				WHERE vote_opt='$i' AND poll_id='".$data['poll_id']."'"
			);
			if (dbrows($result)!=0) {
				$a = 1;
				$poll_archive .= "<span class='small2'>";
				while ($data2 = dbarray($result)) {
					$poll_archive .= $data2['user_name'];
					if ($a == dbrows($result)) { $poll_archive .= "<br><br>\n"; } else { $poll_archive .= ", "; }
					$a++;
				}
				$poll_archive .= "</span>";
			} else {
				$poll_archive .= "<br>\n";
			}
		}
		$i++;
	}
	tablebreak();
	opentable($locale['111);
	echo "<table align='center' width='200' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".$data['poll_title']."
<hr>
$poll_archive
<center>
".$locale['105'].$oldpoll_votes."<br>
".$locale['106'].showdate("shortdate", $data['poll_started'])."<br>
".$locale['107'].showdate("shortdate", $data['poll_ended'])."
</center>
</td>
</td>
</table>\n";
	closetable();
}

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>