<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
// Users Code Online
if ($userdata[user_name] != "") { $name = $userdata[user_id].".".$userdata[user_name]; } else { $name = "0.Guest"; }
$result = dbquery("SELECT * FROM ".$fusion_prefix."online WHERE online_user='$name' AND online_ip='$user_ip'");
if (dbrows($result) != 0) {
	$result = dbquery("UPDATE ".$fusion_prefix."online SET online_lastactive='".time()."' WHERE online_user='$name' AND online_ip='$user_ip'");
} else {
	if ($name != "0.Guest") {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."online WHERE online_user='$name'");
		if (dbrows($result) != 0) {
			$result = dbquery("UPDATE ".$fusion_prefix."online SET online_ip='$user_ip', online_lastactive='".time()."' WHERE online_user='$name'");
		} else {
			$result = dbquery("INSERT INTO ".$fusion_prefix."online VALUES('', '$name', '$user_ip', '".time()."')");
		}
	} else {
		$result = dbquery("INSERT INTO ".$fusion_prefix."online VALUES('', '$name', '$user_ip', '".time()."')");
	}
}
if (isset($_POST['login'])) {
	$result = dbquery("DELETE FROM ".$fusion_prefix."online WHERE online_user='0.Guest' AND online_ip='$user_ip'");
} else if (isset($logout)) {
	$result = dbquery("DELETE FROM ".$fusion_prefix."online WHERE online_ip='$user_ip'");
}
$deletetime = time() - 300;
$result = dbquery("DELETE FROM ".$fusion_prefix."online WHERE online_lastactive < $deletetime");

// General Core Functions
function getmodlevel($modlevel) {
	$levels = array(MOD0, MOD1, MOD2, MOD3);
	$modlevel = $levels[$modlevel];
	return $modlevel;
}

function parsesmileys($message) {
	$smiley = array("/\:\)/si", "/\;\)/si", "/\:\(/si", "/\:\|/si", "/\:o/si", "/\:p/si", "/b\)/si", "/\:d/si", "/\:@/si");
	$smiley2 = array("<img src=\"".fusion_basedir."fusion_images/smiley/smile.gif\">", "<img src=\"".fusion_basedir."fusion_images/smiley/wink.gif\">", "<img src=\"".fusion_basedir."fusion_images/smiley/sad.gif\">",
	"<img src=\"".fusion_basedir."fusion_images/smiley/frown.gif\">", "<img src=\"".fusion_basedir."fusion_images/smiley/shock.gif\">", "<img src=\"".fusion_basedir."fusion_images/smiley/pfft.gif\">",
	"<img src=\"".fusion_basedir."fusion_images/smiley/cool.gif\">", "<img src=\"".fusion_basedir."fusion_images/smiley/grin.gif\">", "<img src=\"".fusion_basedir."fusion_images/smiley/angry.gif\">");
	for ($i=0;$smiley[$i]!="";$i++) {
		$message = preg_replace($smiley[$i], $smiley2[$i], $message);
	}
	return $message;
}

function parseubb($message) {
	$ubbs = array();
	$ubbs2 = array();
	$ubbs[0] = '#\[b\](.*?)\[/b\]#si';
	$ubbs2[0] = '<b>\1</b>';
	$ubbs[1] = '#\[i\](.*?)\[/i\]#si';
	$ubbs2[1] = '<i>\1</i>';
	$ubbs[2] = '#\[u\](.*?)\[/u\]#si';
	$ubbs2[2] = '<u>\1</u>';
	$ubbs[3] = '#\[center\](.*?)\[/center\]#si';
	$ubbs2[3] = '<center>\1</center>';
	$ubbs[4] = '#\[url\](.*?)\[/url\]#si';
	$ubbs2[4] = '<a href="http://\1" target="_blank">\1</a>';
	$ubbs[5] = '#\[mail\](.*?)\[/mail\]#si';
	$ubbs2[5] = '<a href="mailto:\1">\1</a>';
	$ubbs[6] = '#\[img\](.*?)\[/img\]#si';
	$ubbs2[6] = '<img src="\1">';
	$ubbs[7] = '#\[small\](.*?)\[/small\]#si';
	$ubbs2[7] = '<font class="small">\1</font>';
	$ubbs[8] = '#\[color=(.*?)\](.*?)\[/color\]#si';
	$ubbs2[8] = '<font color="\1">\2</font>';
	$ubbs[9] = '#\[quote\](.*?)\[/quote\]#si';
	$ubbs2[9] = '<div class="quote">\1</div>';
	$ubbs[10] = '#\[code\](.*?)\[/code\]#si';
	$ubbs2[10] = '<div class="quote"><pre>\1</pre></div>';
	for ($i=0;$ubbs[$i]!="";$i++) {
		$message = preg_replace($ubbs, $ubbs2, $message);
	}
	return $message;
}

function trimlink($text, $length) {
	$dec = array("\"", "'", "\\", '\"', "\'", "<", ">");
	$enc = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;");
	$text = str_replace($enc, $dec, $text);
	if (strlen($text) > $length) {
		$text = substr($text, 0, ($length-3))."...";
	}
	$text = str_replace($dec, $enc, $text);
	return $text;
}
?>