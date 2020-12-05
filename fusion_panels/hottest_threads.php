<?php
@openside(LAN_21);
$result = dbquery("SELECT * FROM ".$fusion_prefix."threads INNER JOIN ".$fusion_prefix."forums ON ".$fusion_prefix."threads.forum_id=".$fusion_prefix."forums.forum_id WHERE forum_access<='$userdata[user_mod]' ORDER BY thread_replies DESC,thread_lastpost DESC LIMIT 5");
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		$itemsubject = trimlink($data[thread_subject], 20);
		echo "· <a href=\"fusion_forum/viewthread.php?forum_id=$data[forum_id]&thread_id=$data[thread_id]\" class=\"slink\">$itemsubject</a> [".$data[thread_replies]."]<br>\n";
	}
} else {
	echo "<center>".LAN_04."</center>\n";
}
@closeside();
?>