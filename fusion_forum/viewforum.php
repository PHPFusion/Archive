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
@require_once "../fusion_config.php";
require_once "../fusion_core.php";
require_once FUSION_BASE."subheader.php";
include FUSION_LANGUAGES.FUSION_LAN."forum/forum_main.php";
require_once FUSION_BASE."side_left.php";

if (empty($lastvisited)) { $lastvisited = time(); }

if (!FUSION_QUERY || !$forum_id || !isNum($forum_id)) { header("Location: index.php"); exit; }

$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='".$forum_id."'");
if (dbrows($result)) {
	$data = dbarray($result);
	if (!checkgroup($data['forum_access']) || !$data['forum_cat']) { header("Location: index.php"); exit; }
} else {
	header("Location: index.php"); exit;
}
$can_post = checkgroup($data['forum_posting']);

$fcdata = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='".$data['forum_cat']."'"));
$caption = $fcdata['forum_name']." | ".$data['forum_name'];

opentable(LAN_450);	
echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='smallalt'>
<a href='index.php'>".$settings['sitename']."</a> | $caption</td>\n";
if (iMEMBER && $can_post) {
	echo "<td align='right'>
<a href='post.php?action=newthread&forum_id=$forum_id'><img src='".FUSION_THEME."forum/newthread.gif' border='0'></a>
</td>\n";
}
echo "</tr>
</table>\n";

$rows = dbrows(dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE forum_id='$forum_id' AND thread_sticky='0'"));
if (!isset($rowstart)) $rowstart = 0;
echo "<div align='center' style='margin-top:5px;margin-bottom:5px;'>
".makePageNav($rowstart,20,$rows,3,FUSION_SELF."?forum_id=$forum_id&")."
</div>\n";

echo "<table cellspacing='0' cellpadding='0' border='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellspacing='1' cellpadding='0' width='100%'>
<tr>
<td width='20' class='tbl2'>&nbsp</td>
<td class='tbl2'>".LAN_451."</td>
<td width='100' class='tbl2'>".LAN_452."</td>
<td align='center' width='50' class='tbl2'>".LAN_453."</td>
<td align='center' width='50' class='tbl2'>".LAN_454."</td>
<td width='120' class='tbl2'>".LAN_404."</td>
</tr>\n";

if ($rowstart == 0) {	
	$result = dbquery(
		"SELECT ".$fusion_prefix."threads.*, tu1.user_name as user_author, tu2.user_name as user_lastuser FROM ".$fusion_prefix."threads
		LEFT JOIN ".$fusion_prefix."users tu1 ON ".$fusion_prefix."threads.thread_author = tu1.user_id
		LEFT JOIN ".$fusion_prefix."users tu2 ON ".$fusion_prefix."threads.thread_lastuser = tu2.user_id
		WHERE forum_id='$forum_id' AND thread_sticky='1' ORDER BY thread_lastpost DESC"
	);
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			$new_posts = dbcount("(*)", "posts", "thread_id='".$data['thread_id']."' and post_datestamp>'$lastvisited'");
			$thread_replies = dbcount("(*)", "posts", "thread_id='".$data['thread_id']."'") - 1;
			if ($data['thread_locked']) {
				echo "<tr>\n<td align='center' width='25' class='tbl2'><img src='".FUSION_THEME."forum/folderlock.gif'></td>";
			} else  {
	        		if ($new_posts > 0) {
	        			$folder = "<img src='".FUSION_THEME."forum/foldernew.gif'>";
	        		} else if ($new_posts > 19) {
        				$folder = "<img src='".FUSION_THEME."forum/folderhot.gif'>";
	        		} else {
	        			$folder = "<img src='".FUSION_THEME."forum/folder.gif'>";
	        		}
				echo "<tr>\n<td align='center' width='25' class='tbl2'>$folder</td>";
			}
			echo "<td class='tbl1'><img src='".FUSION_THEME."forum/stickythread.gif'>
<a href='viewthread.php?forum_id=$forum_id&thread_id=".$data['thread_id']."'>".$data['thread_subject']."</a></td>
<td class='tbl2'><a href='../profile.php?lookup=".$data['thread_author']."'>".$data['user_author']."</a></td>
<td align='center' class='tbl1'>".$data['thread_views']."</td>
<td align='center' class='tbl2'>".$thread_replies."</td>
<td class='tbl1'>".showdate("forumdate", $data['thread_lastpost'])."<br>
<span class='small'>".LAN_406."<a href='../profile.php?lookup=".$data['thread_lastuser']."'>".$data['user_lastuser']."</a></span></td>
</tr>\n";
		}
	} else {
		$threadcount = 0;
	}
}

if ($rows != 0) {
	$result = dbquery(
		"SELECT ".$fusion_prefix."threads.*, tu1.user_name as user_author, tu2.user_name as user_lastuser FROM ".$fusion_prefix."threads
		LEFT JOIN ".$fusion_prefix."users tu1 ON ".$fusion_prefix."threads.thread_author = tu1.user_id
		LEFT JOIN ".$fusion_prefix."users tu2 ON ".$fusion_prefix."threads.thread_lastuser = tu2.user_id
		WHERE forum_id='$forum_id' AND thread_sticky='0' ORDER BY thread_lastpost DESC LIMIT $rowstart,20"
	);
	$numrows = dbrows($result);
	while ($data = dbarray($result)) {
		$new_posts = dbcount("(*)", "posts", "thread_id='".$data['thread_id']."' and post_datestamp>'$lastvisited'");
		$thread_replies = dbcount("(*)", "posts", "thread_id='".$data['thread_id']."'") - 1;
		if ($data['thread_locked']) {
			echo "<tr>\n<td align='center' width='25' class='tbl2'><img src='".FUSION_THEME."forum/folderlock.gif'></td>";
		} else  {
        		if ($new_posts > 0) {
        			$folder = "<img src='".FUSION_THEME."forum/foldernew.gif'>";
        		} else if ($new_posts > 19) {
        			$folder = "<img src='".FUSION_THEME."forum/folderhot.gif'>";
        		} else {
        			$folder = "<img src='".FUSION_THEME."forum/folder.gif'>";
        		}
			echo "<tr>
<td align='center' width='25' class='tbl2'>$folder</td>";
		}
		$reps = dbcount("(thread_id)", "posts", "thread_id='".$data['thread_id']."'");
		$reps = ceil($reps / 20);
		$threadsubject = "<a href='viewthread.php?forum_id=$forum_id&thread_id=".$data['thread_id']."'>".$data['thread_subject']."</a>";
		if ($reps > 1) {
			$ctr = 0; $ctr2 = 1; $pages = "";
			while ($ctr2 <= $reps) {
				$pnum = "<a href='viewthread.php?forum_id=$forum_id&thread_id=".$data['thread_id']."&rowstart=$ctr'>$ctr2</a> ";
				$pages = $pages.$pnum; $ctr = $ctr+20; $ctr2++;
			}
			$threadsubject .= " - (Page: ".trim($pages).")";
		}
		echo "<td class='tbl1'>$threadsubject</td>
<td class='tbl2'><a href='../profile.php?lookup=".$data['thread_author']."'>".$data['user_author']."</a></td>
<td align='center' class='tbl1'>".$data['thread_views']."</td>
<td align='center' class='tbl2'>".$thread_replies."</td>
<td class='tbl1'>".showdate("forumdate", $data['thread_lastpost'])."<br>
<span class='small'>".LAN_406."<a href='../profile.php?lookup=".$data['thread_lastuser']."'>".$data['user_lastuser']."</a></span></td>
</tr>\n";
	}
} else {
	if ($threadcount == 0) {
		echo "<tr>\n<td colspan='6' class='tbl1'>".LAN_455."</td>\n</tr>\n";
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
<a href='post.php?action=newthread&forum_id=$forum_id'><img src='".FUSION_THEME."forum/newthread.gif' border='0'></a>
</div>\n";
}
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl1'>
<img src='".FUSION_THEME."forum/foldernew.gif'> - ".LAN_456."(<img src='".FUSION_THEME."forum/folderhot.gif'> - ".LAN_457.")<br>
<img src='".FUSION_THEME."forum/folder.gif'> - ".LAN_458."<br>
<img src='".FUSION_THEME."forum/folderlock.gif'> - ".LAN_459."</td>
</tr>
</table>\n";
closetable();

require_once FUSION_BASE."side_right.php";
require_once FUSION_BASE."footer.php";
?>