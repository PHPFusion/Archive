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
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_main.php";
require fusion_langdir."admin/admin_photoalbums.php";
require "navigation.php";

if(!Admin()){
	header("Location: ../index.php");
}elseif(isset($_POST['btn_cancel'])){
	header("Location: $PHP_SELF");
}elseif(($action=="deletealbumpic") && isset($album_id)){
	if(file_exists($gallery_dir."/a".$album_id.".jpg")) unlink($gallery_dir."/a".$album_id.".jpg");
	header("Location: $PHP_SELF?action=editalbum&album_id=$album_id");
}elseif($action=="deletealbum"){	// delete album
	if(isset($album_id)){
		$result=dbquery("SELECT * FROM ".$fusion_prefix."photos WHERE album_id='$album_id'");
		if(dbrows($result)!=0){
			opentable(LAN_420);
			echo "<center><br>".LAN_422."<br><span class='small'>".LAN_423."</span><br><br>
<a href='$PHP_SELF'>".LAN_429."</a><br><br>
<a href='".fusion_basedir."index.php'>".LAN_430."</a><br><br></center>\n";
			closetable();
		}else{
			$odata=dbarray(dbquery("SELECT * FROM ".$fusion_prefix."photo_albums WHERE album_id='$album_id'"));
			$result=dbquery("UPDATE ".$fusion_prefix."photo_albums SET album_order=(album_order-1) WHERE album_order>'$odata[album_order]'");
			$result=dbquery("DELETE FROM ".$fusion_prefix."photo_albums WHERE album_id='$album_id'");
			if(file_exists($gallery_dir."/a".$album_id.".jpg")) unlink($gallery_dir."/a".$album_id.".jpg");
			header("Location: $PHP_SELF");
		}
	}
}elseif(isset($_POST['btn_save_album'])){
	$album_title=stripinput($album_title);
	$album_info=stripinput($album_info);
	$result=dbquery("SELECT * FROM ".$fusion_prefix."photo_albums WHERE album_title='$album_title' AND album_id<>'$album_id'");
	if(dbrows($result)!=0){
		$error=LAN_424;
	}else{
		$error="";
		if($action=="editalbum"){
			if($album_order<$album_order2){
				$result=dbquery("UPDATE ".$fusion_prefix."photo_albums SET album_order=(album_order+1) WHERE album_order>='$album_order' AND album_order<'$album_order2'");
			}elseif($album_order>$album_order2){
				$result=dbquery("UPDATE ".$fusion_prefix."photo_albums SET album_order=(album_order-1) WHERE album_order>'$album_order2' AND album_order<='$album_order'");
			}
			$result=dbquery("UPDATE ".$fusion_prefix."photo_albums SET album_title='$album_title',album_info='$album_info',album_order='$album_order' WHERE album_id='$album_id'");
		}else{
			if($album_order==""){
				$album_order=dbresult(dbquery("SELECT MAX(album_order) FROM ".$fusion_prefix."photo_albums"),0)+1;
			}else{
				$result=dbquery("UPDATE ".$fusion_prefix."photo_albums SET album_order=(album_order+1) WHERE album_order>='$album_order'");
			}
			$result=dbquery("INSERT INTO ".$fusion_prefix."photo_albums VALUES ('','$album_title','$album_info','$album_order')");
		}
		$album_id=dbresult(dbquery("SELECT album_id FROM ".$fusion_prefix."photo_albums WHERE album_title='$album_title'"),0);
		if(is_uploaded_file($_FILES['album_pic_file']['tmp_name'])){
			if($_FILES['album_pic_file']['size']>=$settings['album_max_b']){
				$error=sprintf(LAN_425, $settings['album_max_b']);
			}elseif(!$imageFile=@getImageSize($_FILES['album_pic_file']['tmp_name'])){
				$error=LAN_426;
			}elseif($imageFile[2]<>2){
				$error=LAN_427;
			}elseif($imageFile[0]>$settings['album_max_w']||$imageFile[1]>$settings['album_max_h']){
				$error=sprintf(LAN_428, $settings['album_max_w'], $settings['album_max_h']);
			}else{
				if(file_exists($gallery_dir."/a".$album_id.".jpg")){unlink($gallery_dir."/a".$album_id.".jpg");}
				move_uploaded_file($_FILES['album_pic_file']['tmp_name'], $gallery_dir."/".basename($_FILES['album_pic_file']['tmp_name']));
				createThumbnail($gallery_dir."/".basename($_FILES['album_pic_file']['tmp_name']),$gallery_dir."/a".$album_id.".jpg",$settings['album_image_w'],$settings['album_image_h']);
				unlink($gallery_dir."/".basename($_FILES['album_pic_file']['tmp_name']));
			}
		}
	}
	if($error<>""){
		opentable(LAN_420);
		echo "<center><br><span class='small'>".LAN_421."</span><br><span class='small'>$error</span><br><br>
<a href='$PHP_SELF'>".LAN_429."</a><br><br>
<a href='".fusion_basedir."index.php'>".LAN_430."</a><br><br></center>\n";
		closetable();
	}else{
		header("Location: $PHP_SELF");
	}
}elseif($action=="mup"){
	$data=dbarray(dbquery(
		"SELECT t1.album_id id1, t2.album_id id2
		FROM ".$fusion_prefix."photo_albums t1 INNER JOIN ".$fusion_prefix."photo_albums t2
		WHERE t1.album_order = t2.album_order+1 AND t1.album_id = '$album_id'"
	));
	$id1=$data['id1'];
	$id2=$data['id2'];
	$result=dbquery("UPDATE ".$fusion_prefix."photo_albums SET album_order=album_order-1 WHERE album_id='$id1'");
	$result=dbquery("UPDATE ".$fusion_prefix."photo_albums SET album_order=album_order+1 WHERE album_id='$id2'");
	header("Location: $PHP_SELF");
}elseif($action=="mdown"){
	$data=dbarray(dbquery(
		"SELECT t1.album_id id1, t2.album_id id2
		FROM ".$fusion_prefix."photo_albums t1 INNER JOIN ".$fusion_prefix."photo_albums t2
		WHERE t1.album_order = t2.album_order-1 AND t1.album_id = '$album_id'"
	));
	$id1=$data['id1'];
	$id2=$data['id2'];
	$result=dbquery("UPDATE ".$fusion_prefix."photo_albums SET album_order=album_order+1 WHERE album_id='$id1'");
	$result=dbquery("UPDATE ".$fusion_prefix."photo_albums SET album_order=album_order-1 WHERE album_id='$id2'");
	header("Location: $PHP_SELF");
}else{
	if($action=="editalbum"){
		$result=dbquery("SELECT * FROM ".$fusion_prefix."photo_albums WHERE album_id='$album_id'");
		$data=dbarray($result);
		$album_title=stripslashes($data['album_title']);
		$album_info=stripslashes($data['album_info']);
		$album_order=$data['album_order'];
		opentable(LAN_402);
	}else{
		opentable(LAN_403);
	}
	echo "<form name='frm_add_album' method='post' action='$PHP_SELF' enctype='multipart/form-data'>\n";
	if($action=="editalbum"){
		echo "<input type='hidden' name='action' value='editalbum'>
<input type='hidden' name='album_id' value='$album_id'>
<input type='hidden' name='album_order2' value='$album_order'>";
	}
	echo "<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr><td>".LAN_405.":</td><td><input type='textbox' name='album_title' value=\"$album_title\" maxlength='100' class='textbox' style='width:250px;'></td></tr>
<tr><td valign='top'>".LAN_410.":</td><td><textarea name='album_info' class='textbox' style='width:250px; height:50px;'>$album_info</textarea></td></tr>
<tr><td>".LAN_408.":</td><td><input type='textbox' name='album_order' value='$album_order' maxlength='2' class='textbox' style='width: 40px;'>	<span class='small alt'>(";
	if($action=="editalbum"){
		echo LAN_416.$album_order;
	}else{
		echo LAN_417;
	}
	echo ")</span></td></tr><tr><td valign='top'>".LAN_411.":";
	if(file_exists($gallery_dir."/a".$album_id.".jpg")){
		echo "<br><br><a class='small' href='$PHP_SELF?action=deletealbumpic&album_id=$album_id'>".LAN_412."</a></td><td><img src='".$gallery_url."/a".$album_id.".jpg' border='1' alt='".LAN_418."' width='".$settings[album_image_w]."' height='".$settings[album_image_h]."'>";
	}else{
		echo "</td><td><input type='file' name='album_pic_file' class='textbox' style='width:250px;'><br>
<span class='small alt'>".sprintf(LAN_419, $settings['album_image_w'], $settings['album_image_h'])."</span>";
	}
	echo "</td></tr>\n";
	echo "<tr><td colspan='2' align='center'><hr><input type='submit' name='btn_save_album' value='".LAN_414."' class='button' style='width:80px;'>	\n";
	if($action=="editalbum"){
		echo "<input type='submit' name='btn_cancel' value='".LAN_415."' class='button' style='width:80px;'>\n";
	}
	echo "</td></tr>\n</table></form>\n";
	closetable();
	tablebreak();
	if(!isset($action)){
		opentable(LAN_400);
		$rows=dbrows(dbquery("SELECT * FROM ".$fusion_prefix."photo_albums"));
		if($rows!=0){
			$result=dbquery(
				"SELECT ta.*, COUNT(tp.photo_id) photo_count
				FROM ".$fusion_prefix."photo_albums ta LEFT JOIN ".$fusion_prefix."photos tp
				USING (album_id) GROUP BY album_id ORDER BY album_order"
			);
			echo "<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>
<tr class='tbl2'>
<td>".LAN_404."</td>
<td>".LAN_405."</td>
<td align='center'>".LAN_406."</td>
<td align='center'>".LAN_407."</td>
<td align='center' colspan='2'>".LAN_408."</td>
<td>".LAN_409."</td>
</tr>\n";
			$r=0;
			while($data=dbarray($result)){
				$album_id=$data['album_id'];
				$album_title=stripslashes($data['album_title']);
				$album_info=stripslashes($data['album_info']);
				$album_order=$data['album_order'];
				$photo_count=$data['photo_count'];
				if($photo_count==0) $photo_count=LAN_432;
				$comment_count=$data['comment_count'];
				if($rows!=1){
					if($album_order==1){
						$up_down=" <a href='$PHP_SELF?action=mdown&album_id=$album_id'><img src='".fusion_themedir."images/down.gif' border='0' /></a>";
					}elseif($album_order<$rows){
						$up_down=" <a href='$PHP_SELF?action=mup&album_id=$album_id'><img src='".fusion_themedir."images/up.gif' border='0' /></a>\n";
						$up_down.=" <a href='$PHP_SELF?action=mdown&album_id=$album_id'><img src='".fusion_themedir."images/down.gif' border='0' /></a>";
					}else{
						$up_down=" <a href='$PHP_SELF?action=mup&album_id=$album_id'><img src='".fusion_themedir."images/up.gif' border='0' /></a>";
					}
				}else{
					$up_down = "";
				}
				if(($r%2)==0){echo"<tr class='tbl1'>";}else{echo"<tr class='tbl2'>";}
				echo "<td>$album_id</td>
<td><a title='$album_info' href='$PHP_SELF?action=editalbum&album_id=".$data['album_id']."'>$album_title</a></td>
<td align='center'>";
				if(file_exists($gallery_dir."/a".$album_id.".jpg")){
					echo "<img src='$image_url/tick.gif'>";
				}else{
					echo " ";
				}
				echo "</td>\n
<td align='center'>$photo_count</td>
<td>$album_order</td>
<td>$up_down</td>
<td>";
				echo "<a href='photos.php?album_id=".$data['album_id']."'>".LAN_413."</a>";
				if ($photo_count==0){
					echo " | <a href='$PHP_SELF?action=deletealbum&album_id=".$data['album_id']."'>".LAN_412."</a>";
				}
				echo "</td></tr>";
				$r++;
			}
			echo "</table>\n";
			closetable();
		}else{
			echo "<center>".LAN_431."</center>\n";
			closetable();
		}
	}
}

echo "</td>\n";
require "../footer.php";
?>