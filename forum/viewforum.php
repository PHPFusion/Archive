<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
include LOCALE.LOCALESET."forum/main.php";

if (empty($lastvisited)) { $lastvisited = time(); }
$posts_per_page = 20;

if (!FUSION_QUERY || !$forum_id || !isNum($forum_id)) fallback("index.php");

$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='".$forum_id."'");
if (dbrows($result)) {
	$data = dbarray($result);
	if (!checkgroup($data['forum_access']) || !$data['forum_cat']) fallback("index.php");
} else {
	fallback("index.php");
}
$can_post = checkgroup($data['forum_posting']);

$fcdata = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='".$data['forum_cat']."'"));
$caption = $fcdata['forum_name']." | ".$data['forum_name'];

opentable($locale['450']);	
echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='smallalt'>
<a href='index.php'>".$settings['sitename']."</a> | $caption</td>\n";
if (iMEMBER && $can_post) {
	echo "<td align='right'>
<a href='post.php?action=newthread&forum_id=$forum_id'><img src='".THEME."forum/newthread.gif' border='0'></a>
</td>\n";
}
echo "</tr>
</table>\n";

$rows = dbrows(dbquery("SELECT * FROM ".$db_prefix."threads WHERE forum_id='$forum_id' AND thread_sticky='0'"));
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
echo "<div align='center' style='margin-top:5px;margin-bottom:5px;'>
".makePageNav($rowstart,20,$rows,3,FUSION_SELF."?forum_id=$forum_id&")."
</div>\n";

echo "<table cellspacing='0' cellpadding='0' border='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellspacing='1' cellpadding='0' width='100%'>
<tr>
<td width='20' class='tbl2'>&nbsp</td>
<td class='tbl2'>".$locale['451']."</td>
<td width='100' class='tbl2'>".$locale['452']."</td>
<td align='center' width='50' class='tbl2'>".$locale['453']."</td>
<td align='center' width='50' class='tbl2'>".$locale['454']."</td>
<td width='120' class='tbl2'>".$locale['404']."</td>
</tr>\n";

if ($rowstart == 0) {	
	$result = dbquery(
		"SELECT ".$db_prefix."threads.*, tu1.user_name as user_author, tu2.user_name as user_lastuser FROM ".$db_prefix."threads
		LEFT JOIN ".$db_prefix."users tu1 ON ".$db_prefix."threads.thread_author = tu1.user_id
		LEFT JOIN ".$db_prefix."users tu2 ON ".$db_prefix."threads.thread_lastuser = tu2.user_id
		WHERE forum_id='$forum_id' AND thread_sticky='1' ORDER BY thread_lastpost DESC"
	);
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			$new_posts = dbcount("(post_id)", "posts", "thread_id='".$data['thread_id']."' and post_datestamp>'$lastvisited'");
			$thread_replies = dbcount("(post_id)", "posts", "thread_id='".$data['thread_id']."'") - 1;
			if ($data['thread_locked']) {
				echo "<tr>\n<td align='center' width='25' class='tbl2'><img src='".THEME."forum/folderlock.gif'></td>";
			} else  {
				if ($new_posts > 0 && $new_posts < 20) {
					$folder = "<img src='".THEME."forum/foldernew.gif'>";
				} else if ($new_posts >= 20) {
					$folder = "<img src='".THEME."forum/folderhot.gif'>";
				} else {
					$folder = "<img src='".THEME."forum/folder.gif'>";
				}
				echo "<tr>\n<td align='center' width='25' class='tbl2'>$folder</td>";
			}
			$reps = dbcount("(thread_id)", "posts", "thread_id='".$data['thread_id']."'");
			$reps = ceil($reps / $posts_per_page);
			$threadsubject = "<a href='viewthread.php?forum_id=$forum_id&thread_id=".$data['thread_id']."'>".$data['thread_subject']."</a>";
			if ($reps > 1) {
				$ctr = 0; $ctr2 = 1; $pages = "";
				while ($ctr2 <= $reps) {
					$pnum = "<a href='viewthread.php?forum_id=$forum_id&thread_id=".$data['thread_id']."&rowstart=$ctr'>$ctr2</a> ";
					$pages = $pages.$pnum; $ctr = $ctr + $posts_per_page; $ctr2++;
				}
				$threadsubject .= " - (".$locale['412'].trim($pages).")";
			}
			echo "<td class='tbl1'><img src='".THEME."forum/stickythread.gif' style='vertical-align:middle;'>
$threadsubject</td>
<td class='tbl2'><a href='../profile.php?lookup=".$data['thread_author']."'>".$data['user_author']."</a></td>
<td align='center' class='tbl1'>".$data['thread_views']."</td>
<td align='center' class='tbl2'>".$thread_replies."</td>
<td class='tbl1'>".showdate("forumdate", $data['thread_lastpost'])."<br>
<span class='small'>".$locale['406']."<a href='../profile.php?lookup=".$data['thread_lastuser']."'>".$data['user_lastuser']."</a></span></td>
</tr>\n";
		}
		$threadcount = dbrows($result);
	} else {
		$threadcount = 0;
	}
}

if ($rows != 0) {
	$result = dbquery(
		"SELECT ".$db_prefix."threads.*, tu1.user_name as user_author, tu2.user_name as user_lastuser FROM ".$db_prefix."threads
		LEFT JOIN ".$db_prefix."users tu1 ON ".$db_prefix."threads.thread_author = tu1.user_id
		LEFT JOIN ".$db_prefix."users tu2 ON ".$db_prefix."threads.thread_lastuser = tu2.user_id
		WHERE forum_id='$forum_id' AND thread_sticky='0' ORDER BY thread_lastpost DESC LIMIT $rowstart,20"
	);
	$numrows = dbrows($result);
	while ($data = dbarray($result)) {
		$new_posts = dbcount("(post_id)", "posts", "thread_id='".$data['thread_id']."' and post_datestamp>'$lastvisited'");
		$thread_replies = dbcount("(post_id)", "posts", "thread_id='".$data['thread_id']."'") - 1;
		if ($data['thread_locked']) {
			echo "<tr>\n<td align='center' width='25' class='tbl2'><img src='".THEME."forum/folderlock.gif'></td>";
		} else  {
			if ($new_posts > 0 && $new_posts < 20) {
				$folder = "<img src='".THEME."forum/foldernew.gif'>";
			} else if ($new_posts >= 20) {
				$folder = "<img src='".THEME."forum/folderhot.gif'>";
			} else {
				$folder = "<img src='".THEME."forum/folder.gif'>";
			}
			echo "<tr>\n<td align='center' width='25' class='tbl2'>$folder</td>";
		}
		$reps = dbcount("(thread_id)", "posts", "thread_id='".$data['thread_id']."'");
		$reps = ceil($reps / $posts_per_page);
		$threadsubject = "<a href='viewthread.php?forum_id=$forum_id&thread_id=".$data['thread_id']."'>".$data['thread_subject']."</a>";
		if ($reps > 1) {
			$ctr = 0; $ctr2 = 1; $pages = "";
			while ($ctr2 <= $reps) {
				$pnum = "<a href='viewthread.php?forum_id=$forum_id&thread_id=".$data['thread_id']."&rowstart=$ctr'>$ctr2</a> ";
				$pages = $pages.$pnum; $ctr = $ctr + $posts_per_page; $ctr2++;
			}
			$threadsubject .= " - (".$locale['412'].trim($pages).")";
		}
		echo "<td class='tbl1'>$threadsubject</td>
<td class='tbl2'><a href='../profile.php?lookup=".$data['thread_author']."'>".$data['user_author']."</a></td>
<td align='center' class='tbl1'>".$data['thread_views']."</td>
<td align='center' class='tbl2'>".$thread_replies."</td>
<td class='tbl1'>".showdate("forumdate", $data['thread_lastpost'])."<br>
<span class='small'>".$locale['406']."<a href='../profile.php?lookup=".$data['thread_lastuser']."'>".$data['user_lastuser']."</a></span></td>
</tr>\n";
	}
} else {
	if ($threadcount == 0) {
		echo "<tr>\n<td colspan='6' class='tbl1'>".$locale['455']."</td>\n</tr>\n";
	}
}

echo "</table>
</td>
</tr>
</table>\n";

echo "<div align='center' style='margin-top:5px;margin-bottom:5px;'>
".makePageNav($rowstart,20,$rows,3,FUSION_SELF."?forum_id=$forum_id&")."
</div>\n";

if (iMEMBER && $can_post) {
	echo "<div align='right' style='margin-top:5px;'>
<a href='post.php?action=newthread&forum_id=$forum_id'><img src='".THEME."forum/newthread.gif' border='0'></a>
</div>\n";
}
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl1'>
<img src='".THEME."forum/foldernew.gif' style='vertical-align:middle;'> - ".$locale['456']."(
<img src='".THEME."forum/folderhot.gif' style='vertical-align:middle;'> - ".$locale['457']." )<br>
<img src='".THEME."forum/folder.gif' style='vertical-align:middle;'> - ".$locale['458']."<br>
<img src='".THEME."forum/folderlock.gif' style='vertical-align:middle;'> - ".$locale['459']."<br>
<img src='".THEME."forum/stickythread.gif' style='vertical-align:middle;'> - ".$locale['460']."</td>
</tr>
</table>\n";
closetable();

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>