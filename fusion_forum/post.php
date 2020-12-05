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
include FUSION_LANGUAGES.FUSION_LAN."forum/forum_post.php";
include FUSION_BASE."side_left.php";

if (!FUSION_QUERY || !$forum_id || !isNum($forum_id)) { header("Location:index.php"); exit; }

$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='".$forum_id."'");
if (dbrows($result)) {
	$fdata = dbarray($result);
	if ($fdata['forum_access'] && iUSER < $fdata['forum_access'] || !$fdata['forum_cat']) { header("Location:index.php"); exit; }
} else {
	header("Location:index.php"); exit;
}

$fcdata = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='".$fdata['forum_cat']."'"));

$caption = $fcdata['forum_name']." | <a href='viewforum.php?forum_id=".$fdata['forum_id']."'>".$fdata['forum_name']."</a>";;
$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_views=thread_views+1 WHERE thread_id='$thread_id'");

$caption = $fcdata['forum_name']." | ".$fdata['forum_name'];

if ($action == "newthread") {
	include "postnewthread.php";
} elseif ($action == "reply") {
	if (!$thread_id || !isNum($thread_id)) { header("Location:index.php"); exit; }

	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE thread_id='".$thread_id."' AND forum_id='".$fdata['forum_id']."'");
	if (dbrows($result)) { $tdata = dbarray($result); } else { header("Location:index.php"); exit; }
	
	if (!$tdata['thread_locked']) { include "postreply.php"; } else { header("Location:index.php"); exit; }
} elseif ($action == "edit") {
	if (!$thread_id || !isNum($thread_id) || !$post_id || !isNum($post_id)) { header("Location:index.php"); exit; }

	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE thread_id='".$thread_id."' AND forum_id='".$fdata['forum_id']."'");
	if (dbrows($result)) { $tdata = dbarray($result); } else { header("Location:index.php"); exit; }
	
	$result = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE post_id='".$post_id."' AND thread_id='".$tdata['thread_id']."' AND forum_id='".$fdata['forum_id']."'");
	if (dbrows($result)) { $pdata = dbarray($result); } else { header("Location:index.php"); exit; }
	
	if (!$tdata['thread_locked']) { include "postedit.php"; } else { header("Location:index.php"); exit; }
} else {
	header("Location:index.php");
}

include FUSION_BASE."side_right.php";
include FUSION_BASE."footer.php";
?>