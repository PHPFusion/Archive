<?php	
@openside(LAN_01);
$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links WHERE link_visibility<='".UserLevel()."' ORDER BY link_order");
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		if ($data['link_name'] != "---") {
			if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
				echo "· <a href='".$data['link_url']."' class='slink'>".$data['link_name']."</a><br>\n";
			} else {
				echo "· <a href='".fusion_basedir.$data['link_url']."' class='slink'>".$data['link_name']."</a><br>\n";
			}
		} else {
			echo "<hr class='shr'>\n";
		}
	}
} else {
	echo LAN_02;
}
@closeside();
?>