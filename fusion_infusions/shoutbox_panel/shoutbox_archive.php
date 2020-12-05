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
@require_once "../../fusion_config.php";
require_once "../../fusion_core.php";
require_once FUSION_BASE."subheader.php";
require_once FUSION_BASE."side_left.php";

opentable(LAN_126);
$result = dbquery("SELECT count(shout_id) FROM ".$fusion_prefix."shoutbox");
$rows = dbresult($result, 0);
if (!isset($rowstart)) $rowstart = 0;
if ($rows != 0) {
	$i = 0;
	$result = dbquery(
		"SELECT * FROM ".$fusion_prefix."shoutbox LEFT JOIN ".$fusion_prefix."users
		ON ".$fusion_prefix."shoutbox.shout_name=".$fusion_prefix."users.user_id
		ORDER BY shout_datestamp DESC LIMIT $rowstart,20"
	);
	echo "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>\n";
	while ($data = dbarray($result)) {
		echo "<tr>\n<td class='".($i% 2==0?"tbl1":"tbl2")."'><span class='comment-name'>";
		if ($data['user_name']) {
			echo "<a href='".FUSION_BASE."profile.php?lookup=".$data['shout_name']."' class='slink'>".$data['user_name']."</a>";
		} else {
			echo $data['shout_name'];
		}
		echo "</span>
<span class='small'>".LAN_41.showdate("longdate", $data['shout_datestamp'])."</span><br>
".str_replace("<br>", "", parsesmileys($data['shout_message']))."</td>\n</tr>\n";
		$i++;
	}
	echo "</table>\n";
} else {
	echo "<center><br>
".LAN_127."<br><br>
</center>\n";
}
closetable();

echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,20,$rows,3,"$PHP_SELF?")."\n</div>\n";

require_once FUSION_BASE."side_right.php";
require_once FUSION_BASE."footer.php";
?>