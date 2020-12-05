<?php
if ($settings['maintenance'] != "1") {
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
			echo "<a href='".fusion_basedir."profile.php?lookup=".$data['user_id']."' class='slink'>".$data['user_name']."</a>";
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
	".LAN_15."<a href='".fusion_basedir."profile.php?lookup=".$data['user_id']."' class='slink'>".$data['user_name']."</a>\n";
	@closeside();
}
?>