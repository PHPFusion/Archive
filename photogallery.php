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
+---------------------------------------------+
| Photo Gallery coded by CrappoMan            |
| email: simonpatterson@dsl.pipex.com         |
+--------------------------------------------*/
@include "fusion_config.php";
include "fusion_core.php";
include "subheader.php";
include FUSION_LANGUAGES.FUSION_LAN."photogallery.php";
include FUSION_INCLUDES."comments_include.php";
include "side_left.php";

function checkImageExists($image_file) {
	if(file_exists($image_file)) {
		return $image_file;
	}else{
		return FUSION_IMAGES."imagenotfound.jpg";
	}
}

if (!$rowstart) $rowstart = 0;

if(isset($photo)){
	$result=dbquery("UPDATE ".$fusion_prefix."photos SET photo_views=(photo_views+1) WHERE photo_id='".$photo."'");
	$result=dbquery("SELECT tp.*, user_name FROM ".$fusion_prefix."photos AS tp INNER JOIN ".$fusion_prefix."users USING (user_id) WHERE photo_id='".$photo."'");
	$data=dbarray($result);
	opentable(LAN_419.$data['photo_title']);
	if(dbrows($result)!=1){
		echo "<center><br>".LAN_428."<br><br></center>\n";
	}else{
		$img_filename = FUSION_PHOTOS.$photo.".jpg";
		$imgsize=@getimagesize($img_filename);
		$prev=@dbresult(@dbquery("SELECT t2.photo_id FROM ".$fusion_prefix."photos t1 JOIN ".$fusion_prefix."photos t2 WHERE t1.photo_order=t2.photo_order+1 AND t1.album_id=t2.album_id AND t1.photo_id='".$photo."'"),0);
		$next=@dbresult(@dbquery("SELECT t2.photo_id FROM ".$fusion_prefix."photos t1 JOIN ".$fusion_prefix."photos t2 WHERE t1.photo_order=t2.photo_order-1 AND t1.album_id=t2.album_id AND t1.photo_id='".$photo."'"),0);
		echo "<div align='center' style='margin:5px 0px;'>
<table border='0' align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>
<td class='tbl1'><span class='small'><a href='".$PHP_SELF."?".(empty($prev)?"album=".$data['album_id']:"photo=".$prev)."'>".LAN_429."</a></span></td>
<td class='tbl2'><span class='small'><a href='".$PHP_SELF."?album=".$data['album_id']."'>".LAN_427."</a></span></td>
<td class='tbl1'><span class='small'><a href='".$PHP_SELF."?".(empty($next)?"album=".$data['album_id']:"photo=".$next)."'>".LAN_430."</a></span></td>
</tr>\n</table>\n</div>
<div align='center' style='margin:5px 0px;'>
<img src='".checkImageExists($img_filename)."' border='1' title='".$data['photo_title']."' title='".$data['photo_title']."' alt='".LAN_405."'>
</div>
<div align='center' style='margin:5px 0px;'>
<span class='small2'>".LAN_420.$imgsize[0]." x ".$imgsize[1].LAN_421."(".parseByteSize(filesize($img_filename)).")<br>
".LAN_422."<b>".$data['user_name']."</b>".LAN_423."<b>".showdate("shortdate", $data['photo_date'])."</b>.<br>
".LAN_424."<b>".$data['photo_views']."</b>".LAN_425."</span>
</div>";
	}
	closetable();
	if($settings['album_comments']=="1") showcomments("P","photos","photo_id",$photo,"$PHP_SELF?photo=$photo");
}elseif(isset($album)){
	$data=dbarray(dbquery(
		"SELECT ta.*, COUNT(photo_id) as photo_count, MAX(photo_date) as max_date, user_name
		FROM ".$fusion_prefix."photo_albums AS ta
		LEFT JOIN ".$fusion_prefix."photos USING (album_id)
		LEFT JOIN ".$fusion_prefix."users USING (user_id)
		WHERE ta.album_id='".$album."' GROUP BY album_id"
	));
	$piccnt=$data['photo_count'];
	opentable(LAN_408.$data['album_title']);
	echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td width='25%' rowspan='2' align='center'><img class='activegallery' src='".checkImageExists(FUSION_PHOTOS."a".$data['album_id'].".jpg")."' width='".$settings['album_image_w']."' height='".$settings['album_image_h']."' title='".($data['album_info']==""?$data['album_title']:$data['album_info'])."' alt='".LAN_405."'></td>
<td width='75%' valign='top'>".($data['album_info']==""?$data['album_title']:$data['album_info'])."</td>
</tr>
<tr>
<td valign='bottom' class='small2'><hr>".LAN_409."<b>".($data['photo_count']>0?"$data[photo_count]</b><br>".LAN_410."<b>".strftime($settings['shortdate'], $data['max_date']+($settings['timeoffset']*3600))."</b>".LAN_411."<b>".$data['user_name']."</b>":LAN_412."</b><br /><br />")."</td>
</tr>
<tr>
<td align='center' colspan='2' class='small'><br><a href='$PHP_SELF'>".LAN_426."</a></td>
</tr>
</table>";
	closetable();
	tablebreak();
	opentable(LAN_413);
	$result=dbquery(
		"SELECT tp.*, COUNT(comment_item_id) AS comment_count
		FROM ".$fusion_prefix."photos AS tp LEFT JOIN ".$fusion_prefix."comments
		ON photo_id = comment_item_id AND comment_type='P'
		WHERE album_id='".$album."' GROUP BY photo_id
		ORDER BY photo_order LIMIT ".$rowstart.",".$settings['thumbs_per_page']
	);
	if(dbrows($result)>0){
		echo "<table width='100%' cellpadding='0' cellspacing='0'>\n<tr>\n";
		$img_cnt=0;
		while($data=dbarray($result)){
			echo "<td class='gallery' width='".round(100/$settings['thumbs_per_row'])."%' align='center' valign='top'>
<a href='$PHP_SELF?photo=$data[photo_id]' class='gallery'>
<img src='".checkImageExists(FUSION_PHOTOS.$data['photo_id']."t.jpg")."' width='".$settings['thumb_image_w']."' height='".$settings['thumb_image_h']."' title='".$data['photo_title']."' alt='".LAN_405."'>
</a><br />
".$data['photo_title']."<br />
<span class='small2'>".LAN_414."<b>".($data['photo_views']==0?LAN_412:$data['photo_views'])."</b><br />
".($data['comment_count']==0?LAN_415:"<b>".$data['comment_count']."</b> ".($data['comment_count']==1?LAN_416:LAN_417))."</span>";
			if(++$img_cnt%$settings['thumbs_per_row']==0) echo "</tr>\n<tr>\n";
		}
		echo "</tr>\n</table>\n";
	}else{
		echo "<center><br />".LAN_418."<br /><br /></center>";
	}
	closetable();
	echo "<div align='center' style='margin-top:5px;'>".makePageNav($rowstart,$settings['thumbs_per_page'],$piccnt,3,"$PHP_SELF?album=$album&")."</div>\n";
}else{
	opentable(LAN_400);
	$albcnt=dbresult(dbquery("SELECT COUNT(*) FROM ".$fusion_prefix."photo_albums"), 0);
	if($albcnt!=0){
		$result=dbquery(
			"SELECT COUNT(photo_id) AS photo_count, MAX(photo_date) AS max_date, ta.*
			FROM ".$fusion_prefix."photo_albums AS ta LEFT JOIN ".$fusion_prefix."photos USING (album_id)
			GROUP BY album_id ORDER BY album_order LIMIT ".$rowstart.",".$settings[albums_per_page]
		);
		echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
		$img_cnt=0;
		while($data=dbarray($result)){
			echo "<td class='gallery' width='".round(100/$settings['albums_per_row'])."%' align='center' valign='top'>
<a href='$PHP_SELF?album=".$data['album_id']."' class='gallery'>
<img src='".checkImageExists(FUSION_PHOTOS."a".$data['album_id'].".jpg")."' width='".$settings['album_image_w']."' height='".$settings['album_image_h']."' title='".($data['album_info']==""?$data['album_title']:$data['album_info'])."' alt='".LAN_405."' />
</a><br />
".$data['album_title']."<br />
<span class='small2'>".($data['photo_count']!=0?$data['photo_count'].($data['photo_count']==1?LAN_403:LAN_404)."<br>".LAN_402.showdate("shortdate", $data['max_date']):LAN_401);
			if($data['max_date']!=NULL && (time()-604800) < $data['max_date']){
				echo "<br /><span class='small2'>".LAN_406."</span>";
			}
			echo "</span>";
			if(++$img_cnt%$settings['albums_per_row']==0) echo "</tr>\n<tr>\n";
		}
		echo "</tr>\n</table>\n";
		echo "<div align='center' style='margin-top:5px;'>".makePageNav($rowstart,$settings['albums_per_page'],$albcnt,3)."</div>\n";
	}else{
		echo "<center><br>".LAN_407."<br><br></center>\n";
	}
	closetable();
}

require "side_right.php";
require "footer.php";
?>
