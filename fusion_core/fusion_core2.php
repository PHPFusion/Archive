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
if (!defined("IN_FUSION")) header("Location: ../index.php");

// Users Online Code
if ($settings['maintenance'] != "1") {
	if ($userdata['user_mod'] != 0) { $cond = "'".$userdata['user_id']."'"; } else { $cond = "'0' AND online_ip='$user_ip'"; }
	$result = dbquery("SELECT * FROM ".$fusion_prefix."online WHERE online_user=".$cond."");
	if (dbrows($result) != 0) {
		$result = dbquery("UPDATE ".$fusion_prefix."online SET online_lastactive='".time()."' WHERE online_user=".$cond."");
	} else {
		if ($userdata['user_mod'] != 0) { $name = $userdata['user_id']; } else { $name = "0"; }
		$result = dbquery("INSERT INTO ".$fusion_prefix."online VALUES('$name', '$user_ip', '".time()."')");
	}
	if (isset($_POST['login'])) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."online WHERE online_user='0' AND online_ip='$user_ip'");
	} else if (isset($logout)) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."online WHERE online_ip='$user_ip'");
	}
}
$result = dbquery("DELETE FROM ".$fusion_prefix."online WHERE online_lastactive<".(time()-60)."");

// General Core Functions
function getmodlevel($modlevel) {
	$levels = array(1=> USER1, USER2, USER3, USER4);
	$modlevel = $levels[$modlevel];
	return $modlevel;
}

function displaysmileys() {
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
	foreach($smileys as $key=>$smiley) {
		echo "<a href=\"javascript:insertText('$key');\"><img src='".fusion_basedir."fusion_images/smiley/$smiley' border='0'></a>\n";
	}
}

function parsesmileys($message) {
	$smiley = array(
		"/\:\)/si" => "<img src='".fusion_basedir."fusion_images/smiley/smile.gif'>",
		"/\;\)/si" => "<img src='".fusion_basedir."fusion_images/smiley/wink.gif'>",
		"/\:\(/si" => "<img src='".fusion_basedir."fusion_images/smiley/sad.gif'>",
		"/\:\|/si" => "<img src='".fusion_basedir."fusion_images/smiley/frown.gif'>",
		"/\:o/si" => "<img src='".fusion_basedir."fusion_images/smiley/shock.gif'>",
		"/\:p/si" => "<img src='".fusion_basedir."fusion_images/smiley/pfft.gif'>",
		"/b\)/si" => "<img src='".fusion_basedir."fusion_images/smiley/cool.gif'>",
		"/\:d/si" => "<img src='".fusion_basedir."fusion_images/smiley/grin.gif'>",
		"/\:@/si" => "<img src='".fusion_basedir."fusion_images/smiley/angry.gif'>"
	);
	foreach($smiley as $key=>$smiley_img){
		$message = preg_replace($key, $smiley_img, $message);
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
	for ($i=0;$ubbs[$i]!="";$i++) $message = preg_replace($ubbs, $ubbs2, $message);
	
	// Javascript Stripping
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

function encscript($text) {
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
	for ($i=0;$text1[$i]!="";$i++) {
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

function makePageNav($start,$count,$total,$range=0,$link=""){
	if($link=="")$link=$PHP_SELF."?";
	$res="";
	$pg_cnt=ceil($total / $count);
	if($pg_cnt > 1){
		$idx_back = $start - $count;
		$idx_next = $start + $count;
		$cur_page=ceil(($start + 1) / $count);
		$res.="<table cellspacing='1' cellpadding='1' border='0' class='tbl-border'>\n<tr>\n";
		$res.="<td class='altbg'><span class='small'>".LAN_52."$cur_page".LAN_53."$pg_cnt</span></td>\n";
		if($idx_back >= 0) {
			if ($cur_page > ($range + 1)) $res.="<td class='tbl2'><a class='small' href='$link"."rowstart=0'>&lt;&lt;</a></td>\n";
			$res.="<td class='tbl2'><a class='small' href='$link"."rowstart=$idx_back'>&lt;</a></td>\n";
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
				$res.="<td class='tbl1'><span class='small'><b>$i</b></span></td>\n";
			}else{
				$res.="<td class='tbl1'><a class='small' href='$link"."rowstart=$offset_page'>$i</a></td>\n";
			}
		}
		if($idx_next < $total) {
			$res.="<td class='tbl2'><a class='small' href='$link"."rowstart=$idx_next'>&gt;</a></td>\n";
			if ($cur_page < ($pg_cnt - $range)) $res.="<td class='tbl2'><a class='small' href='$link"."rowstart=".($pg_cnt-1)*$count."'>&gt;&gt;</a></td>\n";
		}
		$res.="</tr>\n</table>\n";

	}
	return $res;
}

function Guest() {
	return $GLOBALS['userdata']['user_mod']==0;
}

function Member() {
	return $GLOBALS['userdata']['user_mod']>=1;
}

function Moderator() {
	return $GLOBALS['userdata']['user_mod']>=2;
}

function Admin() {
	return $GLOBALS['userdata']['user_mod']>=3;
}

function SuperAdmin() {
	return $GLOBALS['userdata']['user_mod']==4;
}

function UserLevel() {
	return $GLOBALS['userdata']['user_mod'];
}

function constrainImage($width,$height,$max_width,$max_height){
	if(!$height||!$width||!$max_height||!$max_width){
		return false;
	}elseif($height>$max_height||$width>$max_width){
		if($height>$width){
			if($img_width>$max_width){
				$img_width=$max_width;
				$img_height=round(($max_width*$max_height)/$img_width);
			}else{
				$img_width=round(($width*$max_height)/$height);
				$img_height=$max_height;
			}
		}else{
			if($img_height>$max_height){
				$img_width=round(($max_width*$max_height)/$img_height);
				$img_height=$max_height;
			}else{
				$img_width=$max_width;
				$img_height=round(($height*$max_width)/$width);
			}
		}
		return array('width'=>$img_width,'height'=>$img_height);
	}else{
		return array('width'=>$width,'height'=>$height);
	}
}

$gd2=checkgd2();
function checkgd2(){
	$gd2=0;
	ob_start();
	phpinfo(8);
	$phpinfo=ob_get_contents();
	ob_end_clean();
	$phpinfo=strip_tags($phpinfo);
	$phpinfo=stristr($phpinfo,"gd version");
	$phpinfo=stristr($phpinfo,"version");
	preg_match('/\d/',$phpinfo,$gd);
	if ($gd[0]=='2'){$gd2=1;}
	return $gd2;
}

function createThumbnail($origfile,$thumbfile,$new_w,$new_h){
	global $gd2;
	$origimage=imagecreatefromjpeg($origfile);
	$origwidth=imagesx($origimage);
	$origheight=imagesy($origimage);
	$size=constrainImage($origwidth,$origheight,$new_w,$new_h);
	if(!$gd2){
		$thumbimage=imagecreate($new_w,$new_h);
		$background=imagecolorallocate($thumbimage,255,255,255);
		imagefill($thumbimage,0,0,$background);
		$result=imagecopyresized( $thumbimage, $origimage,round(($new_w-$size['width'])/2),round(($new_h-$size['height'])/2),0,0,$size['width'],$size['height'],$origwidth,$origheight);
	}else{
		$thumbimage=imagecreatetruecolor($new_w,$new_h);
		$background=imagecolorallocate($thumbimage,255,255,255);
		imagefill($thumbimage,0,0,$background);
		$result=imagecopyresampled( $thumbimage, $origimage,round(($new_w-$size['width'])/2),round(($new_h-$size['height'])/2),0,0,$size['width'],$size['height'],$origwidth,$origheight);
	}
	imageJPEG($thumbimage,$thumbfile);
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
$image_url=fusion_basedir."fusion_images";
$image_dir=fusion_basedir."fusion_images";
$gallery_url=$image_url."/photoalbum";
$gallery_dir=$image_dir."/photoalbum";
?>