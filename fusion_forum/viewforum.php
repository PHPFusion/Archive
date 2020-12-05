<?
/*
---------------------------------------------------
	PHP-Fusion - Content Management System
	-------------------------------------------
	© Nick Jones (Digitanium) 2002-2004
	http://www.php-fusion.co.uk
	nick@php-fusion.co.uk
	-------------------------------------------
	Released under the terms and conditions of
	the GNU General Public License (Version 2)
---------------------------------------------------
*/
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."forum/forum_main.php";
require "navigation.php";

if (empty($lastvisited)) { $lastvisited = time(); }

// Get the Forum Category name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_cat'");
$data = dbarray($result);
$caption = stripslashes($data[forum_name]);

// Get the Forum name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
$data = dbarray($result);
$access_level = $data[forum_access];
$caption .= " | <a href='viewforum.php?forum_id=".$data['forum_id']."&forum_cat=".$data['forum_cat']."'>".$data['forum_name']."</a>";

opentable(LAN_450);

if (UserLevel() >= $access_level) {
	
echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='smallalt'>
<a href='index.php'>".$settings['sitename']."</a> |
$caption</td>\n";
if (Member()) {
	echo "<td align='right'>
<a href='post.php?action=newthread&forum_id=$forum_id&forum_cat=$forum_cat'><img src='".fusion_themedir."forum/newthread.gif' border='0'></a>
</td>\n";
}
echo "</tr>
</table>\n";

$itemsperpage = 20;
$rows = dbrows(dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE forum_id='$forum_id' AND thread_sticky='n'"));
if (!$rowstart) $rowstart = 0;
echo "<div align='center' style='margin-top:5px;margin-bottom:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"$PHP_SELF?forum_id=$forum_id&forum_cat=$forum_cat&")."
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
	$result = dbquery("SELECT ".$fusion_prefix."threads.*, tu1.user_name as user_author, tu2.user_name as user_lastuser FROM ".$fusion_prefix."threads INNER JOIN ".$fusion_prefix."users tu1 ON ".$fusion_prefix."threads.thread_author = tu1.user_id INNER JOIN ".$fusion_prefix."users tu2 ON ".$fusion_prefix."threads.thread_lastuser = tu2.user_id WHERE forum_id='$forum_id' AND thread_sticky='y' ORDER BY thread_lastpost DESC");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			$result3 = dbquery("SELECT count(thread_id) FROM ".$fusion_prefix."posts WHERE thread_id='".$data['thread_id']."' and post_datestamp>'$lastvisited'");
        		$num = dbresult($result3, 0);
			if ($data[thread_locked] == "y") {
				echo "<tr>\n<td align='center' width='25' class='tbl2'><img src='".fusion_themedir."forum/folderlock.gif'></td>";
			} else  {
	        		if ($num > 0) {
	        			$folder = "<img src='".fusion_themedir."forum/foldernew.gif'>";
	        		} else if ($num > 19) {
        				$folder = "<img src='".fusion_themedir."forum/folderhot.gif'>";
	        		} else {
	        			$folder = "<img src='".fusion_themedir."forum/folder.gif'>";
	        		}
				echo "<tr>\n<td align='center' width='25' class='tbl2'>$folder</td>";
			}
			echo "<td class='tbl1'><img src='".fusion_themedir."forum/stickythread.gif'>
<a href='viewthread.php?forum_id=$forum_id&forum_cat=$forum_cat&thread_id=".$data['thread_id']."'>".$data['thread_subject']."</a></td>
<td class='tbl2'><a href='../profile.php?lookup=".$data['thread_author']."'>".$data['user_author']."</a></td>
<td align='center' class='tbl1'>".$data['thread_views']."</td>
<td align='center' class='tbl2'>".$data['thread_replies']."</td>
<td class='tbl1'>".strftime($settings['forumdate'], $data['thread_lastpost']+($settings['timeoffset']*3600))."<br>
<span class='small'>".LAN_406."<a href='../profile.php?lookup=".$data['thread_lastuser']."'>".$data['user_lastuser']."</a></span></td>
</tr>\n";
		}
	} else {
		$threadcount = 0;
	}
}

if ($rows != 0) {
	$result = dbquery("SELECT ".$fusion_prefix."threads.*, tu1.user_name as user_author, tu2.user_name as user_lastuser FROM ".$fusion_prefix."threads INNER JOIN ".$fusion_prefix."users tu1 ON ".$fusion_prefix."threads.thread_author = tu1.user_id INNER JOIN ".$fusion_prefix."users tu2 ON ".$fusion_prefix."threads.thread_lastuser = tu2.user_id WHERE forum_id='$forum_id' AND thread_sticky='n' ORDER BY thread_lastpost DESC LIMIT $rowstart,$itemsperpage");
	$numrows = dbrows($result);
	while ($data = dbarray($result)) {
		$result3 = dbquery("SELECT count(thread_id) FROM ".$fusion_prefix."posts WHERE thread_id='".$data['thread_id']."' and post_datestamp>'$lastvisited'");
        	$num = dbresult($result3, 0);
		if ($data[thread_locked] == "y") {
			echo "<tr>
<td align='center' width='25' class='tbl2'><img src='".fusion_themedir."forum/folderlock.gif'></td>";
		} else  {
        		if ($num > 0) {
        			$folder = "<img src='".fusion_themedir."forum/foldernew.gif'>";
        		} else if ($num > 19) {
        			$folder = "<img src='".fusion_themedir."forum/folderhot.gif'>";
        		} else {
        			$folder = "<img src='".fusion_themedir."forum/folder.gif'>";
        		}
			echo "<tr>
<td align='center' width='25' class='tbl2'>$folder</td>";
		}
		echo "<td class='tbl1'>
<a href='viewthread.php?forum_id=$forum_id&forum_cat=$forum_cat&thread_id=".$data['thread_id']."'>".$data['thread_subject']."</a></td>
<td class='tbl2'><a href='../profile.php?lookup=".$data['thread_author']."'>".$data['user_author']."</a></td>
<td align='center' class='tbl1'>".$data['thread_views']."</td>
<td align='center' class='tbl2'>".$data['thread_replies']."</td>
<td class='tbl1'>".strftime($settings['forumdate'], $data['thread_lastpost']+($settings['timeoffset']*3600))."<br>
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
".makePageNav($rowstart,$itemsperpage,$rows,3,"$PHP_SELF?forum_id=$forum_id&forum_cat=$forum_cat&")."
</div>\n";

if (Member()) {
	echo "<div align='right' style='margin-top:5px;'>
<a href='post.php?action=newthread&forum_id=$forum_id&forum_cat=$forum_cat'><img src='".fusion_themedir."forum/newthread.gif' border='0'></a>
</div>\n";
}
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl1'>
<img src='".fusion_themedir."forum/foldernew.gif'> - ".LAN_456."(<img src='".fusion_themedir."forum/folderhot.gif'> - ".LAN_457.")<br>
<img src='".fusion_themedir."forum/folder.gif'> - ".LAN_458."<br>
<img src='".fusion_themedir."forum/folderlock.gif'> - ".LAN_459."</td>
</tr>
</table>\n";

} else {
	echo "<center><br>\n".LAN_460."<br><br>
<a href='index.php'>".LAN_511."</a>\n<br><br></center>\n";
}

closetable();

echo "</td>\n";
require "../footer.php";
?>