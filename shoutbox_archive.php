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
$result = dbquery("SELECT count(shout_id) FROM ".$fusion_prefix."shoutbox");
$rows = dbresult($result, 0);
if (!$rowstart) $rowstart = 0;
if ($rows != 0) {
	$result = dbquery(
		"SELECT * FROM ".$fusion_prefix."shoutbox LEFT JOIN ".$fusion_prefix."users
		ON ".$fusion_prefix."shoutbox.shout_name=".$fusion_prefix."users.user_id
		ORDER BY shout_datestamp DESC LIMIT $rowstart,$itemsperpage"
	);
	while ($data = dbarray($result)) {
		echo "<div class='shoutboxname'>";
		if ($data[user_name]) {
			echo "<a href='profile.php?lookup=".$data['shout_name']."' class='slink'>".$data['user_name']."</a><br>\n";
		} else {
			echo "<span class='shoutboxname'>".$data[shout_name]."</span><br>\n";
		}
		echo "</div>
<div>".str_replace("<br>", "", parsesmileys($data['shout_message']))."</div>
<div align='right' class='small'>".strftime($settings['shortdate'], $data['shout_datestamp']+($settings['timeoffset']*3600))."</div>\n";
	}
} else {
	echo "<center><br>
".LAN_107."<br><br>
</center>\n";
}
closetable();

echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"$PHP_SELF?")."
</div>\n";

require "side_right.php";
require "footer.php";
?>