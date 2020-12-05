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
// Users Online Code
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
			$result = dbquery("INSERT INTO ".$fusion_prefix."online VALUES('$name', '$user_ip', '".time()."')");
		}
	} else {
		$result = dbquery("INSERT INTO ".$fusion_prefix."online VALUES('$name', '$user_ip', '".time()."')");
	}
}
if (isset($_POST['login'])) {
	$result = dbquery("DELETE FROM ".$fusion_prefix."online WHERE online_user='0.Guest' AND online_ip='$user_ip'");
} else if (isset($logout)) {
	$result = dbquery("DELETE FROM ".$fusion_prefix."online WHERE online_ip='$user_ip'");
}
$deletetime = time() - 60;
$result = dbquery("DELETE FROM ".$fusion_prefix."online WHERE online_lastactive < $deletetime");

// General Core Functions
function getmodlevel($modlevel) {
	$levels = array(1=> USER1, USER2, USER3, USER4);
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
	$ubbs[0] = '#\[b\](.*?)\[/b\]#si';
	$ubbs2[0] = '<b>\1</b>';
	$ubbs[1] = '#\[i\](.*?)\[/i\]#si';
	$ubbs2[1] = '<i>\1</i>';
	$ubbs[2] = '#\[u\](.*?)\[/u\]#si';
	$ubbs2[2] = '<u>\1</u>';
	$ubbs[3] = '#\[center\](.*?)\[/center\]#si';
	$ubbs2[3] = '<center>\1</center>';
	$ubbs[4] = '#\[url\]http://(.*?)\[/url\]#si';
	$ubbs2[4] = '<a href="http://\1" target="_blank">http://\1</a>';
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
	$ubbs2[10] = '<blockquote><pre><div class="quote">\1</div></pre></blockquote>';
	$ubbs[11] = '#\[url\](.*?)\[/url\]#si';
	$ubbs2[11] = '<a href="http://\1" target="_blank">\1</a>';
	// Javascript Stripping
	$ubbs[12] = '#javascript#si'; $ubbs2[12] = 'javascr<i></i>ipt';
	$ubbs[13] = "#script#si"; $ubbs2[13] = 'scri<i></i>pt';
	$ubbs[14] = "#document#si"; $ubbs2[14] = 'docu<i></i>ment';
	$ubbs[15] = "#expression#si"; $ubbs2[15] = 'expres<i></i>sion';
	$ubbs[16] = "#onmouseover#si"; $ubbs2[16] = 'onmouse<i></i>over';
	$ubbs[17] = "#onclick#si"; $ubbs2[17] = 'on<i></i>click';
	$ubbs[18] = "#onmousedown#si"; $ubbs2[18] = 'onmouse<i></i>down';
	$ubbs[19] = "#onmouseup#si"; $ubbs2[19] = 'onmouse<i></i>up';
	$ubbs[20] = "#ondblclick#si"; $ubbs2[20] = 'on<i></i>dblclick';
	$ubbs[21] = "#onmouseout#si"; $ubbs2[21] = 'onmouse<i></i>out';
	$ubbs[22] = "#onmousemove#si"; $ubbs2[22] = 'onmouse<i></i>move';
	$ubbs[23] = "#onload#si"; $ubbs2[23] = 'on<i></i>load';
	$ubbs[24] = "#background:url#si"; $ubbs2[24] = 'background<i></i>:url';
	for ($i=0;$ubbs[$i]!="";$i++) {
		$message = preg_replace($ubbs, $ubbs2, $message);
	}
	return $message;
}

function encscript($text) {
	$text1[0] = '#javascript#si'; $text2[0] = 'javascr<i></i>ipt';
	$text1[1] = "#script#si"; $text2[1] = 'scri<i></i>pt';
	$text1[2] = "#document#si"; $text2[2] = 'docu<i></i>ment';
	$text1[3] = "#expression#si"; $text2[3] = 'expres<i></i>sion';
	$text1[4] = "#onmouseover#si"; $text2[4] = 'onmouse<i></i>over';
	$text1[5] = "#onclick#si"; $text2[5] = 'on<i></i>click';
	$text1[6] = "#onmousedown#si"; $text2[6] = 'onmouse<i></i>down';
	$text1[7] = "#onmouseup#si"; $text2[7] = 'onmouse<i></i>up';
	$text1[8] = "#ondblclick#si"; $text2[8] = 'on<i></i>dblclick';
	$text1[9] = "#onmouseout#si"; $text2[9] = 'onmouse<i></i>out';
	$text1[10] = "#onmousemove#si"; $text2[10] = 'onmouse<i></i>move';
	$text1[11] = "#onload#si"; $text2[11] = 'on<i></i>load';
	$text1[12] = "#background:url#si"; $text2[12] = 'background<i></i>:url';
	for ($i=0;$text[$i]!="";$i++) {
		$text = preg_replace($text1, $text2, $text);
	}
	return $text;
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

function makePageNav($start,$count,$total,$range=0,$cls="",$link=""){
	if($link=="")$link=$PHP_SELF."?";
	$res="";
	$pg_cnt=ceil($total / $count);
	if($pg_cnt > 1){
		$idx_back = $start - $count;
		$idx_next = $start + $count;
		$cur_page=ceil(($start + 1) / $count);
		$res.="<table cellspacing='1' cellpadding='1' border='0' class='forum-border'>\n<tr>\n";
		$res.="<td class='altbg'><span class='small'>".LAN_52."$cur_page".LAN_53."$pg_cnt</span></td>\n";
		if($idx_back >= 0) {
			if ($cur_page > ($range + 1)) $res.="<td class='forum2'><a class='$cls' href='$link"."rowstart=0'>&lt;&lt;</a></td>\n";
			$res.="<td class='forum2'><a class='$cls' href='$link"."rowstart=$idx_back'>&lt;</a></td>\n";
		}
		$idx_fst=max($cur_page - $range, 1);
		$idx_lst=min($cur_page + $range, $pg_cnt);
		if($range==0){
			$idx_fst = 1;
			$idx_lst=$pg_cnt;
		}
		for($i=$idx_fst;$i<=$idx_lst;$i++){
			$offset_page=($i - 1) * $count;
			if($i==$cur_page){
				$res.="<td class='forum1'><span class='$cls'><b>$i</b></span></td>\n";
			}else{
				$res.="<td class='forum1'><a class='$cls' href='$link"."rowstart=$offset_page'>$i</a></td>\n";
			}
		}
		if($idx_next < $total) {
			$res.="<td class='forum2'><a class='$cls' href='$link"."rowstart=$idx_next'>&gt;</a></td>\n";
			if ($cur_page < ($pg_cnt - $range)) $res.="<td class='forum2'><a class='$cls' href='$link"."rowstart=".($pg_cnt-1)*$count."'>&gt;&gt;</a></td>\n";
		}
		$res.="</tr>\n</table>\n";

	}
	return $res;
}

define("FUSION_PUBLIC", 0);
define("FUSION_MEMBER", 1);
define("FUSION_MODERATOR", 2);
define("FUSION_ADMIN", 3);
define("FUSION_SUPERADMIN", 4);

function Guest() {
	return $GLOBALS['userdata']['user_mod']==FUSION_PUBLIC;
}

function Member() {
	return $GLOBALS['userdata']['user_mod']>=FUSION_MEMBER;
}

function Moderator() {
	return $GLOBALS['userdata']['user_mod']>=FUSION_MODERATOR;
}

function Admin() {
	return $GLOBALS['userdata']['user_mod']>=FUSION_ADMIN;
}

function SuperAdmin() {
	return $GLOBALS['userdata']['user_mod']==FUSION_SUPERADMIN;
}

function UserLevel() {
	return $GLOBALS['userdata']['user_mod'];
}

function parseByteSize($size,$digits=2,$dir=false){
  $kb=1024; $mb=1024*$kb; $gb=1024*$mb; $tb=1024*$gb;
  if(($size==0)&&($dir)){return "Empty";}
  else if($size<$kb){return $size." Bytes";}
  else if($size<$mb){return round($size/$kb,$digits)." Kb";}
  else if($size<$gb){return round($size/$mb,$digits)." Mb";}
  else if($size<$tb){return round($size/$gb,$digits)." Gb";}
  else{return round($size/$tb,$digits)." Tb";}
}

//Path Config
$webroot=$_SERVER['DOCUMENT_ROOT'];
$image_url=fusion_basedir."fusion_images";
$image_dir=fusion_basedir."fusion_images";
$gallery_url=$image_url."/photoalbum";
$gallery_dir=$image_dir."/photoalbum";

//Photo Gallery Config
$settings["photocomments"]=1;  // allow photo comments
$settings["albumsperrow"]=4;  // number of albums per row
$settings["albumsperpage"]=12;  // number of albums per page
$settings["thumbsperrow"]=4;  // number of thumbs per row
$settings["thumbsperpage"]=16;  // number of photos per page

//size of album images
$album_thumb_image_width=80;
$album_thumb_image_height=80;
//size of photo thumbnails
$thumb_image_width=100;
$thumb_image_height=80;
//max size of photos
$upload_image_max_w=640;
$upload_image_max_h=640;
$upload_image_max_b=150000;
?>