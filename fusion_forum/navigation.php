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
echo "<td width='$theme_width_l' valign='top' class='side-border-left'>\n";
@openside(LAN_01);
$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links WHERE link_visibility<='".iUSER."' ORDER BY link_order");
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		if ($data['link_name'] != "---") {
			if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
				echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".$data['link_url']."' class='side'>".$data['link_name']."</a><br>\n";
			} else {
				echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE.$data['link_url']."' class='side'>".$data['link_name']."</a><br>\n";
			}
		} else {
			echo "<hr class='side-hr'>\n";
		}
	}
} else {
	echo LAN_02;
}
@closeside();
if (iMEMBER) {
	openside("$userdata[user_name]");
	$result = dbquery("SELECT count(message_id) FROM ".$fusion_prefix."messages WHERE message_to='".$userdata['user_id']."' AND message_read='0'");
echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."edit_profile.php' class='side'>".LAN_60."</a><br>
<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."messages.php' class='side'>".LAN_61."</a> [".dbresult($result, 0)."]<br>
<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."members.php' class='side'>".LAN_62."</a><br>\n";
	if (iADMIN) {
		echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."fusion_admin/index.php' class='side'>".LAN_63."</a><br>\n";
	}
	echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."index.php?logout=yes' class='side'>".LAN_64."</a>\n";
} else {
	@openside(LAN_65);
	echo "<div align='center'>$loginerror
<form name='loginform' method='post' action='$PHP_SELF'>
".LAN_66."<br>
<input type='text' name='user_name' class='textbox' style='width:100px'><br>
".LAN_67."<br>
<input type='password' name='user_pass' class='textbox' style='width:100px'><br>
<input type='checkbox' name='remember_me' value='y'>".LAN_70."<br><br>
<input type='submit' name='login' value='Login' class='button'><br>
</form>
<br>
<a href='".FUSION_BASE."register.php' class='side'>".LAN_68."</a><br>
<a href='".FUSION_BASE."lostpassword.php' class='side'>".LAN_69."</a>
</div>\n";
}
@closeside();
@openside(LAN_10);
$result = dbquery("SELECT * FROM ".$fusion_prefix."online WHERE online_user='0'");
echo LAN_11.dbrows($result)."<br>\n";
$result = dbquery(
	"SELECT ton.*, user_id,user_name FROM ".$fusion_prefix."online ton
	LEFT JOIN ".$fusion_prefix."users tu ON ton.online_user=tu.user_id
	WHERE online_user!='0'"
);
$members = dbrows($result);
if ($members != 0) {
	$i = 1;
	echo LAN_12;
	while($data = dbarray($result)) {
		echo "<a href='".FUSION_BASE."profile.php?lookup=".$data['user_id']."' class='side'>".$data['user_name']."</a>";
		if ($i != $members) echo ", ";
		$i++;
	}
	echo "<br>\n";
} else {
	echo LAN_13."<br>\n";
}
$result = dbquery("SELECT * FROM ".$fusion_prefix."users ORDER BY user_joined DESC");
$total = dbrows($result);
$data = dbarray($result);
echo "<br>".LAN_14.$total."<br>
".LAN_15."<a href='".FUSION_BASE."profile.php?lookup=".$data['user_id']."' class='side'>".$data['user_name']."</a>\n";
@closeside();
@openside(LAN_151);
	echo "<center>
<form name='search' method='post' action='forum_search.php'>
".LAN_152."<br />
<input type='textbox' name='keywords' class='textbox' style='width:100%' /><br />
<input type='submit' name='search' value='".LAN_153."' class='button' />
</form>
</center>\n";
@closeside();
echo "</td>\n<td valign='top' class='main-bg'>\n";
?>
