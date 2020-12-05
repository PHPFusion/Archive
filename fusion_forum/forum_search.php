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
require fusion_langdir."forum/forum_options.php";
require "navigation.php";

opentable(LAN_151);
if (isset($_POST['keywords'])) {
	if ($keywords != "") {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_access>'".UserLevel()."' ORDER BY forum_id");
		if (dbrows($result) != 0) {
			$exc_list = "";
			for ($i=1;$data=dbarray($result);$i++) {
				$exc_list .= "forum_id!='".$data['forum_id']."' ";
				if ($i != dbrows($result)) { $exc_list .= "AND "; }
			}
			$exc_list .= "AND ";
		}			
		$keywords = explode(" ", stripinput($keywords));
		$num_keywords = count($keywords);
		$query = "SELECT * FROM ".$fusion_prefix."posts INNER JOIN ".$fusion_prefix."users ON ".$fusion_prefix."posts.post_author=".$fusion_prefix."users.user_id WHERE ".$exc_list."post_message LIKE '%".$keywords['0']."%' ";
		for ($i=1;$i < $num_keywords;$i++) {
			$keywords[$i] = $keywords[$i];
			$query .= "OR post_message LIKE '%".$keywords[$i]."%' ";
		}
		$query .= "ORDER BY post_datestamp DESC";
		$result = dbquery($query);
		if (dbrows($result) != 0) {
			echo "<table border='0' cellspacing='0' cellpadding='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellspacing='1' cellpadding='0' width='100%'>
<tr>
<td class='tbl2'><b>".LAN_460."</b></td>
<td class='tbl2'><b>".LAN_461."</b></td>
<td align='center' class='tbl2' width='100'><b>".LAN_462."</b></td>
<td align='right' class='tbl2' width='120'><b>".LAN_463."</b></td>\n";
			while ($data = dbarray($result)) {
				$result2 = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE thread_id='".$data['thread_id']."' AND post_id<'".$data['post_id']."' ORDER BY post_id DESC");
				$rows = dbrows($result2);
				if ($rows > 20) {
					$rowstart = ceil($rows / 20);
					$rowstart = "rowstart=".(($rowstart-1)*20)."&";
				} else {
					$rowstart = "";
				}
				$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='".$data['forum_id']."'");
				$data2 = dbarray($result2);
				$forum_name = $data['forum_name'];
				echo "<tr>
<td class='tbl2'><a href='".fusion_basedir."fusion_forum/viewforum.php?forum_id=".$data2['forum_id']."&forum_cat=".$data2['forum_cat']."'>".$data2['forum_name']."</a></td>
<td class='tbl1'><a href='".fusion_basedir."fusion_forum/viewthread.php?".$rowstart."forum_id=".$data['forum_id']."&thread_id=".$data['thread_id']."#".$data['post_id']."'>".$data['post_subject']."</a></td>
<td align='center' class='tbl2'><a href='".fusion_basedir."profile.php?user_id=".$data['post_author']."'>".$data['user_name']."</a></td>
<td align='right' class='tbl1'>".strftime($settings['forumdate'], $data['post_datestamp']+($settings['timeoffset']*3600))."</td>
</tr>\n";
			}
			echo "</table>
</td>
</tr>
</table>\n";
		} else {
			echo "<center><br>
".LAN_464."
<br><br></center>\n";
		}
	} else {
		echo "<center><br>
".LAN_465."
<br><br></center>\n";
	}
}
closetable();
echo "</td>\n";

require fusion_basedir."footer.php";
?>