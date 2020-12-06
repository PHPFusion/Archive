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

$result = dbquery(
	"SELECT tf.*, tt.*, tu.user_id,user_name FROM ".$db_prefix."forums tf
	INNER JOIN ".$db_prefix."threads tt USING(forum_id)
	INNER JOIN ".$db_prefix."users tu ON tt.thread_lastuser=tu.user_id
	WHERE ".groupaccess('forum_access')." ORDER BY thread_lastpost DESC LIMIT 0,".$settings['numofthreads']
);
if (dbrows($result) != 0) {
	$i=0;
	opentable($locale['031']);
	echo "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
	if ($theme_width == "100%") echo "<td class='tbl2'><span class='small'><b>".$locale['032']."</b></span></td>\n";
	echo "<td class='tbl2'><span class='small'><b>".$locale['033']."</b></span></td>
<td align='center' class='tbl2'><span class='small'><b>".$locale['034']."</b></span></td>
<td align='center' class='tbl2'><span class='small'><b>".$locale['035']."</b></span></td>
<td align='right' class='tbl2'><span class='small'><b>".$locale['036']."</b></span></td>
</tr>\n";
	while ($data = dbarray($result)) {
		if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
		$reply_count = dbcount("(thread_id)", "posts", "thread_id='".$data['thread_id']."'");
		$data2 = dbarray(dbquery("SELECT post_id FROM ".$db_prefix."posts WHERE thread_id='".$data['thread_id']."' ORDER BY post_id DESC LIMIT 1"));
		$rstart = ($reply_count > 20 ? "rowstart=".((ceil($reply_count / 20)-1)*20)."&" : "");
		echo "<tr>\n";
		if ($theme_width == "100%") {
			echo "<td width='30%' class='$row_color'><span class='small'>".$data['forum_name']."</span></td>
<td width='35%' class='$row_color'><span class='small'><a href='".FORUM."viewthread.php?".$rstart."forum_id=".$data['forum_id']."&thread_id=".$data['thread_id']."#".$data2['post_id']."' title='".$data['thread_subject']."'>".trimlink($data['thread_subject'], 30)."</a></span></td>\n";
		} else {
			echo "<td width='55%' class='$row_color'><span class='small'><a href='".FORUM."viewthread.php?".$rstart."forum_id=".$data['forum_id']."&thread_id=".$data['thread_id']."#".$data2['post_id']."' title='".$data['thread_subject']." (".$data['forum_name'].")'>".trimlink($data['thread_subject'], 30)."</a></span></td>\n";
		}
		echo "<td align='center' width='40' class='$row_color'><span class='small'>".$data['thread_views']."</span></td>
<td align='center' width='45' class='$row_color'><span class='small'>".($reply_count - 1)."</span></td>
<td align='right' class='$row_color'><span class='small'><a href='profile.php?lookup=".$data['thread_lastuser']."'>".$data['user_name']."</a></span></td>
</tr>\n";
		$i++;
	}
	echo "</table>\n";
	closetable();
}
unset($usr_grps);
?>