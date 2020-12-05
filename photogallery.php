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
	Photogallery written by CrappoMan
	simonpatterson@dsl.pipex.com
---------------------------------------------------
*/
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."photogallery.php";
require fusion_langdir."comments.php";
require "side_left.php";

if (isset($_POST['post_comment'])) {
	if (dbrows(dbquery("SELECT photo_id FROM ".$fusion_prefix."photos WHERE photo_id='$photo'"))==0) {
		header("Location: ".fusion_basedir."index.php");
		exit;
	}
	if (Member()) {
		$comment_name = $userdata['user_id'];
	} elseif ($settings['guestposts'] == "1") {
		$comment_name = stripinput($_POST['comment_name']);
		if (is_numeric($comment_name)) $comment_name="";
	}
	$comment_message = stripinput($_POST['comment_message']);
	if ($comment_name != "" && $comment_message != "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."comments VALUES('', '$photo', 'P', '$comment_name', '$comment_message', '".time()."', '$user_ip')");
	}
	header("Location: ".$PHP_SELF."?photo=$photo");
}

if(!isset($rowstart)) $rowstart=0;

if(isset($photo)){
	$result=dbquery("UPDATE ".$fusion_prefix."photos SET photo_views=(photo_views+1) WHERE photo_id='$photo'");
	$result=dbquery(
		"SELECT tp.*, user_name FROM ".$fusion_prefix."photos AS tp
		INNER JOIN ".$fusion_prefix."users USING (user_id) WHERE photo_id='".$photo."'"
	);
	if(dbrows($result)!=0){
		$data=dbarray($result);
		$phototitle=stripslashes($data['photo_title']);
		opentable(LAN_419.$phototitle);
		$img_filename="$gallery_dir/$photo.jpg";
		if(file_exists($img_filename)){
			echo "<center><img src='$gallery_url/$photo.jpg' border='0' title='$phototitle' alt='".LAN_405."'>";
			$pixsize=getimagesize($img_filename);
			echo "<div class='small2'>".LAN_420.$pixsize[0]." x ".$pixsize[1].LAN_421."(".parseByteSize(filesize($img_filename)).")<br>";
			echo LAN_422."<b>".$data['user_name']."</b>".LAN_423."<b>".strftime($settings['shortdate'], $data['photo_date']+($settings['timeoffset']*3600))."</b>.<br>";
			echo LAN_424."<b>".$data['photo_views']."</b>".LAN_425."</div></center>";
		}else{
			echo "<center><img src='$image_url/imagenotfound.jpg' border='1' alt='".LAN_405."'>";
		}
	}
	closetable();
	echo "<div align='center' class='small' style='margin-top:5px;'><a href='$PHP_SELF?album=".$data['album_id']."'>".LAN_427."</a></div>";
	if($settings['album_comments']=="1"){
		$comment_type = "P"; $comment_item_id = "$photo"; $comment_link = "$PHP_SELF?photo=$photo";
		require fusion_basedir."fusion_core/comments_panel.php";
	}
}elseif(isset($album)){
	$data=dbarray(dbquery(
		"SELECT ta.*, COUNT(photo_id) as photo_count, MAX(photo_date) as max_date, user_name
		FROM ".$fusion_prefix."photo_albums AS ta
		LEFT JOIN ".$fusion_prefix."photos USING (album_id)
		LEFT JOIN ".$fusion_prefix."users USING (user_id)
		WHERE ta.album_id='".$album."' GROUP BY album_id"
	));
	$piccnt=$data['photo_count'];
	$albumtitle=stripslashes($data['album_title']);
	$albuminfo=stripslashes($data['album_info']);
	opentable(LAN_408.$albumtitle);
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td width='25%' rowspan='2' align='center'><img class='activegallery' src='";
	if(file_exists($gallery_dir."/a".$data['album_id'].".jpg")){
		echo $gallery_url."/a".$data['album_id'].".jpg' width='".$settings['thumb_image_w']."' height='".$settings['thumb_image_h']."";
	}else{
		echo "$image_url/imagenotfound.jpg";
	}
	echo "' title='".($albuminfo==""?$albumtitle:$albuminfo)."' alt='".LAN_405."'></a>";
	echo "<td width='75%' valign='top'>".($albuminfo==""?$albumtitle:$albuminfo)."</td></tr>";
	echo "<tr><td width='75%' valign='bottom' class='small2'><hr>".LAN_409."<b>";
	if($data[photo_count]>0){
		echo "$data[photo_count]</b><br>".LAN_410."<b>".strftime($settings['shortdate'], $data['max_date']+($settings['timeoffset']*3600))."</b>".LAN_411."<b>".$data['user_name']."</b>";
	}else{
		echo LAN_412."</b><br><br>";
	}
	echo "</td></tr></table>";
	closetable();
	tablebreak();
	opentable(LAN_413);
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	$result=dbquery(
		"SELECT tp.*, COUNT(comment_item_id) AS comment_count
		FROM ".$fusion_prefix."photos AS tp LEFT JOIN ".$fusion_prefix."comments
		ON photo_id = comment_item_id AND comment_type='P'
		WHERE album_id='".$album."' GROUP BY photo_id
		ORDER BY photo_order LIMIT ".$rowstart.",".$settings['thumbs_per_page']
	);
	if(dbrows($result)>0){
		$img_cnt=0;
		while($data=dbarray($result)){
			$phototitle=stripslashes($data['photo_title']);
			echo "<td class='gallery' width='".round(100 / $settings['thumbs_per_row'])."%' align='center' valign='top'><a href='$PHP_SELF?photo=$data[photo_id]' class='gallery'>";
			echo "<img src='";
			if(file_exists("$gallery_dir/".$data['photo_id']."t.jpg")){
				echo "$gallery_url/".$data['photo_id']."t.jpg";
			}else{
				echo "$image_url/imagenotfound.jpg";
			}
			echo "' width='".$settings['thumb_image_w']."' height='".$settings['thumb_image_h']."' title='$phototitle' alt='".LAN_405."'></a><br>";
			echo "$phototitle<br><span class='small2'>".LAN_414."<b>";
			if($data['photo_views']==0){
				echo LAN_412;
			}else{
				echo $data['photo_views'];
			}
			echo "</b><br>";
			if($data['comment_count']==0){
				echo LAN_415;
			}else{
				echo "<b>".$data['comment_count']."</b> ".($data['comment_count']==1?LAN_416:LAN_417);
			}
			echo "</span>";
			$img_cnt++;
			if(($img_cnt%$settings['thumbs_per_row'])==0) echo "</tr><tr>";
		}
	}else{
		echo "<td align='center'>".LAN_418."</td>";
	}
	echo "</tr></table>";
	closetable();
	echo "<div align='center' style='margin-top:5px;'>".makePageNav($rowstart,$settings['thumbs_per_page'],$piccnt,3,"$PHP_SELF?album=$album&")."</div>\n";
	echo "<div align='center' class='small' style='margin-top:5px;'><a href='$PHP_SELF'>".LAN_426."</a></div>";
}else{
	opentable(LAN_400);
	$albcnt=dbresult(dbquery("SELECT COUNT(album_id) FROM ".$fusion_prefix."photo_albums"), 0);
	if($albcnt!=0){
		$result=dbquery(
			"SELECT COUNT(photo_id) AS photo_count, MAX(photo_date) AS max_date, ta.*
			FROM ".$fusion_prefix."photo_albums AS ta LEFT JOIN ".$fusion_prefix."photos USING (album_id)
			GROUP BY album_id ORDER BY album_order LIMIT ".$rowstart.",".$settings[albums_per_page]
		);
		echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
		$img_cnt=0;
		while($data=dbarray($result)){
			$albumtitle=stripslashes($data['album_title']);
			$albuminfo=stripslashes($data['album_info']);
			echo "<td class='gallery' width='".round(100/$settings['albums_per_row'])."%' align='center' valign='top'>";
			echo "<a href='$PHP_SELF?album=".$data['album_id']."' class='gallery'><img ";
			if(file_exists($gallery_dir."/a".$data['album_id'].".jpg")){
				echo "src='".$gallery_url."/a".$data['album_id'].".jpg' width='".$settings['album_image_w']."' height='".$settings['album_image_h']."'";
			}else{
				echo "src='$image_url/imagenotfound.jpg'";
			}
			echo " title='".($albuminfo==""?$albumtitle:$albuminfo)."' alt='".LAN_405."'></a>";
			echo "<br>$albumtitle<br><span class='small2'>";
			if($data['photo_count']!=0){
				echo $data['photo_count'].($data['photo_count']==1?LAN_403:LAN_404)."<br>".LAN_402.strftime($settings['shortdate'], $data['max_date']+($settings['timeoffset']*3600));
			}else{
				echo LAN_401;
			}
			if($data['max_date']!=NULL && (time()-604800) < $data['max_date']){
				echo "<br><span class='small2'>".LAN_406."</span>";
			}
			echo "</span>";
			$img_cnt++;
			if(($img_cnt%$settings['albums_per_row'])==0) echo "</tr><tr>";
		}
		echo "</tr></table>";
		closetable();
		echo "<div align='center' style='margin-top:5px;'>".makePageNav($rowstart,$settings['albums_per_page'],$albcnt,3)."</div>\n";
	}else{
		echo "<center><br>".LAN_407."<br><br></center>\n";
		closetable();
	}
}

require "side_right.php";
require "footer.php";
?>