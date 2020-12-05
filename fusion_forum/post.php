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
include FUSION_LANGUAGES.FUSION_LAN."forum/forum_post.php";
require_once FUSION_BASE."side_left.php";

if (!FUSION_QUERY || !$forum_id || !isNum($forum_id)) { header("Location: index.php"); exit; }

$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='".$forum_id."'");
if (dbrows($result)) {
	$fdata = dbarray($result);
	if (!checkgroup($fdata['forum_access']) || !$fdata['forum_cat']) { header("Location: index.php"); exit; }
} else {
	header("Location: index.php"); exit;
}
if (!checkgroup($fdata['forum_posting'])) { header("Location: index.php"); exit; }

$forum_mods = explode(".", $fdata['forum_moderators']);
if (iMEMBER && in_array($userdata['user_id'], $forum_mods)) { define("iMOD", true); } else { define("iMOD", false); }

$fcdata = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='".$fdata['forum_cat']."'"));

$caption = $fcdata['forum_name']." | ".$fdata['forum_name'];

if ($action == "newthread") {
	include "postnewthread.php";
} elseif ($action == "reply") {
	if (!$thread_id || !isNum($thread_id)) { header("Location: index.php"); exit; }

	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE thread_id='".$thread_id."' AND forum_id='".$fdata['forum_id']."'");
	if (dbrows($result)) { $tdata = dbarray($result); } else { header("Location: index.php"); exit; }
	
	if (!$tdata['thread_locked']) { include "postreply.php"; } else { header("Location: index.php"); exit; }
} elseif ($action == "edit") {
	if (!$thread_id || !isNum($thread_id) || !$post_id || !isNum($post_id)) { header("Location: index.php"); exit; }

	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE thread_id='".$thread_id."' AND forum_id='".$fdata['forum_id']."'");
	if (dbrows($result)) { $tdata = dbarray($result); } else { header("Location: index.php"); exit; }

	$result = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE post_id='".$post_id."' AND thread_id='".$tdata['thread_id']."' AND forum_id='".$fdata['forum_id']."'");
	if (dbrows($result)) { $pdata = dbarray($result); } else { header("Location: index.php"); exit; }
	if ($userdata['user_id'] != $pdata['post_author'] && !iMOD) { header("Location: index.php"); exit; }
	
	if (!$tdata['thread_locked']) { include "postedit.php"; } else { header("Location: index.php"); exit; }
} else {
	header("Location: index.php");
}

require_once FUSION_BASE."side_right.php";
require_once FUSION_BASE."footer.php";
?>