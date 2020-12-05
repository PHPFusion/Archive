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
require "side_left.php";

opentable(LAN_106);
$itemsperpage = 20;
$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox");
$rows = dbrows($result);
if (!$rowstart) $rowstart = 0;
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
if ($rows != 0) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox ORDER BY shout_datestamp DESC LIMIT $rowstart,$itemsperpage");
	$numrows = dbrows($result);
	$i = 1;
	while ($data = dbarray($result)) {
		$postee = explode(".", $data[shout_name]);
		echo "<tr>
<td colspan=\"2\">";
		if ($postee[0] != 0) {
			echo "<a href=\"profile.php?lookup=$postee[0]\">$postee[1]</a>";
		} else {
			echo "$postee[1]";
		}
		echo "</td>
</tr>
<tr>
<td>".str_replace("<br />", "", parsesmileys($data[shout_message]))."</td>
</tr>
<tr>
<td align=\"right\" class=\"small\">".strftime($settings[shortdate], $data[shout_datestamp]+($settings[timeoffset]*3600))."</td>
</tr>\n";
	}
} else {
	echo "<tr>
<td><center><br>
".LAN_107."<br><br>
</center></td>
</tr>\n";
}
echo "</table>\n";
closetable();

echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"small","$PHP_SELF?")."
</div>\n";

require "side_right.php";
require "footer.php";
?>