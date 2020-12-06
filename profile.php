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
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
include LOCALE.LOCALESET."members-profile.php";
include LOCALE.LOCALESET."user_fields.php";

if (!isset($lookup) || !isNum($lookup)) fallback("index.php");

$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$lookup'");
if (dbrows($result)) { $data = dbarray($result); } else { redirect("index.php"); }

opentable($locale['420'].$data['user_name']);
echo "<table align='center' cellpadding='0' cellspacing='0'>
<tr>\n<td class='tbl2' colspan='3'><b>".$locale['421']."</b></td>\n\n</tr>
<tr>\n<td align='center' width='150' rowspan='8' class='tbl'>\n";

echo ($data['user_avatar'] ? "<img src='".IMAGES."avatars/".$data['user_avatar']."'>" : $locale['u046'])."\n</td>\n";

echo "<td width='125' class='tbl'>".$locale['u005']."</td>\n<td class='tbl'>\n";
echo ($data['user_hide_email'] != "1" || iADMIN ? "<a href='mailto:".str_replace("@","&#64;",$data['user_email'])."'>".str_replace("@","&#64;",$data['user_email'])."</a>" : $locale['u047'])."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".$locale['u009']."</td>\n<td class='tbl'>\n";
echo ($data['user_location'] ? $data['user_location'] : $locale['u048'])."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".$locale['u010']."</td>\n<td class='tbl'>";
if ($data['user_birthdate'] != "0000-00-00") {
	$months = explode("|", $locale['months']);
	$user_birthdate = explode("-", $data['user_birthdate']);
	echo $months[number_format($user_birthdate['1'])]." ".number_format($user_birthdate['2'])." ".$user_birthdate['0']."</td>\n</tr>\n";
} else {
	echo $locale['u048']."</td>\n</tr>\n";
}

echo "<tr>\n<td class='tbl'>".$locale['u021']."</td>\n<td class='tbl'>\n";
echo ($data['user_aim'] ? $data['user_aim'] : $locale['u048'])."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".$locale['u011']."</td>\n<td class='tbl'>\n";
echo ($data['user_icq'] ? $data['user_icq'] : $locale['u048'])."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".$locale['u012']."</td>\n<td class='tbl'>\n";
echo ($data['user_msn'] ? $data['user_msn'] : $locale['u048'])."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".$locale['u013']."</td>\n<td class='tbl'>\n";
echo ($data['user_yahoo'] ? $data['user_yahoo'] : $locale['u048'])."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".$locale['u014']."</td>\n<td class='tbl'>";
if ($data['user_web']) {
	$urlprefix = !strstr($data['user_web'], "http://") ? "http://" : "";
	echo "<a href='".$urlprefix.$data['user_web']."' target='_blank'>".$data['user_web']."</a></td>\n</tr>\n";
} else {
	echo $locale['u048']."</td>\n</tr>\n";
}

echo "<tr>\n<td class='tbl2' colspan='3'><b>".$locale['422']."</b></td>\n\n</tr>\n";

echo "<tr>\n<td class='tbl'>".$locale['u040']."</td>\n<td class='tbl' colspan='2'>\n";
echo showdate("longdate", $data['user_joined'])."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".$locale['u041']."</td>\n<td class='tbl' colspan='2'>\n";
echo dbcount("(shout_id)", "shoutbox", "shout_name='".$data['user_id']."'")."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".$locale['u042']."</td>\n<td class='tbl' colspan='2'>\n";
echo dbcount("(comment_id)", "comments", "comment_name='".$data['user_id']."'")."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".$locale['u043']."</td>\n<td class='tbl' colspan='2'>\n";
//echo dbcount("(post_id)", "posts", "post_author='".$data['user_id']."'")."</td>\n</tr>\n";
echo $data['user_posts']."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".$locale['u044']."</td>\n<td class='tbl' colspan='2'>\n";
echo ($data['user_lastvisit'] != 0 ? showdate("longdate", $data['user_lastvisit']) : $locale['u049'])."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".$locale['u045']."</td>\n<td class='tbl' colspan='2'>\n";
echo getuserlevel($data['user_level'])."</td>\n</tr>\n";

if ($data['user_groups']) {
	echo "<tr>\n<td class='tbl2' colspan='3'><b>".$locale['423']."</b></td>\n\n</tr>\n<tr>\n<td class='tbl' colspan='3'>\n";
	$user_groups = (strpos($data['user_groups'], ".") == 0 ? explode(".", substr($data['user_groups'], 1)) : explode(".", $data['user_groups']));
	for ($i = 0;$i < count($user_groups);$i++) {
		echo getgroupname($user_groups[$i]);
		if ($i != (count($user_groups)-1)) echo ", ";
	}
	echo "</td>\n</tr>\n";
}

if ($data['user_id'] != $userdata['user_id']) {
	echo "<tr><td align='center' colspan='3' class='tbl'><br>\n<a href='messages.php?msg_send=".$data['user_id']."'>".$locale['u060']."</a>\n";
	echo "</td>\n</tr>\n";
}

echo "</table>\n";
closetable();

require_once "side_right.php";
require_once "footer.php";
?>