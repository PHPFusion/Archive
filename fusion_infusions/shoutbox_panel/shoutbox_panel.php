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
if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }

openside(LAN_120);
if (isset($_POST['post_shout'])) {
	if (iMEMBER) {
		$shout_name = $userdata['user_id'];
	} elseif ($settings['guestposts'] == "1") {
		$shout_name = stripinput($_POST['shout_name']);
		if (is_numeric($shout_name)) $shout_name="";
	}
	$shout_message = str_replace("\n", " ", $_POST['shout_message']);
	$shout_message = preg_replace("/^(.{255}).*$/", "$1", $shout_message);
	$shout_message = preg_replace("/([^\s]{25})/", "$1\n", $shout_message);
	$shout_message = stripinput(censorwords($shout_message));
	$shout_message = str_replace("\n", "<br>", $shout_message);
	if ($shout_name != "" && $shout_message != "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."shoutbox VALUES('', '$shout_name', '$shout_message', '".time()."', '".FUSION_IP."')");
	}
	header("Location:".$PHP_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : ""));
}
if (iMEMBER || $settings['guestposts'] == "1") {
	echo "<form name='chatform' method='post' action='".$PHP_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "")."' OnSubmit=\"document.forms['chatform'].post_shout.enabled = false;\">\n";
	if (iGUEST) {
		echo LAN_121."<br>
<input type='text' name='shout_name' value='".$userdata['user_name']."' class='textbox' maxlength='32' style='width:160;'><br>
".LAN_122."<br>\n";
	}
	echo "<textarea name='shout_message' rows='3' class='textbox' cols='29'></textarea><br>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr><td>
<input type='submit' name='post_shout' value='".LAN_123."' class='button'></td>
<td align='right' class='side-small'><a href='".FUSION_BASE."shoutboxhelp.php' class='side'>".LAN_124."</a></td></tr>
</table>
</form><br>\n";
} else {
	echo "<center>".LAN_125."</center><br>\n";
}
$result = dbquery("SELECT count(shout_id) FROM ".$fusion_prefix."shoutbox");
$numrows = dbresult($result, 0);
$result = dbquery(
	"SELECT * FROM ".$fusion_prefix."shoutbox LEFT JOIN ".$fusion_prefix."users
	ON ".$fusion_prefix."shoutbox.shout_name=".$fusion_prefix."users.user_id
	ORDER BY shout_datestamp DESC LIMIT 0,".$settings['numofshouts']
);
if (dbrows($result) != 0) {
	$i = 0;
	echo "<div style='border:0; padding:0px; width:168; height:350px; overflow:auto; '>";
	while ($data = dbarray($result)) {
		echo "<span class='shoutboxname'>";
		if ($data['user_name']) {
			echo "<a href='".FUSION_BASE."profile.php?lookup=".$data['shout_name']."' class='side'>".$data['user_name']."</a>\n";
		} else {
			echo "".$data['shout_name']."\n";
		}
		echo "</span><br>
<span class='shoutboxdate'>".showdate("shortdate", $data['shout_datestamp'])."</span><br>
<span class='shoutbox'>".parsesmileys($data['shout_message'])."</span><br>\n";


		if ($i != $numrows) echo "<br>\n";
		
	}echo "</div>";
	if ($numrows > $settings['numofshouts']) {
		echo "<center>\n<img src='".FUSION_THEME."images/bullet.gif'>
<a href='".FUSION_BASE."shoutbox_archive.php' class='side'>".LAN_126."</a> <img src='".FUSION_THEME."images/bulletb.gif'></center>\n";
	}
} else {
	echo "<div align='left'>".LAN_127."</div>\n";
}
closeside();
?>
