<?
/*
---------------------------------------------------
	PHP-Fusion - Content Management System
	-------------------------------------------
	© Nick Jones (Digitanium) 2002-2004
	http://www.php-fusion.co.uk
	nick@php-fusion.co.uk
	-------------------------------------------
	Released under the terms and conditions of
	the GNU General Public License (Version 2)
---------------------------------------------------
*/
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."members-profile.php";
require "side_left.php";

opentable(LAN_400);
if (Member()) {
	$itemsperpage = 20;
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users");
	$rows = dbrows($result);
	if (!$rowstart) $rowstart = 0;
	if ($rows != 0) {
		echo "<table align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>
<td class=\"altbg\"><span>".LAN_401."</td>
<td align=\"right\" class=\"altbg\">".LAN_402."</td>
</tr>\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users ORDER BY user_mod DESC, user_name LIMIT $rowstart,$itemsperpage");
		while ($data = dbarray($result)) {
			if ($data[user_id] != $userdata[user_id]) {
				echo "<tr>
<td><a href=\"profile.php?lookup=$data[user_id]\">$data[user_name]</a></td>\n";
			} else {
				echo "<tr>
<td>$data[user_name]</td>\n";
			}
			echo "<td align=\"right\">".getmodlevel($data[user_mod])."</td>
</tr>";
		}
		echo "</table>\n";
		closetable();
		echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"small","$PHP_SELF?")."
</div>\n";
	}
} else {
	echo "<center><br>
".LAN_03."<br>
<br></center>\n";
	closetable();
}

require "side_right.php";
require "footer.php";
?>