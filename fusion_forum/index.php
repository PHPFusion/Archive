<?
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
@include "../fusion_config.php";
include "../fusion_core.php";
include FUSION_BASE."subheader.php";
include FUSION_LANGUAGES.FUSION_LAN."forum/forum_main.php";
include FUSION_BASE."side_left.php";

if (empty($lastvisited)) { $lastvisited = time(); }

opentable(LAN_400);
echo "<table border='0' cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td colspan='2' class='tbl2'>".LAN_401."</td>
<td align='center' width='50' class='tbl2'>".LAN_402."</td>
<td align='center' width='50' class='tbl2'>".LAN_403."</td>
<td width='120' class='tbl2'>".LAN_404."</td>
</tr>\n";

$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		echo "<tr>\n<td colspan='5' class='forum-caption'>".$data['forum_name']."</td>\n</tr>\n";
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='".$data['forum_id']."' AND forum_access<='".iUSER."' ORDER BY forum_order");
		if (dbrows($result2) != 0) {
			while ($data2 = dbarray($result2)) {
				$new_posts = dbcount("(forum_id)", "posts", "forum_id='".$data2['forum_id']."' AND post_datestamp>'$lastvisited'");
				$thread_count = dbcount("(forum_id)", "threads", "forum_id='".$data2['forum_id']."'");
				$posts_count = dbcount("(forum_id)", "posts", "forum_id='".$data2['forum_id']."'");
				if ($new_posts > 0) { $fim = "<img src='".FUSION_THEME."forum/foldernew.gif'>"; } else { $fim = "<img src='".FUSION_THEME."forum/folder.gif'>"; }
        			echo "<tr>
<td align='center' class='tbl2'>$fim</td>
<td class='tbl1'><a href='viewforum.php?forum_id=".$data2['forum_id']."'>".$data2['forum_name']."</a><br>
<span class='small'>".$data2['forum_description']."</span></td>
<td align='center' class='tbl2'>".$thread_count."</td>
<td align='center' class='tbl1'>".$posts_count."</td>
<td class='tbl2'>";
				if ($data2[forum_lastpost] == 0) {
					echo LAN_405."</td>\n</tr>\n";
				} else {
					$data3 = dbarray(dbquery("SELECT user_name FROM ".$fusion_prefix."users WHERE user_id='".$data2['forum_lastuser']."'"));
					echo showdate("forumdate", $data2['forum_lastpost'])."<br>
<span class='small'>".LAN_406."<a href='../profile.php?lookup=$data2[forum_lastuser]'>".$data3['user_name']."</a></span></td>
</tr>\n";
				}
			}
		} else {
			echo "<tr>\n<td colspan='5' class='tbl1'>".LAN_407."</td>\n</tr>\n";
		}
	}
} else {
	echo  "<tr>\n<td colspan='5' class='tbl1'>".LAN_408."</td>\n</tr>\n";
}

echo "</table>
</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='forum'><br>
<img src='".FUSION_THEME."forum/foldernew.gif' align='left'> - ".LAN_409."<br>
<img src='".FUSION_THEME."forum/folder.gif' align='left'> - ".LAN_410."
</td><td align='right' valign='bottom' class='forum'>
<form name='search' method='post' action='".FUSION_BASE."search.php?stype=fp'>
<input type='textbox' name='stext' class='textbox' style='width:150px'>
<input type='submit' name='search' value='".LAN_550."' class='button'>
</form>
</td>
</tr>
</table>\n";
closetable();

include FUSION_BASE."side_right.php";
include FUSION_BASE."footer.php";
?>