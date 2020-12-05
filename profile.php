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
@include "fusion_config.php";
include "fusion_core.php";
include "subheader.php";
include "side_left.php";
include FUSION_LANGUAGES.FUSION_LAN."members-profile.php";
include FUSION_LANGUAGES.FUSION_LAN."user_fields.php";

$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$lookup'");
$data = dbarray($result);
opentable(LAN_420.$data['user_name']);
echo "<table align='center' cellpadding='0' cellspacing='0'>
<tr>\n<td class='tbl2' colspan='3'><b>".LAN_421."</b></td>\n\n</tr>
<tr>\n<td align='center' width='150' rowspan='7' class='tbl'>\n";

echo ($data['user_avatar'] ? "<img src='".FUSION_PUBLIC."avatars/".$data['user_avatar']."'>" : LNU_046)."\n</td>\n";

echo "<td width='125' class='tbl'>".LNU_005."</td>\n<td class='tbl'>\n";
echo ($data['user_hide_email'] != "1" || iADMIN ? "<a href='mailto:".$data['user_email']."'>".$data['user_email']."</a>" : LNU_047)."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".LNU_009."</td>\n<td class='tbl'>\n";
echo ($data['user_location'] ? $data['user_location'] : LNU_048)."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".LNU_010."</td>\n<td class='tbl'>";
if ($data['user_birthdate'] != "0000-00-00") {
	$months = explode("|", LAN_MONTHS);
	$user_birthdate = explode("-", $data['user_birthdate']);
	echo $months[number_format($user_birthdate['1'])]." ".number_format($user_birthdate['2'])." ".$user_birthdate['0']."</td>\n</tr>\n";
} else {
	echo LNU_048."</td>\n</tr>\n";
}

echo "<tr>\n<td class='tbl'>".LNU_011."</td>\n<td class='tbl'>\n";
echo ($data['user_icq'] ? $data['user_icq'] : LNU_048)."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".LNU_012."</td>\n<td class='tbl'>\n";
echo ($data['user_msn'] ? $data['user_msn'] : LNU_048)."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".LNU_013."</td>\n<td class='tbl'>\n";
echo ($data['user_yahoo'] ? $data['user_yahoo'] : LNU_048)."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".LNU_014."</td>\n<td class='tbl'>";
if ($data['user_web']) {
	if (!strstr($data['user_web'], "http://")) { $urlprefix = "http://"; }
	echo "<a href='".$urlprefix.$data['user_web']."' target='_blank'>".$data['user_web']."</a></td>\n</tr>\n";
} else {
	echo LNU_048."</td>\n</tr>\n";
}

echo "<tr>\n<td class='tbl2' colspan='3'><b>".LAN_422."</b></td>\n\n</tr>\n";

echo "<tr>\n<td class='tbl'>".LNU_040."</td>\n<td class='tbl' colspan='2'>\n";
echo showdate("longdate", $data['user_joined'])."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".LNU_041."</td>\n<td class='tbl' colspan='2'>\n";
echo dbcount("(shout_id)", "shoutbox", "shout_name='".$data['user_id']."'")."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".LNU_042."</td>\n<td class='tbl' colspan='2'>\n";
echo dbcount("(comment_id)", "comments", "comment_name='".$data['user_id']."'")."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".LNU_043."</td>\n<td class='tbl' colspan='2'>\n";
//echo dbcount("(post_id)", "posts", "post_author='".$data['user_id']."'")."</td>\n</tr>\n";
echo $data['user_posts']."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".LNU_044."</td>\n<td class='tbl' colspan='2'>\n";
echo ($data['user_lastvisit'] != 0 ? showdate("longdate", $data['user_lastvisit']) : LNU_049)."</td>\n</tr>\n";

echo "<tr>\n<td class='tbl'>".LNU_045."</td>\n<td class='tbl' colspan='2'>\n";
echo getmodlevel($data['user_mod'])."</td>\n</tr>\n";

if ($data['user_id'] != $userdata['user_id']) {
	echo "<tr><td align='center' colspan='3' class='tbl'><br>\n<a href='messages.php?step=send&user_id=".$data['user_id']."'>".LNU_060."</a>\n";
	echo "</td>\n</tr>\n";
}

echo "</table>\n";
closetable();
echo "</td>\n";

include "side_right.php";
include "footer.php";
?>