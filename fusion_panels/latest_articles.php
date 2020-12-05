<?php
@openside(LAN_22);
$result = dbquery("SELECT * FROM ".$fusion_prefix."articles ORDER BY article_datestamp DESC LIMIT 0,5");
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		$itemsubject = trimlink($data['article_subject'], 23);
		echo "· <a href='".fusion_basedir."readarticle.php?article_id=".$data['article_id']."' class='slink'>$itemsubject</a><br>\n";
	}
} else {
	echo "<center>".LAN_04."</center>\n";
}
@closeside();
?>