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

$sql = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
if (dbrows($sql) != 0) {
	while ($data = dbarray($sql)) {
		$forums = "";
		$sql2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
		if (dbrows($sql2) != 0) {
			while ($data2 = dbarray($sql2)) {
				if (checkgroup($data2['forum_access'])) {
					$moderators = "";
					$forum_mods = ($data2['forum_moderators'] ? explode(".", $data2['forum_moderators']) : "");
					if (is_array($forum_mods)) {
						for ($i=0;$i < count($forum_mods);$i++) {
							$data3 = dbarray(dbquery("SELECT user_id,user_name FROM ".$fusion_prefix."users WHERE user_id='".$forum_mods[$i]."'"));
							$moderators .= "<a href='".FUSION_BASE."profile.php?lookup=".$data3['user_id']."'>".$data3['user_name']."</a>".($i != (count($forum_mods)-1) ? ", " : "");
						}
					}
					$new_posts = dbcount("(*)", "posts", "forum_id='".$data2['forum_id']."' AND post_datestamp>'$lastvisited'");
					$thread_count = dbcount("(*)", "threads", "forum_id='".$data2['forum_id']."'");
					$posts_count = dbcount("(*)", "posts", "forum_id='".$data2['forum_id']."'");
					if ($new_posts > 0) { $fim = "<img src='".FUSION_THEME."forum/foldernew.gif'>"; } else { $fim = "<img src='".FUSION_THEME."forum/folder.gif'>"; }
	        			$forums .= "<tr>
<td align='center' class='tbl2'>$fim</td>
<td class='tbl1'><a href='viewforum.php?forum_id=".$data2['forum_id']."'>".$data2['forum_name']."</a><br>
<span class='small'>".$data2['forum_description'].($moderators ? "<br>\n".LAN_411.$moderators."</span></td>\n" : "</span></td>\n")."
<td align='center' class='tbl2'>".$thread_count."</td>
<td align='center' class='tbl1'>".$posts_count."</td>
<td class='tbl2'>";
					if ($data2['forum_lastpost'] == 0) {
						$forums .=  LAN_405."</td>\n</tr>\n";
					} else {
						$data3 = dbarray(dbquery("SELECT user_name FROM ".$fusion_prefix."users WHERE user_id='".$data2['forum_lastuser']."'"));
						$forums .= showdate("forumdate", $data2['forum_lastpost'])."<br>
<span class='small'>".LAN_406."<a href='".FUSION_BASE."profile.php?lookup=".$data2['forum_lastuser']."'>".$data3['user_name']."</a></span></td>
</tr>\n";
					}
				}
			}
			if ($forums != "") {
				echo "<tr>\n<td colspan='5' class='forum-caption'>".$data['forum_name']."</td>\n</tr>\n".$forums;
				unset($forums);
			}
		}
	}
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

require_once FUSION_BASE."side_right.php";
require_once FUSION_BASE."footer.php";
?>