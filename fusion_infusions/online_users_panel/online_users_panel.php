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
if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }

if ($settings['maintenance'] != "1") {
	$cond = ($userdata['user_level'] != 0 ? "'".$userdata['user_id']."'" : "'0' AND online_ip='".FUSION_IP."'");
	$result = dbquery("SELECT * FROM ".$fusion_prefix."online WHERE online_user=".$cond."");
	if (dbrows($result) != 0) {
		$result = dbquery("UPDATE ".$fusion_prefix."online SET online_lastactive='".time()."' WHERE online_user=".$cond."");
	} else {
		$name = ($userdata['user_level'] != 0 ? $userdata['user_id'] : "0");
		$result = dbquery("INSERT INTO ".$fusion_prefix."online VALUES('$name', '".FUSION_IP."', '".time()."')");
	}
	if (isset($_POST['login'])) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."online WHERE online_user='0' AND online_ip='".FUSION_IP."'");
	} else if (isset($logout)) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."online WHERE online_ip='".FUSION_IP."'");
	}
	$result = dbquery("DELETE FROM ".$fusion_prefix."online WHERE online_lastactive<".(time()-60)."");
	openside(LAN_10);
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
	$result = dbquery("SELECT user_id,user_name FROM ".$fusion_prefix."users ORDER BY user_joined DESC");
	$total = dbrows($result);
	$data = dbarray($result);
	echo "<br>".LAN_14.$total."<br>
".LAN_15."<a href='".FUSION_BASE."profile.php?lookup=".$data['user_id']."' class='side'>".$data['user_name']."</a>\n";
	closeside();
}
?>