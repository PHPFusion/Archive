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
if (eregi("fusion_core.php", $_SERVER['PHP_SELF'])) die();

// If register_globals is turned off, extract super globals (php 4.2.0+)
if (ini_get('register_globals') != 1) {
	$supers = array("_REQUEST","_ENV","_SERVER","_POST","_GET","_COOKIE","_SESSION","_FILES","_GLOBALS");
	foreach ($supers as $__s) {
		if ((isset($$__s) == true) && (is_array($$__s) == true)) extract($$__s, EXTR_OVERWRITE);
	}
	unset($supers);
}

// Prevent any possible XSS attacks via url string
foreach ($_GET as $check_url) {
	if ((eregi("<[^>]*script*\"?[^>]*>", $check_url)) || (eregi("<[^>]*object*\"?[^>]*>", $check_url)) ||
		(eregi("<[^>]*iframe*\"?[^>]*>", $check_url)) || (eregi("<[^>]*applet*\"?[^>]*>", $check_url)) ||
		(eregi("<[^>]*meta*\"?[^>]*>", $check_url)) || (eregi("<[^>]*style*\"?[^>]*>", $check_url)) ||
		(eregi("<[^>]*form*\"?[^>]*>", $check_url)) || (eregi("\([^>]*\"?[^)]*\)", $check_url)) ||
		(eregi("\"", $check_url))) {
	die ();
	}
}
unset($check_url);

// Start Output Buffering
ob_start();

// PHP-Fusion folder locations
$admin_folder = "fusion_admin/";
$images_folder = "fusion_images/";
$a_images_folder = "fusion_images/articles/";
$n_images_folder = "fusion_images/news/";
$forum_folder = "fusion_forum/";
$includes_folder = "fusion_includes/";
$languages_folder = "fusion_languages/";
$infusions_folder = "fusion_infusions/";
$photos_folder = "photoalbum/";
$public_folder = "fusion_public/";
$themes_folder = "fusion_themes/";

// Ensure user definitions have not been declared outside fusion_core.php
if (defined("iGUEST") || defined("iMEMBER") || defined("iMOD") || defined("iADMIN") || 
	defined("iUSER") || defined("iUSER_RIGHTS") || defined("iUSER_GROUPS")) {
	die ("Access Violation");
}

// Ensure rowstart pagination is numeric if isset.
if (isset($rowstart) && !isNum($rowstart)) die("Access Violation");

// If fusion_root is not defined activate install.php script
if (!defined("FUSION_ROOT")) { header("Location: install.php"); exit; }

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
define("FUSION_QUERY", (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : ""));
define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
define("FUSION_IP", getenv('REMOTE_ADDR'));
define("FUSION_ADMIN", FUSION_BASE.$admin_folder);
define("FUSION_IMAGES", FUSION_BASE.$images_folder);
define("FUSION_IMAGES_A", FUSION_BASE.$a_images_folder);
define("FUSION_IMAGES_N", FUSION_BASE.$n_images_folder);
define("FUSION_INCLUDES", FUSION_BASE.$includes_folder);
define("FUSION_LANGUAGES", FUSION_BASE.$languages_folder);
define("FUSION_LAN", $settings['language']."/");
define("FUSION_FORUM", FUSION_BASE.$forum_folder);
define("FUSION_INFUSIONS", FUSION_BASE.$infusions_folder);
define("FUSION_PHOTOS", FUSION_IMAGES.$photos_folder);
define("FUSION_PUBLIC", FUSION_BASE.$public_folder);
define("FUSION_THEMES", FUSION_BASE.$themes_folder);
define("QUOTES_GPC", (ini_get('magic_quotes_gpc') ? TRUE : FALSE));

// mySQL database functions
function dbquery($query) {
	if (!$query = mysql_query($query)) echo mysql_error();
	return $query;
}

function dbcount($field,$table,$vars="",$prefix=FUSION_PREFIX) {
	$cond = ($vars ? " WHERE ".$vars : "");
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

// Load the Global language file
include FUSION_LANGUAGES.FUSION_LAN."global.php";

// Check if users full or partial ip is blacklisted
$sub_ip1 = substr(FUSION_IP,0,strlen(FUSION_IP)-strlen(strrchr(FUSION_IP,".")));
$sub_ip2 = substr($sub_ip1,0,strlen($sub_ip1)-strlen(strrchr($sub_ip1,".")));
if (dbcount("(*)", "blacklist", "blacklist_ip='".FUSION_IP."' OR blacklist_ip='$sub_ip1' OR blacklist_ip='$sub_ip2'")) {
	header("Location: http://www.google.com/"); exit;
}

// PHP-Fusion user cookie functions
if (!isset($_COOKIE['fusion_visited'])) {
	$result=dbquery("UPDATE ".$fusion_prefix."settings SET counter=counter+1");
	setcookie("fusion_visited", "yes", time() + 31536000, "/", "", "0");
}

if (isset($_POST['login'])) {
	$user_name = stripinput($_POST['user_name']);
	$user_pass = md5($_POST['user_pass']);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_name='$user_name' AND user_password='$user_pass'");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		$cookie_value = $data['user_id'].".".$data['user_password'];
		if ($data['user_ban'] != 1) {			
			if (isset($_POST['remember_me'])) { $cookie_exp = time() + 3600*24*30; } else { $cookie_exp = time() + 3600*3; }
			setcookie("fusion_user", $cookie_value, $cookie_exp, "/", "", "0");
			header("Location: ".FUSION_BASE."index.php");
		} else {
			$loginerror = "This account is suspended<br><br>\n";
		}
	}
}

if (isset($_REQUEST['logout']) && $_REQUEST['logout'] == "yes") {
	setcookie("fusion_user", "", time() - 7200, "/", "", "0");
	setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
	header("Location: ".FUSION_BASE."index.php");
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
			setcookie("fusion_user", "", time() - 7200, "/", "", "0");
			setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
			header("Location: ".FUSION_BASE."index.php");
		}
	} else {
		setcookie("fusion_user", "", time() - 7200, "/", "", "0");
		setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
		header("Location: ".FUSION_BASE."index.php");
	}
} else {
	define("FUSION_THEME", FUSION_THEMES.$settings['theme']."/");
	$userdata = "";	$userdata['user_level'] = 0; $userdata['user_rights'] = ""; $userdata['user_groups'] = "";
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (QUOTES_GPC) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

function stripslash($text) {
	if (QUOTES_GPC) $text = stripslashes($text);
	return $text;
}

function addslash($text) {
	if (!QUOTES_GPC) {
		$text = addslashes(addslashes($text));
	} else {
		$text = addslashes($text);
	}
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
function isnum($value) {
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
	$smiles = "";
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
	$message = preg_replace($ubbs1, $ubbs2, $message);
	
	// Prevent use of mallicious javascript
	$message = preg_replace_callback("/&#([0-9]{1,3});/", create_function('$matches', 'return chr($matches[1]);'), $message);
	$message = preg_replace_callback("/&#([0-9]{1,3})/", create_function('$matches', 'return chr($matches[1]);'), $message);
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
	$message = preg_replace($text1, $text2, $message);
	
	return $message;
}

function descript($text) {
	// Prevent use of mallicious javascript in any non-stripped document
	$text = preg_replace_callback("/&#([0-9]{1,3});/", create_function('$matches', 'return chr($matches[1]);'), $text);
	$text = preg_replace_callback("/&#([0-9]{1,3})/", create_function('$matches', 'return chr($matches[1]);'), $text);
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
	$text1[11] = "#alert#si"; $text2[11] = 'ale<i></i>rt';
	$text1[12] = "#script#si"; $text2[12] = 'scr<i></i>ipt';
	$text1[13] = "#iframe#si"; $text2[13] = 'ifr<i></i>ame';
	$text = preg_replace($text1, $text2, $text);
	return $text;
}

// Replace offensive words with the defined replacement word
function censorwords($text) {
	global $settings;
	if ($settings['bad_words']!="") {
		$word_list = explode("\r\n", $settings['bad_words']);
		for ($i=0;$i < count($word_list);$i++) {
			$text = preg_replace("/".$word_list[$i]."/si", $settings['bad_word_replace'], $text);
		}
	}
	return $text;
}

// Display the user's moderator level
function getuserlevel($userlevel) {
	if ($userlevel==250) { return USER1; }
	elseif ($userlevel==251) { return USER2; }
	elseif ($userlevel==252) { return USER3; }
}

// Check if Administrator has correct rights assigned
function checkrights($right) {
	if (iADMIN && in_array($right, explode(".", iUSER_RIGHTS))) {
		return true;
	} else {
		return false;
	}
}

// Check if user is assigned to the specified user group
function checkgroup($group) {
	if (($group == "0" || $group == "250" || $group == "251") && iADMIN) { return true; }
	elseif (($group == "0" || $group == "250") && iMEMBER) { return true; }
	elseif ($group == "0" && iGUEST) { return true; }
	elseif (in_array($group, explode(".", iUSER_GROUPS)) && iMEMBER) {
		return true;
	} else {
		return false;
	}
}

// Compile access levels & user group array
function getusergroups() {
	$groups_array = array(
		array("0", USER0),
		array("250", USER1),
		array("251", USER2)
	);
	$gsql = dbquery("SELECT group_id,group_name FROM ".FUSION_PREFIX."user_groups");
	while ($gdata = dbarray($gsql)) {
		array_push($groups_array, array($gdata['group_id'], $gdata['group_name']));
	}
	return $groups_array;
}

// Get the name of the access level or user group
function getgroupname($group) {
	if ($group == "0") { return USER0; }
	elseif ($group == "250") { return USER1; }
	elseif ($group >= "251") { return USER2;
	} else {
		$gsql = dbquery("SELECT group_id,group_name FROM ".FUSION_PREFIX."user_groups WHERE group_id='$group'");
		if (dbrows($gsql)!=0) {
			$gdata = dbarray($gsql);
			return $gdata['group_name'];
		} else {
			return "N/A";
		}
	}
}

// Universal page pagination function
function makepagenav($start,$count,$total,$range=0,$link=""){
	if ($link == "") $link = FUSION_SELF."?";
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

function createthumbnail($origfile,$thumbfile,$new_w,$new_h) {
	
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

function parsebytesize($size,$digits=2,$dir=false) {
	$kb=1024; $mb=1024*$kb; $gb=1024*$mb; $tb=1024*$gb;
	if (($size==0)&&($dir)) { return "Empty"; }
	elseif ($size<$kb) { return $size." Bytes"; }
	elseif ($size<$mb) { return round($size/$kb,$digits)." Kb"; }
	elseif ($size<$gb) { return round($size/$mb,$digits)." Mb"; }
	elseif ($size<$tb) { return round($size/$gb,$digits)." Gb"; }
	else { return round($size/$tb,$digits)." Tb"; }
}

// User level, Admin Rights & User Group definitions
define("iGUEST",$userdata['user_level'] == 0 ? 1 : 0);
define("iMEMBER", $userdata['user_level'] >= 250 ? 1 : 0);
define("iADMIN", $userdata['user_level'] >= 251 ? 1 : 0);
define("iSUPERADMIN", $userdata['user_level'] >= 252 ? 1 : 0);
define("iUSER", $userdata['user_level']);
define("iUSER_RIGHTS", $userdata['user_rights']);
define("iUSER_GROUPS", (strpos($userdata['user_groups'], ".") == 0 ? substr($userdata['user_groups'], 1) : $userdata['user_groups']));
?>