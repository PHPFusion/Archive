<?
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
@include "../fusion_config.php";
include "../fusion_core.php";
include FUSION_BASE."subheader.php";
include FUSION_LANGUAGES.FUSION_LAN."admin/admin_main.php";
include FUSION_ADMIN."navigation.php";

if (!iADMIN) { header("Location:../index.php"); exit; }

opentable(LAN_200);
$columns = 3; $counter = 0;
echo "<table align='center' width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n<tr>\n";
foreach ($panel_titles as $key=>$panel_link) {
	if ($counter != 0) {
		if ($counter % $columns == 0) echo "</tr>\n<tr>\n";
	}
	echo "<td valign='top' width='33%'><img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_ADMIN.$panel_link.".php'>$key</a></td>\n";
	$counter++;
}
echo "</tr>\n<tr>\n<td colspan='3'><hr></td>\n</tr>\n<td><img src='".FUSION_THEME."images/bullet.gif'> <a href='infusions.php'>".LAN_240."</a></td>\n";
$result = dbquery("SELECT * FROM ".$fusion_prefix."infusions WHERE inf_installed='1' AND inf_admin!='' ORDER BY inf_name");
if (dbrows($result)!=0) {
	$counter = 1;
	while ($data = dbarray($result)) {
		if ($counter != 0) {
			if ($counter % $columns == 0) echo "</tr>\n<tr>\n";
		}
		echo "<td valign='top' width='33%'><img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_INFUSIONS.$data['inf_folder']."/".$data['inf_admin']."'>".$data['inf_name']."</a></td>\n";
		$counter++;
	}
}
echo "</tr>\n</table>\n";
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
include "../footer.php";
?>