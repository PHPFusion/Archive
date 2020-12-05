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
openside(LAN_01);
$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links ORDER BY link_order");
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		if ($data[link_name] != "---") {
			if (strstr($data[link_url], "http://")) {
				echo "· <a href=\"$data[link_url]\" class=\"slink\">$data[link_name]</a><br>\n";
			} else {
				echo "· <a href=\"".fusion_basedir."$data[link_url]\" class=\"slink\">$data[link_name]</a><br>\n";
			}
		} else {
			echo "<hr class=\"shr\">\n";
		}
	}
} else {
	echo LAN_02;
}
closeside();
if ($userdata[user_name] != "") {
	openside("$userdata[user_name]");
	$result = dbquery("SELECT count(message_id) FROM ".$fusion_prefix."messages WHERE message_to='$userdata[user_id].$userdata[user_name]' AND message_read='0'");
echo "· <a href=\"".fusion_basedir."editprofile.php\" class=\"slink\">".LAN_60."</a><br>
· <a href=\"".fusion_basedir."messages.php\" class=\"slink\">".LAN_61."</a> [".dbresult($result, 0)."]<br>
· <a href=\"".fusion_basedir."members.php\" class=\"slink\">".LAN_62."</a><br>\n";
	if ($userdata[user_mod] > "1") {
		echo "· <a href=\"".fusion_basedir."fusion_admin/index.php\" class=\"slink\">".LAN_63."</a><br>\n";
	}
	echo "· <a href=\"".fusion_basedir."index.php?logout=yes\" class=\"slink\">".LAN_64."</a>\n";
} else {
	openside(LAN_65);
	echo "<div align=\"center\">$loginerror
<form name=\"loginform\" method=\"post\" action=\"$PHP_SELF\">
".LAN_66."<br>
<input type=\"textbox\" name=\"username\" class=\"textbox\" style=\"width:100px\"><br>
".LAN_67."<br>
<input type=\"password\" name=\"password\" class=\"textbox\" style=\"width:100px\"><br>
<input type=\"submit\" name=\"login\" value=\"Login\" class=\"button\" style=\"width:60px\"><br>
</form>
<br>
<a href=\"".fusion_basedir."register.php\" class=\"slink\">".LAN_68."</a><br>
<a href=\"".fusion_basedir."lostpassword.php\" class=\"slink\">".LAN_69."</a>
</div>\n";
}
closeside();
openside(LAN_10);
$result = dbquery("SELECT * FROM ".$fusion_prefix."online WHERE online_user='0.Guest'");
echo LAN_11.dbrows($result)."<br>\n";
$result = dbquery("SELECT * FROM ".$fusion_prefix."online WHERE online_user!='0.Guest'");
$members = dbrows($result);
if ($members != 0) {
	$i = 1;
	echo LAN_12;
	while($data = dbarray($result)) {
		$member = explode(".", $data[online_user]);
		echo "<a href=\"".fusion_basedir."profile.php?lookup=$member[0]\" class=\"slink\">$member[1]</a>";
		if ($i != $members) {
			echo ", ";
		}
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
".LAN_15."<a href=\"".fusion_basedir."profile.php?lookup=$data[user_id]\" class=\"slink\">".$data[user_name]."</a>\n";
closeside();
?>
