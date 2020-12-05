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

if (iADMIN) {
	$usr_grps = "WHERE forum_access=0 OR forum_access=250 OR forum_access=251".(iUSER_GROUPS!="" ? " OR forum_access=".str_replace(".", " OR forum_access=", iUSER_GROUPS) : "");
} elseif (iMEMBER) {
	$usr_grps = "WHERE forum_access=0 OR forum_access=250".(iUSER_GROUPS!="" ? " OR forum_access=".str_replace(".", " OR forum_access=", iUSER_GROUPS) : "");
} elseif (iGUEST) {
	$usr_grps = "WHERE forum_access=0";
}

$result = dbquery(
	"SELECT tf.*, tt.*, tu.user_id,user_name FROM ".$fusion_prefix."forums tf
	INNER JOIN ".$fusion_prefix."threads tt USING(forum_id)
	INNER JOIN ".$fusion_prefix."users tu ON tt.thread_lastuser=tu.user_id
	".$usr_grps." ORDER BY thread_lastpost DESC LIMIT 0,".$settings['numofthreads']
);
if (dbrows($result) != 0) {
	$i=0;
	opentable(LAN_31);
	echo "<table width='100%' cellpadding='0' cellspacing='0'";
	if ($theme_width == "100%") { echo " style='text-indent:2px'"; }
	echo ">\n<tr>\n";
	if ($theme_width == "100%") {
		echo "<td><b>".LAN_32."</b></td>\n";
	}
	echo "<td><b>".LAN_33."</b></td>
<td align='center'><b>".LAN_34."</b></td>
<td align='center'><b>".LAN_35."</b></td>
<td align='right'><b>".LAN_36."</b></td>
</tr>\n";
	while ($data = dbarray($result)) {
		$reply_count = dbcount("(thread_id)", "posts", "thread_id='".$data['thread_id']."'");
		$data2 = dbarray(dbquery("SELECT post_id FROM ".$fusion_prefix."posts WHERE thread_id='".$data['thread_id']."' ORDER BY post_id DESC LIMIT 1"));
		if ($reply_count > 20) {
			$rstart = ceil($reply_count / 20);
			$rstart = "rowstart=".(($rstart-1)*20)."&";
		} else {
			$rstart = "";
		}
		echo "<tr>\n";
		if ($theme_width == "100%") {
			echo "<td>".$data['forum_name']."</td>
<td><a href='".FUSION_FORUM."viewthread.php?".$rstart."forum_id=".$data['forum_id']."&thread_id=".$data['thread_id']."#".$data2['post_id']."' title='".$data['thread_subject']."'>".trimlink($data['thread_subject'], 30)."</a></td>\n";
		} else {
			echo "<td><a href='".FUSION_FORUM."viewthread.php?".$rstart."forum_id=".$data['forum_id']."&thread_id=".$data['thread_id']."#".$data2['post_id']."' title='".$data['thread_subject']."'>".trimlink($data['thread_subject'], 30)."</a><br>
<span class='small'>[".$data['forum_name']."]</span></td>\n";
		}
		echo "<td align='center'>".$data['thread_views']."</td>
<td align='center'>".($reply_count - 1)."</td>
<td align='right'><a href='profile.php?lookup=".$data['thread_lastuser']."'>".$data['user_name']."</a><br>
".showdate("forumdate", $data['thread_lastpost'])."</td>
</tr>\n";
	}
	echo "</table>\n";
	closetable();
}
unset($usr_grps);
?>