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
@require_once "../fusion_config.php";
require_once "../fusion_core.php";
require_once FUSION_BASE."subheader.php";
require_once FUSION_ADMIN."navigation.php";
include FUSION_ADMIN."admin_panels.php";

if (!iADMIN) { header("Location:../index.php"); exit; }

function showmainpanels($admin_title, $admin_link, $admin_right) {
	global $column; $tmp = ""; $row1 = ""; $row2 = "";	
	if (checkrights($admin_right)) {
                if (!$column) $row1 = "<tr>\n";
                if ($column == 2) { $row2 = "</tr>\n"; $column=-1;}
                $column++;
		$tmp .= $row1."<td><img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_ADMIN.$admin_link."'>$admin_title</a></td>\n".$row2;
	}
	return $tmp;
}

opentable(LAN_200);
echo "<table align='center' width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n";
while(list($key, $admin_info) = each($admin_panels)){
        if ($admin_info[1] != "empty") echo showmainpanels($admin_info[0], $admin_info[1], $admin_info[2]);
}
echo "</tr>";
if (checkrights("S")) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."infusions WHERE inf_installed='1' AND inf_admin!='' ORDER BY inf_name");
	if (dbrows($result)!=0) {
		echo "<tr>\n<td colspan='3'><hr></td>\n</tr>\n";
		$column = 0; $row1 = ""; $row2 = "";
		while ($data = dbarray($result)) {
	                if (!$column) { $row1 = "<tr>\n"; } else { $row1 = ""; }
	                if ($column == 2) { $row2 = "</tr>\n"; $column=-1; } else { $row2 = ""; }
	                $column++;
			echo $row1."<td><img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_INFUSIONS.$data['inf_folder']."/".$data['inf_admin']."'>".$data['inf_name']."</a></td>\n".$row2;
		}
	}
	echo "</tr>\n";
}
echo "</table>\n";
closetable();
tablebreak();
opentable(LAN_250);
echo "<table align='center' width='100%'>\n<tr>\n<td width='50%' class='small'>\n";
$result = dbquery("SELECT count(user_id) FROM ".$fusion_prefix."users");
echo "<span class='alt'>".LAN_251."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(user_id) FROM ".$fusion_prefix."users WHERE user_ban > '0'");
echo "<span class='alt'>".LAN_252."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(submit_id) FROM ".$fusion_prefix."submissions WHERE submit_type='n'");
echo "<span class='alt'>".LAN_253."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(submit_id) FROM ".$fusion_prefix."submissions WHERE submit_type='a'");
echo "<span class='alt'>".LAN_254."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(submit_id) FROM ".$fusion_prefix."submissions WHERE submit_type='l'");
echo "<span class='alt'>".LAN_255."</span> ".dbresult($result, 0)."
</td>
<td valign='top' width='50%' class='small'>\n";
$result = dbquery("SELECT count(comment_id) FROM ".$fusion_prefix."comments");
echo "<span class='alt'>".LAN_256."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(shout_id) FROM ".$fusion_prefix."shoutbox");
echo "<span class='alt'>".LAN_257."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(post_id) FROM ".$fusion_prefix."posts");
echo "<span class='alt'>".LAN_258."</span> ".dbresult($result, 0)."
</td>\n</tr>\n</table>\n";
closetable();

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>