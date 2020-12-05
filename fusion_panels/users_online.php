<?php
@openside(LAN_10);
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
@closeside();
?>