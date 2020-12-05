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
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
		if (dbrows($result2) != 0) {
			while ($data2 = dbarray($result2)) {
				$result3 = dbquery("SELECT count(forum_id) FROM ".$fusion_prefix."posts WHERE forum_id='".$data2['forum_id']."' AND post_datestamp > '$lastvisited'");
        			$num = dbresult($result3, 0);
        			if ($num > 0) { $fim = "<img src='".fusion_themedir."forum/foldernew.gif'>"; } else { $fim = "<img src='".fusion_themedir."forum/folder.gif'>"; }
        			echo "<tr>
<td align='center' class='tbl2'>$fim</td>
<td class='tbl1'><a href='viewforum.php?forum_id=".$data2['forum_id']."&forum_cat=".$data2['forum_cat']."'>".$data2['forum_name']."</a><br>
<span class='small'>".$data2['forum_description']."</span></td>
<td align='center' class='tbl2'>".$data2['forum_threads']."</td>
<td align='center' class='tbl1'>".$data2['forum_posts']."</td>
<td class='tbl2'>";
				if ($data2[forum_lastpost] == 0) {
					echo LAN_405."</td>\n</tr>\n";
				} else {
					$data3 = dbarray(dbquery("SELECT user_name FROM ".$fusion_prefix."users WHERE user_id='".$data2['forum_lastuser']."'"));
					echo strftime($settings['forumdate'], $data2['forum_lastpost']+($settings['timeoffset']*3600))."<br>
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
<img src='".fusion_themedir."forum/foldernew.gif' align='left'> - ".LAN_409."<br>
<img src='".fusion_themedir."forum/folder.gif' align='left'> - ".LAN_410."</td>
</tr>
</table>\n";
closetable();

echo "</td>\n";
require "../footer.php";
?>