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

openside(LAN_20);
echo "<div class='side-label'><b>".LAN_21."</b></div>\n";
$result = dbquery("
	SELECT * FROM ".$fusion_prefix."threads
	INNER JOIN ".$fusion_prefix."forums ON ".$fusion_prefix."threads.forum_id=".$fusion_prefix."forums.forum_id
	".$usr_grps." ORDER BY thread_lastpost DESC LIMIT 5
");
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		$itemsubject = trimlink($data['thread_subject'], 23);
		echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_FORUM."viewthread.php?forum_id=".$data['forum_id']."&thread_id=".$data['thread_id']."' title='".$data['thread_subject']."' class='side'>$itemsubject</a><br>\n";
	}
} else {
	echo "<center>".LAN_04."</center>\n";
}
echo "<div class='side-label'><b>".LAN_22."</b></div>\n";
$result = dbquery("
	SELECT tf.forum_id, tt.thread_id, tt.thread_subject, COUNT(tp.post_id) as count_posts 
	FROM ".$fusion_prefix."forums tf
	INNER JOIN ".$fusion_prefix."threads tt USING(forum_id)
	INNER JOIN ".$fusion_prefix."posts tp USING(thread_id)
	".$usr_grps." GROUP BY thread_id ORDER BY count_posts DESC, thread_lastpost DESC LIMIT 5
");
if (dbrows($result) != 0) {
	echo "<table width='100%' cellpadding='0' cellspacing='0'>\n";
	while($data = dbarray($result)) {
		$itemsubject = trimlink($data['thread_subject'], 20);
		echo "<tr>\n<td class='side-small'><img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_FORUM."viewthread.php?forum_id=".$data['forum_id']."&thread_id=".$data['thread_id']."' title='".$data['thread_subject']."' class='side'>$itemsubject</a></td>
<td align='right' class='side-small'>[".($data['count_posts']-1)."]</td>\n</tr>\n";
	}
	echo "</table>\n";
} else {
	echo "<center>".LAN_04."</center>\n";
}
unset($usr_grps);
closeside();
?>