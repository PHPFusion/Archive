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
if (eregi("fusion_core.php", $_SERVER['PHP_SELF'])) die();

// PHP-Fusion folder locations
$admin_folder = "fusion_admin/";
$images_folder = "fusion_images/";
$forum_folder = "fusion_forum/";
$includes_folder = "fusion_includes/";
$languages_folder = "fusion_languages/";
$infusions_folder = "fusion_infusions/";
$pages_folder = "fusion_pages/";
$photos_folder = "photoalbum/";
$public_folder = "fusion_public/";
$themes_folder = "fusion_themes/";
//$downloads_folder = "beta/";

// Standard Output Buffering
ob_start();
// GZ Output Buffering
//ob_start("ob_gzhandler");

// If register_globals is turned off, extract super globals (php 4.2.0+)
if (ini_get('register_globals') != 1) {
	$supers = array(
		"_REQUEST",
		"_ENV",
		"_SERVER",
		"_POST",
		"_GET",
		"_COOKIE",
		"_SESSION",
		"_FILES",
		"_GLOBALS"
	);
	foreach ($supers as $__s) {
		if ((isset($$__s) == true) && (is_array($$__s) == true)) extract($$__s, EXTR_OVERWRITE);
	}
	unset($supers);
}

// Prevent any possible XSS attacks via url string
define("FUSION_QUERY", $_SERVER['QUERY_STRING']);
if ((eregi("<[^>]*script*\"?[^>]*>", FUSION_QUERY)) || (eregi("<[^>]*object*\"?[^>]*>", FUSION_QUERY)) ||
	(eregi("<[^>]*iframe*\"?[^>]*>", FUSION_QUERY)) || (eregi("<[^>]*applet*\"?[^>]*>", FUSION_QUERY)) ||
	(eregi("<[^>]*meta*\"?[^>]*>", FUSION_QUERY)) || (eregi("<[^>]*style*\"?[^>]*>", FUSION_QUERY)) ||
	(eregi("<[^>]*form*\"?[^>]*>", FUSION_QUERY)) || (eregi("\([^>]*\"?[^)]*\)", FUSION_QUERY))) {
	die ("Access Violation");
}

// Ensure user definitions have not been declared outside fusion_core.php
if (defined("iGUEST") || defined("iMEMBER") || defined("iMOD") ||
	defined("iADMIN") || defined("iSUPERADMIN") || defined("iUSER")) {
	die ("Access Violation");
}

// mySQL database functions
function dbquery($query) {
	if (!$query = mysql_query($query)) echo mysql_error();
	return $query;
}

function dbcount($field,$table,$vars="",$prefix=FUSION_PREFIX) {
	if ($vars) $cond = " WHERE ".$vars;
	if (!$query = mysql_query("SELECT Count".$field." FROM ".$prefix.$table.$cond)) {
		echo mysql_error();
	} else {
		$rows = mysql_result($query, 0); return $rows;
	}
}

function dbresult($query, $row) {
	if (!$query = mysql_result($query, $row)) echo mysql_error();
	return $query;
}

function dbrows($query) {
	if (!$query = mysql_num_rows($query)) echo mysql_error();
	return $query;
}

function dbarray($query) {
	if (!$query = mysql_fetch_assoc($query)) echo mysql_error();
	return $query;
}

function dbarraynum($query) {
	if (!$query = mysql_fetch_row($query)) echo mysql_error();
	return $query;
}

function dbconnect($dbhost, $dbusername, $dbpassword, $dbname) {
	mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect to SQL Server");
	mysql_select_db($dbname) or die("Unable to select database");
}

// Ensure rowstart pagination is numeric if isset.
if (isset($rowstart) && !isNum($rowstart)) die();

// If fusion_root is not defined activate install.php script
if (!defined("FUSION_ROOT")) { header("Location:install.php"); exit; }

// Calculate the folder level and define FUSION_BASE
$i = 0; $prefix = "";
$numlevels = (substr_count(substr($_SERVER['PHP_SELF'], strlen(FUSION_ROOT)),"/"));
while ($i < $numlevels) { $prefix .= "../"; $i++; }
define("FUSION_BASE", $prefix);

// Establish mySQL database connection
dbconnect($dbhost, $dbusername, $dbpassword, $dbname);

// Fetch the Site Settings from the database and store them in $settings
$settings = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."settings"));

// Various core definitions
define("IN_FUSION", TRUE);
define("FUSION_IP", getenv('REMOTE_ADDR'));
define("FUSION_ADMIN", FUSION_BASE.$admin_folder);
define("FUSION_IMAGES", FUSION_BASE.$images_folder);
define("FUSION_INCLUDES", FUSION_BASE.$includes_folder);
define("FUSION_LANGUAGES", FUSION_BASE.$languages_folder);
define("FUSION_LAN", $settings['language']."/");
define("FUSION_FORUM", FUSION_BASE.$forum_folder);
define("FUSION_INFUSIONS", FUSION_BASE.$infusions_folder);
define("FUSION_PHOTOS", FUSION_IMAGES.$photos_folder);
define("FUSION_PUBLIC", FUSION_BASE.$public_folder);
define("FUSION_THEMES", FUSION_BASE.$themes_folder);

// Load the Global language file
include FUSION_LANGUAGES.FUSION_LAN."global.php";

// PHP-Fusion user cookie functions
if (!isset($_COOKIE['fusion_visited'])) {
	$result=dbquery("UPDATE ".$fusion_prefix."settings SET counter=counter+1");
	setcookie("fusion_visited", "yes", time() + 31536000, "/", "", "0");
}

if (isset($_POST['login'])) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_name='".$_POST['user_name']."' and user_password=md5('".$_POST['user_pass']."')");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		$cookie_value = $data['user_id'].".".md5($user_pass);
		if ($data['user_ban'] != 1) {			
			if (isset($_POST['remember_me'])) { $cookie_exp = time() + 3600*24*30; } else { $cookie_exp = time() + 3600*3; }
			setcookie("fusion_user", $cookie_value, $cookie_exp, "/", "", "0");
			header("Location:".FUSION_BASE."index.php");
		} else {
			$loginerror = "This account is suspended<br><br>\n";
		}
	}
}

if (isset($_REQUEST['logout']) && $_REQUEST['logout'] == "yes") {
	setcookie("fusion_user", "Null", time() - 7200, "/", "", "0");
	setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
	header("Location:".FUSION_BASE."index.php");
}

if (isset($_COOKIE['fusion_user'])) {
	$logincheck = explode(".", $_COOKIE['fusion_user']);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='".$logincheck['0']."' AND user_password='".$logincheck['1']."'");
	if (dbrows($result) != 0) {
		$userdata = dbarray($result);
		if ($userdata['user_ban'] != 1) {
			if ($userdata['user_theme'] != "Default" && file_exists(FUSION_THEMES.$userdata['user_theme']."/theme.php")) {
				define("FUSION_THEME", FUSION_THEMES.$userdata['user_theme']."/");
			} else {
				define("FUSION_THEME", FUSION_THEMES.$settings['theme']."/");
			}
			if ($userdata['user_offset'] <> 0) {
				$settings['timeoffset'] = $settings['timeoffset'] + $userdata['user_offset'];
			}
			if (empty($_COOKIE['fusion_lastvisit'])) {
				setcookie("fusion_lastvisit", $userdata['user_lastvisit'], time() + 3600, "/", "", "0");
				$lastvisited = $userdata['user_lastvisit'];
			} else {
				$lastvisited = $_COOKIE['fusion_lastvisit'];
			}
		} else {
			setcookie("fusion_user", "Null", time() - 7200, "/", "", "0");
			setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
			header("Location:".FUSION_BASE."index.php");
		}
	} else {
		setcookie("fusion_user", "Null", time() - 7200, "/", "", "0");
		setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
		header("Location:".FUSION_BASE."index.php");
	}
} else {
	define("FUSION_THEME", FUSION_THEMES.$settings['theme']."/");
	$userdata = "";	$userdata['user_mod'] = 0;
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (ini_get('magic_quotes_gpc')) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

function phpentities($text) {
	$search = array("&", "\"", "'", "\\", "<", ">");
	$replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&lt;", "&gt;");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// Trim a line of text to a preferred length
function trimlink($text, $length) {
	$dec = array("\"", "'", "\\", '\"', "\'", "<", ">");
	$enc = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;");
	$text = str_replace($enc, $dec, $text);
	if (strlen($text) > $length) $text = substr($text, 0, ($length-3))."...";
	$text = str_replace($dec, $enc, $text);
	return $text;
}

// Check numeric input
function isNum($value) {
	return (preg_match('/^[0-9]+$/',$value));
}

// Parse smiley bbcode into HTML images
function parsesmileys($message) {
	$smiley = array(
		"/\:\)/si" => "<img src='".FUSION_IMAGES."smiley/smile.gif'>",
		"/\;\)/si" => "<img src='".FUSION_IMAGES."smiley/wink.gif'>",
		"/\:\(/si" => "<img src='".FUSION_IMAGES."smiley/sad.gif'>",
		"/\:\|/si" => "<img src='".FUSION_IMAGES."smiley/frown.gif'>",
		"/\:o/si" => "<img src='".FUSION_IMAGES."smiley/shock.gif'>",
		"/\:p/si" => "<img src='".FUSION_IMAGES."smiley/pfft.gif'>",
		"/b\)/si" => "<img src='".FUSION_IMAGES."smiley/cool.gif'>",
		"/\:d/si" => "<img src='".FUSION_IMAGES."smiley/grin.gif'>",
		"/\:@/si" => "<img src='".FUSION_IMAGES."smiley/angry.gif'>"
	);
	foreach($smiley as $key=>$smiley_img) $message = preg_replace($key, $smiley_img, $message);
	return $message;
}

// Show smiley icons in comments, forum and other post pages
function displaysmileys($textarea) {
	$smileys = array (
		":)" => "smile.gif",
		";)" => "wink.gif",
		":|" => "frown.gif",
		":(" => "sad.gif",
		":o" => "shock.gif",
		":p" => "pfft.gif",
		"B)" => "cool.gif",
		":D" => "grin.gif",
		":@" => "angry.gif"
	);
	foreach($smileys as $key=>$smiley) $smiles .= "<img src='".FUSION_IMAGES."smiley/$smiley' onClick=\"insertText('$textarea', '$key');\">\n";
	return $smiles;
}

// Parse bbcode into HTML code
function parseubb($message) {
	$ubbs1[0] = '#\[b\](.*?)\[/b\]#si';
	$ubbs2[0] = '<b>\1</b>';
	$ubbs1[1] = '#\[i\](.*?)\[/i\]#si';
	$ubbs2[1] = '<i>\1</i>';
	$ubbs1[2] = '#\[u\](.*?)\[/u\]#si';
	$ubbs2[2] = '<u>\1</u>';
	$ubbs1[3] = '#\[center\](.*?)\[/center\]#si';
	$ubbs2[3] = '<center>\1</center>';
	$ubbs1[4] = '#\[url\]http://(.*?)\[/url\]#si';
	$ubbs2[4] = '<a href=\'http://\1\' target=\'_blank\'>http://\1</a>';
	$ubbs1[5] = '#\[url\](.*?)\[/url\]#si';
	$ubbs2[5] = '<a href=\'http://\1\' target=\'_blank\'>\1</a>';
	$ubbs1[6] = '#\[url=http://(.*?)\](.*?)\[/url\]#si';
	$ubbs2[6] = '<a href=\'http://\1\' target=\'_blank\'>\2</a>';
	$ubbs1[7] = '#\[url=(.*?)\](.*?)\[/url\]#si';
	$ubbs2[7] = '<a href=\'http://\1\' target=\'_blank\'>\2</a>';
	$ubbs1[8] = '#\[mail\](.*?)\[/mail\]#si';
	$ubbs2[8] = '<a href=\'mailto:\1\'>\1</a>';
	$ubbs1[9] = '#\[mail=(.*?)\](.*?)\[/mail\]#si';
	$ubbs2[9] = '<a href=\'mailto:\1\'>\2</a>';
	$ubbs1[10] = '#\[img\](.*?)\[/img\]#si';
	$ubbs2[10] = '<img src=\'\1\'>';
	$ubbs1[11] = '#\[small\](.*?)\[/small\]#si';
	$ubbs2[11] = '<span class=\'small\'>\1</span>';
	$ubbs1[12] = '#\[color=(.*?)\](.*?)\[/color\]#si';
	$ubbs2[12] = '<span style=\'color:\1\'>\2</span>';
	$ubbs1[13] = '#\[quote\](.*?)\[/quote\]#si';
	$ubbs2[13] = '<div class=\'quote\'>\1</div>';
	$ubbs1[14] = '#\[code\](.*?)\[/code\]#si';
	$ubbs2[14] = '<div class=\'quote\' style=\'width:400px;white-space:nowrap;overflow:auto\'><code style=\'white-space:nowrap\'>\1<br><br><br></code></div>';
	for ($i=0;$ubbs1[$i]!="";$i++) $message = preg_replace($ubbs1, $ubbs2, $message);
	
	// Prevent use of mallicious javascript
	$text1[0] = "#document#si"; $text2[0] = 'docu<i></i>ment';
	$text1[1] = "#expression#si"; $text2[1] = 'expres<i></i>sion';
	$text1[2] = "#onmouseover#si"; $text2[2] = 'onmouse<i></i>over';
	$text1[3] = "#onclick#si"; $text2[3] = 'on<i></i>click';
	$text1[4] = "#onmousedown#si"; $text2[4] = 'onmouse<i></i>down';
	$text1[5] = "#onmouseup#si"; $text2[5] = 'onmouse<i></i>up';
	$text1[6] = "#ondblclick#si"; $text2[6] = 'on<i></i>dblclick';
	$text1[7] = "#onmouseout#si"; $text2[7] = 'onmouse<i></i>out';
	$text1[8] = "#onmousemove#si"; $text2[8] = 'onmouse<i></i>move';
	$text1[9] = "#onload#si"; $text2[9] = 'on<i></i>load';
	$text1[10] = "#background:url#si"; $text2[10] = 'background<i></i>:url';
	for ($i=0;$text1[$i]!="";$i++) $message = preg_replace($text1, $text2, $message);
	
	return $message;
}

function descript($text) {
	// Prevent use of mallicious javascript in any non-stripped document
	$text1[0] = "#document#si"; $text2[0] = 'docu<i></i>ment';
	$text1[1] = "#expression#si"; $text2[1] = 'expres<i></i>sion';
	$text1[2] = "#onmouseover#si"; $text2[2] = 'onmouse<i></i>over';
	$text1[3] = "#onclick#si"; $text2[3] = 'on<i></i>click';
	$text1[4] = "#onmousedown#si"; $text2[4] = 'onmouse<i></i>down';
	$text1[5] = "#onmouseup#si"; $text2[5] = 'onmouse<i></i>up';
	$text1[6] = "#ondblclick#si"; $text2[6] = 'on<i></i>dblclick';
	$text1[7] = "#onmouseout#si"; $text2[7] = 'onmouse<i></i>out';
	$text1[8] = "#onmousemove#si"; $text2[8] = 'onmouse<i></i>move';
	$text1[9] = "#onload#si"; $text2[9] = 'on<i></i>load';
	$text1[10] = "#background:url#si"; $text2[10] = 'background<i></i>:url';
	for ($i=0;$text1[$i]!="";$i++) $text = preg_replace($text1, $text2, $text);
	return $text;
}

// Replace offensive words with the defined replacement word
function censorwords($text) {
	global $settings;
	$word_list = explode("\r\n", $settings['bad_words']);
		for ($i=0;$word_list[$i]!="";$i++) {
		$text = preg_replace("/".$word_list[$i]."/si", $settings['bad_word_replace'], $text);
	}
	return $text;
}

// Display the user's moderator level
function getmodlevel($modlevel) {
	$levels = array(1=> USER1, USER2, USER3, USER4);
	$modlevel = $levels[$modlevel];
	return $modlevel;
}

// Universal page pagination function
function makePageNav($start,$count,$total,$range=0,$link=""){
	if ($link=="") $link=$PHP_SELF."?";
	$res="";
	$pg_cnt=ceil($total / $count);
	if ($pg_cnt > 1) {
		$idx_back = $start - $count;
		$idx_next = $start + $count;
		$cur_page=ceil(($start + 1) / $count);
		$res.="<table cellspacing='1' cellpadding='1' border='0' class='tbl-border'>\n<tr>\n";
		$res.="<td class='tbl2'><span class='small'>".LAN_52."$cur_page".LAN_53."$pg_cnt</span></td>\n";
		if ($idx_back >= 0) {
			if ($cur_page > ($range + 1)) $res.="<td class='tbl2'><a class='small' href='$link"."rowstart=0'>&lt;&lt;</a></td>\n";
			$res.="<td class='tbl2'><a class='small' href='$link"."rowstart=$idx_back'>&lt;</a></td>\n";
		}
		$idx_fst=max($cur_page - $range, 1);
		$idx_lst=min($cur_page + $range, $pg_cnt);
		if ($range==0) {
			$idx_fst = 1;
			$idx_lst=$pg_cnt;
		}
		for($i=$idx_fst;$i<=$idx_lst;$i++) {
			$offset_page=($i - 1) * $count;
			if ($i==$cur_page) {
				$res.="<td class='tbl1'><span class='small'><b>$i</b></span></td>\n";
			} else {
				$res.="<td class='tbl1'><a class='small' href='$link"."rowstart=$offset_page'>$i</a></td>\n";
			}
		}
		if ($idx_next < $total) {
			$res.="<td class='tbl2'><a class='small' href='$link"."rowstart=$idx_next'>&gt;</a></td>\n";
			if ($cur_page < ($pg_cnt - $range)) $res.="<td class='tbl2'><a class='small' href='$link"."rowstart=".($pg_cnt-1)*$count."'>&gt;&gt;</a></td>\n";
		}
		$res.="</tr>\n</table>\n";

	}
	return $res;
}

// Format the date & time accordingly
function showdate($format, $val) {
	global $settings;
	if ($format == "shortdate" || $format == "longdate" || $format == "forumdate") {
		return strftime($settings[$format], $val+($settings['timeoffset']*3600));
	} else {
		return strftime($format, $val+($settings['timeoffset']*3600));
	}
}

// Photo Gallery functions
function constrainImage($width,$height,$max_width,$max_height){
	if (!$height||!$width||!$max_height||!$max_width) {
		return false;
	} elseif ($height>$max_height||$width>$max_width) {
		if ($height>$width) {
			if ($img_width>$max_width) {
				$img_width=$max_width;
				$img_height=round(($max_width*$max_height)/$img_width);
			} else {
				$img_width=round(($width*$max_height)/$height);
				$img_height=$max_height;
			}
		} else {
			if ($img_height>$max_height) {
				$img_width=round(($max_width*$max_height)/$img_height);
				$img_height=$max_height;
			} else {
				$img_width=$max_width;
				$img_height=round(($height*$max_width)/$width);
			}
		}
		return array('width'=>$img_width,'height'=>$img_height);
	} else {
		return array('width'=>$width,'height'=>$height);
	}
}

function createThumbnail($origfile,$thumbfile,$new_w,$new_h) {
	
	global $settings;
	
	$origimage=imagecreatefromjpeg($origfile);
	$origwidth=imagesx($origimage);
	$origheight=imagesy($origimage);
	$size=constrainImage($origwidth,$origheight,$new_w,$new_h);
	if ($settings['thumb_compression']=="gd1") {
		$thumbimage=imagecreate($new_w,$new_h);
		$background=imagecolorallocate($thumbimage,255,255,255);
		imagefill($thumbimage,0,0,$background);
		$result=imagecopyresized( $thumbimage, $origimage,round(($new_w-$size['width'])/2),round(($new_h-$size['height'])/2),0,0,$size['width'],$size['height'],$origwidth,$origheight);
	} else {
		$thumbimage=imagecreatetruecolor($new_w,$new_h);
		$background=imagecolorallocate($thumbimage,255,255,255);
		imagefill($thumbimage,0,0,$background);
		$result=imagecopyresampled( $thumbimage, $origimage,round(($new_w-$size['width'])/2),round(($new_h-$size['height'])/2),0,0,$size['width'],$size['height'],$origwidth,$origheight);
	}
	imageJPEG($thumbimage,$thumbfile);
}

function parseByteSize($size,$digits=2,$dir=false) {
	$kb=1024; $mb=1024*$kb; $gb=1024*$mb; $tb=1024*$gb;
	if (($size==0)&&($dir)) { return "Empty"; }
	elseif ($size<$kb) { return $size." Bytes"; }
	elseif ($size<$mb) { return round($size/$kb,$digits)." Kb"; }
	elseif ($size<$gb) { return round($size/$mb,$digits)." Mb"; }
	elseif ($size<$tb) { return round($size/$gb,$digits)." Gb"; }
	else { return round($size/$tb,$digits)." Tb"; }
}

// Calculate page load time
function getloadtime($time,$p) {
	global $start;
	$time = explode(" ", $time);
	$time = $time[1] + $time[0];
	if ($p == 'f') { $time = round(($time - $start),5); }
	return $time;
}

// User level reference definitions
define("iGUEST",$userdata['user_mod'] == 0 ? 1 : 0);
define("iMEMBER", $userdata['user_mod'] >= 1 ? 1 : 0);
define("iMOD", $userdata['user_mod'] >= 2 ? 1 : 0);
define("iADMIN", $userdata['user_mod'] >= 3 ? 1 : 0);
define("iSUPERADMIN", $userdata['user_mod'] >= 4 ? 1 : 0);
define("iUSER", $userdata['user_mod']);
?>