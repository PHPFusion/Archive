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
+---------------------------------------------+
| Photo Gallery coded by CrappoMan            |
| email: simonpatterson@dsl.pipex.com         |
+--------------------------------------------*/
@require_once "../fusion_config.php";
require_once "../fusion_core.php";
require_once FUSION_BASE."subheader.php";
require_once FUSION_ADMIN."navigation.php";

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_photos.php";

if (!checkrights("G")) { header("Location:../index.php"); exit; }

if (!isset($action)) $action = "";

function builduseroptionlist($selected_user_id=1){
	global $fusion_prefix;
	$user_option_list="";
	$levels = array_reverse(array(250=>USER1,USER2,USER3), true);
	$modlevel = $levels[$modlevel];
	foreach($levels as $level=>$modlevel){
		$uresult=dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_level='$level' ORDER BY user_name");
		if(dbrows($uresult)>0){
			$user_option_list.="<optgroup label='$modlevel'>";
			while($udata=dbarray($uresult)){
				if($udata['user_id']==$selected_user_id) {$sel=" selected";}else{$sel="";}
				$user_option_list.="<option $sel value='".$udata['user_id']."'>".$udata['user_name']."</option>";
			}
			$user_option_list.="</optgroup>";
		}
	}
	return $user_option_list;
}

if(isset($_POST['btn_cancel'])){
	header("Location: ".FUSION_SELF."?album_id=$album_id");
}elseif(($action == "deletephotopic") && isset($photo_id)) {
	if(file_exists(FUSION_PHOTOS.$photo_id.".jpg")) unlink(FUSION_PHOTOS.$photo_id.".jpg");
	if(file_exists(FUSION_PHOTOS.$photo_id."t.jpg")) unlink(FUSION_PHOTOS.$photo_id."t.jpg");
	header("Location: ".FUSION_SELF."?action=editphoto&photo_id=$photo_id");
}elseif($action=="deletephoto"){
	if(isset($photo_id)){
		$data=dbarray(dbquery("SELECT album_id, photo_order FROM ".$fusion_prefix."photos WHERE photo_id='$photo_id'"));
		$album_id=$data['album_id'];
		$photo_order=$data['photo_order'];
		$result=dbquery("UPDATE ".$fusion_prefix."photos SET photo_order=(photo_order-1) WHERE photo_order>'$photo_order'");
		$result=dbquery("DELETE FROM ".$fusion_prefix."photos WHERE photo_id='$photo_id'");
		if(file_exists(FUSION_PHOTOS.$photo_id.".jpg")) unlink(FUSION_PHOTOS.$photo_id.".jpg");
		if(file_exists(FUSION_PHOTOS.$photo_id."t.jpg")) unlink(FUSION_PHOTOS.$photo_id."t.jpg");
		header("Location: ".FUSION_SELF."?album_id=$album_id");
	}
}elseif(isset($_POST['btn_save_photo'])){
	$photo_title=stripinput($photo_title);
	$result=dbquery("SELECT photo_title FROM ".$fusion_prefix."photos WHERE photo_title='$photo_title' AND photo_id<>'$photo_id' AND album_id='$album_id'");
	if(dbrows($result)!=0){
		$error=LAN_422;
	}else{
		$error="";
		if($action=="editphoto"){
			if($photo_order<$photo_order2){
				$result=dbquery("UPDATE ".$fusion_prefix."photos SET photo_order=(photo_order+1) WHERE photo_order>='$photo_order' AND photo_order<'$photo_order2'");
			}elseif($photo_order>$photo_order2){
				$result=dbquery("UPDATE ".$fusion_prefix."photos SET photo_order=(photo_order-1) WHERE photo_order>'$photo_order2' AND photo_order<='$photo_order'");
			}
			$result=dbquery("UPDATE ".$fusion_prefix."photos SET album_id='$album_id',photo_title='$photo_title',photo_date='".time()."', user_id='$photo_added_by',photo_order='$photo_order' WHERE photo_id='$photo_id'");
		}else{
			if($photo_order==""){
				$photo_order=dbresult(dbquery("SELECT MAX(photo_order) FROM ".$fusion_prefix."photos WHERE album_id='$album_id'"),0)+1;
			}else{
				$result=dbquery("UPDATE ".$fusion_prefix."photos SET photo_order=(photo_order+1) WHERE photo_order>='$photo_order' AND album_id='$album_id'");
			}
			$result=dbquery("INSERT INTO ".$fusion_prefix."photos VALUES ('','$album_id','$photo_title','".time()."','$photo_added_by','0','$photo_order')");
		}
		$photo_id=dbresult(dbquery("SELECT photo_id FROM ".$fusion_prefix."photos WHERE photo_title='$photo_title' AND album_id='$album_id'"),0);
		if(is_uploaded_file($_FILES['photo_pic_file']['tmp_name'])){
			if($_FILES['photo_pic_file']['size']>=$settings['album_max_b']){
				$error=sprintf(LAN_423, $settings['album_max_b']);
			}elseif(!$imageFile=@getImageSize($_FILES['photo_pic_file']['tmp_name'])){
				$error=LAN_424;
			}elseif($imageFile[2]<>2){
				$error=LAN_425;
			}elseif($imageFile[0]>$settings['album_max_w']||$imageFile[1]>$settings['album_max_h']){
				$error=sprintf(LAN_426, $settings['album_max_w'], $settings['album_max_h']);
			}else{
				if(file_exists(FUSION_PHOTOS.$photo_id.".jpg")) unlink(FUSION_PHOTOS.$photo_id.".jpg");
				if(file_exists(FUSION_PHOTOS.$photo_id."t.jpg")) unlink(FUSION_PHOTOS.$photo_id."t.jpg");
				move_uploaded_file($_FILES['photo_pic_file']['tmp_name'], FUSION_PHOTOS.$photo_id.".jpg");
				chmod(FUSION_PHOTOS.$photo_id.".jpg",0644);
				createThumbnail(FUSION_PHOTOS.$photo_id.".jpg",FUSION_PHOTOS.$photo_id."t.jpg",$settings['thumb_image_w'],$settings['thumb_image_h']);
			}
		}
	}
	if($error<>""){
		opentable(LAN_420);
		echo"<center><br><span class='required'>".LAN_421."</span><br><span class='small'>$error</span><br><br>\n";
		echo"<a href='".FUSION_SELF."?album_id=$album_id'>".LAN_427."</a><br><br><a href='".FUSION_BASE."index.php'>".LAN_428."</a><br><br></center>\n";
		closetable();
	}else{
		header("Location: ".FUSION_SELF."?album_id=$album_id");
	}
}elseif($action=="mup"){
	$data=dbarray(dbquery(
		"SELECT t1.photo_id id1, t2.photo_id id2, t1.album_id
		FROM ".$fusion_prefix."photos t1 INNER JOIN ".$fusion_prefix."photos t2
		WHERE t1.photo_order = t2.photo_order+1 AND t1.photo_id = '$photo_id' AND t2.album_id='$album_id'"
	));
	$id1=$data['id1'];
	$id2=$data['id2'];
	$result=dbquery("UPDATE ".$fusion_prefix."photos SET photo_order=photo_order-1 WHERE photo_id='$id1'");
	$result=dbquery("UPDATE ".$fusion_prefix."photos SET photo_order=photo_order+1 WHERE photo_id='$id2'");
	header("Location: ".FUSION_SELF."?album_id=$album_id");
}elseif($action=="mdown"){
	$data=dbarray(dbquery(
		"SELECT t1.photo_id id1, t2.photo_id id2, t1.album_id
		FROM ".$fusion_prefix."photos t1 INNER JOIN ".$fusion_prefix."photos t2
		WHERE t1.photo_order = t2.photo_order-1 AND t1.photo_id = '$photo_id' AND t2.album_id='$album_id'"
	));
	$id1=$data['id1'];
	$id2=$data['id2'];
	$result=dbquery("UPDATE ".$fusion_prefix."photos SET photo_order=photo_order+1 WHERE photo_id='$id1'");
	$result=dbquery("UPDATE ".$fusion_prefix."photos SET photo_order=photo_order-1 WHERE photo_id='$id2'");
	header("Location: ".FUSION_SELF."?album_id=$album_id");
}else{
	if($action=="editphoto"){
		$result=dbquery(
			"SELECT tp.*, user_name, COUNT(comment_id) comment_count
			FROM (".$fusion_prefix."photos tp INNER JOIN ".$fusion_prefix."users USING (user_id))
			LEFT JOIN ".$fusion_prefix."comments ON photo_id = comment_item_id AND comment_type = 'P'
			WHERE photo_id='$photo_id' GROUP BY photo_id ORDER BY photo_order"
		);
		$data=dbarray($result);
		$photo_id=$data['photo_id'];
		$album_id=$data['album_id'];
		$photo_title=stripslashes($data['photo_title']);
		$photo_added_by=$data['user_id'];
		$photo_added_by_name=$data['user_name'];
		$photo_order=$data['photo_order'];
		$photo_comments=$data['comment_count'];
		opentable(LAN_401." - ($photo_id - $photo_title)");
	}else{
		$photo_id="";
		$photo_title="";
		$photo_added_by="";
		$photo_order="";
		opentable(LAN_402);
	}
	echo "<form name='frm_add_photo' method='post' action='".FUSION_SELF."' enctype='multipart/form-data'>\n";
	echo "<table align='center' cellspacing='0' cellpadding='0' class='tbl'>\n";
	echo "<input type='hidden' name='album_id' value='$album_id'>\n";
	if($action=="editphoto"){
		echo "<input type='hidden' name='photo_id' value='$photo_id'>\n";
		echo "<input type='hidden' name='action' value='editphoto'>";
		echo "<input type='hidden' name='photo_order2' value='$photo_order'>";
	}
	echo "<tr><td>".LAN_404.":</td><td><input type='textbox' name='photo_title' value=\"$photo_title\" maxlength='100' class='textbox' style='width:250px;'></td></tr>\n";
	echo "<tr><td>".LAN_407.":</td><td><select name='photo_added_by' class='textbox' style='width:250px;'>".builduseroptionlist($photo_added_by)."</select></td></tr>\n";
	echo "<tr><td>".LAN_410.":</td><td><input type='textbox' name='photo_order' value='$photo_order' maxlength='5' class='textbox' style='width:40px;'>	<span class='small dimmed6'>(";
	if($action=="editphoto"){
		echo LAN_416.$photo_order;
	}else{
		echo LAN_417;
	}
	echo ")</span></td></tr>";
	if(file_exists(FUSION_PHOTOS.$photo_id."t.jpg")){
		echo "<tr><td valign='top'>".LAN_406.":</td><td><img src='".FUSION_PHOTOS.$photo_id."t.jpg' border='1' alt='".LAN_418."' width='".$settings['thumb_image_w']."' height='".$settings['thumb_image_h']."'></td></tr>\n";
	}
	echo "<tr><td valign='top'>".LAN_405.":";
	if(file_exists(FUSION_PHOTOS.$photo_id.".jpg")){
		echo "<br><br><a class='small' href='".FUSION_SELF."?action=deletephotopic&photo_id=$photo_id'>".LAN_412."</a></td><td><img src='".FUSION_PHOTOS.$photo_id.".jpg' border='1' alt='".LAN_418."'>";
	}else{
		echo "</td><td><input type='file' name='photo_pic_file' class='textbox' style='width:250px;'><br>
<span class='small alt'>".sprintf(LAN_419, $settings['thumb_image_w'], $settings['thumb_image_h'])."</span>";
	}

	echo "</td></tr>\n<tr><td colspan='2' align='center'><hr><input type='submit' name='btn_save_photo' value='".LAN_414."' class='button' style='width:80px;'>\n";
	if($action=="editphoto"){
		echo "<input type='submit' name='btn_cancel' value='".LAN_415."' class='button' style='width:80px;'>\n";
	}
	echo "</td></tr>\n</table></form>\n";
	closetable();
	tablebreak();
	if($action=="editphoto" && $photo_comments>0){
		opentable("Photo Comments");
		$result = dbquery(
			"SELECT user_name, fc.*
			FROM ".$fusion_prefix."comments AS fc LEFT JOIN ".$fusion_prefix."users
			ON comment_name = user_id
			WHERE comment_item_id='$photo_id' AND comment_type='P'
			ORDER BY comment_datestamp ASC"
		);
		$cnt_comments = dbrows($result);
		if ($cnt_comments != 0) {
			$i = 1;
			while ($data = dbarray($result)) {
				echo "<span class=\"shoutboxname\">";
				if ($data['user_name']) {
					echo "<a href=\"profile.php?lookup=".$data['comment_name']."\">".$data['user_name']."</a>";
				} else {
					echo $data['comment_name'];
				}
				echo "</span><br>\n".parsesmileys(parseubb($data['comment_message']))."<br>\n<span class=\"shoutboxdate\">";
				echo strftime($settings['longdate'], $data['comment_datestamp'])."</span>\n";
				if ($i != $cnt_comments) {
					echo "<br><br>\n";
				} else {
					echo "\n";
				}
				$i++;
			}
		} else {
			echo LAN_601."\n";
		}
		closetable();
		tablebreak();
	}
	if($action == ""){
		opentable(LAN_400);
		$result=dbquery(
			"SELECT tp.*, user_name, COUNT(comment_id) comment_count
			FROM (".$fusion_prefix."photos tp LEFT JOIN ".$fusion_prefix."users USING (user_id))
			LEFT JOIN ".$fusion_prefix."comments ON photo_id = comment_item_id AND comment_type = 'P'
			WHERE album_id='$album_id' GROUP BY photo_id ORDER BY photo_order"
		);
		$rows=dbrows($result);
		if($rows!=0){
			echo "<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>
<tr class='tbl2'>
<td align='center'>".LAN_403."</td>
<td>".LAN_404."</td>
<td align='center'>".LAN_405."</td>
<td align='center'>".LAN_406."</td>
<td>".LAN_407."</td>
<td align='center'>".LAN_408."</td>
<td align='center'>".LAN_409."</td>
<td align='center' colspan='2'>".LAN_410."</td>
<td align='right'>".LAN_411."</td>
</tr>\n";
			$r=0;
			while($data=dbarray($result)){
				$photo_id=$data['photo_id'];
				$photo_title=stripslashes($data['photo_title']);
				$photo_date=implode('/',array_reverse(split('[-]',$data['photo_date'])));
				$photo_added_by=$data['user_id'];
				$photo_added_by_name=$data['user_name'];
				$photo_views=$data['photo_views'];
				$comment_count=$data['comment_count'];
				if($comment_count==0) $comment_count=LAN_430;
				$photo_order=$data['photo_order'];
				if($rows!=1){
					if($photo_order==1){
						$up_down=" <a href='".FUSION_SELF."?action=mdown&album_id=$album_id&photo_id=$photo_id'><img src='".FUSION_THEME."images/down.gif' border='0' /></a>";
					}elseif($photo_order<$rows){
						$up_down=" <a href='".FUSION_SELF."?action=mup&album_id=$album_id&photo_id=$photo_id'><img src='".FUSION_THEME."images/up.gif' border='0' /></a>\n";
						$up_down.=" <a href='".FUSION_SELF."?action=mdown&album_id=$album_id&photo_id=$photo_id'><img src='".FUSION_THEME."images/down.gif' border='0' /></a>";
					}else{
						$up_down=" <a href='".FUSION_SELF."?action=mup&album_id=$album_id&photo_id=$photo_id'><img src='".FUSION_THEME."images/up.gif' border='0' /></a>";
					}
				}else{
					$up_down = "";
				}

				if(($r%2)==0){echo"<tr class='tbl1'>";}else{echo"<tr class='tbl2'>";}
				echo "<td align='center'>$photo_id</td>
<td><a href='".FUSION_SELF."?action=editphoto&photo_id=$photo_id'>$photo_title</a></td>
<td align='center'>";
				if(file_exists(FUSION_PHOTOS.$photo_id.".jpg")){
					echo "<img src='".FUSION_IMAGES."tick.gif'>";
				}else{
					echo " ";
				}
				echo "</td>\n<td align='center'>";
				if(file_exists(FUSION_PHOTOS.$photo_id."t.jpg")){
					echo "<img src='".FUSION_IMAGES."tick.gif'>";
				}else{
					echo " ";
				}
				echo "</td>\n
<td><a href='".FUSION_BASE."profile.php?lookup=$photo_added_by'>$photo_added_by_name</a></td>
<td align='center'>".showdate("shortdate", $photo_date)."</td>
<td align='center'>$comment_count</td>
<td>$photo_order</td>
<td>$up_down</td>
<td align='right'><a href='".FUSION_SELF."?action=deletephoto&photo_id=".$data['photo_id']."'>".LAN_412."</a></td>";
				echo "</tr>";
				$r++;
			}
			echo "</table>\n";
			tablebreak();
			echo "<center><a href='photoalbums.php'>".LAN_431."</a><center>";
			tablebreak();
			closetable();
		}else{
			echo "<center>".LAN_429."</center>\n";
			closetable();
		}
	}
}

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>