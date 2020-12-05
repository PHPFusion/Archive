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
@require_once "../fusion_config.php";
require_once "../fusion_core.php";
require_once FUSION_BASE."subheader.php";
require_once FUSION_ADMIN."navigation.php";

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_news-articles.php";

if (!checkrights("E")) { header("Location:../index.php"); exit; }

if (isset($_POST['save'])) {
	$subject = stripinput($_POST['subject']);
	$body = addslash($_POST['body']);
	if ($_POST['body2']) $body2 = addslash($_POST['body2']);
	$start_date = 0; $end_date = 0;
	if ($_POST['news_start']['mday']!="" && $_POST['news_start']['mon']!="" && $_POST['news_start']['year']!="") {
		$start_date = mktime($_POST['news_start']['hours'],$_POST['news_start']['minutes'],0,$_POST['news_start']['mon'],$_POST['news_start']['mday'],$_POST['news_start']['year']);
	}
	if ($_POST['news_end']['mday']!="" && $_POST['news_end']['mon']!="" && $_POST['news_end']['year']!="") {
		$end_date = mktime($_POST['news_end']['hours'],$_POST['news_end']['minutes'],0,$_POST['news_end']['mon'],$_POST['news_end']['mday'],$_POST['news_end']['year']);
	}
	if (isset($_POST['line_breaks'])) { $breaks = "y"; } else { $breaks = "n"; }
	$news_visibility = $_POST['news_visibility'];
	if (isset($news_id)) {
		$result = dbquery("UPDATE ".$fusion_prefix."news SET news_subject='$subject', news_news='$body', news_extended='$body2', news_breaks='$breaks',".($start_date != 0 ? " news_datestamp='$start_date'," : "")." news_start='$start_date', news_end='$end_date', news_visibility='$news_visibility' WHERE news_id='$news_id'");
		opentable(LAN_400);
		echo "<center><br>
".LAN_401."<br><br>
<a href='news.php'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
		closetable();
	} else {
		$result = dbquery("INSERT INTO ".$fusion_prefix."news VALUES('', '$subject', '$body', '$body2', '$breaks', '".$userdata['user_id']."', '".($start_date != 0 ? $start_date : time())."', '$start_date', '$end_date', '$news_visibility', '0')");
		opentable(LAN_404);
		echo "<center><br>
".LAN_405."<br><br>
<a href='news.php'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
		closetable();
	}
} else if (isset($_POST['delete'])) {
	$result = dbquery("DELETE FROM ".$fusion_prefix."news WHERE news_id='$news_id'");
	$result = dbquery("DELETE FROM ".$fusion_prefix."comments WHERE comment_item_id='$news_id' and comment_type='N'");
	$result = dbquery("DELETE FROM ".$fusion_prefix."ratings WHERE rating_item_id='$news_id' and rating_type='N'");
	opentable(LAN_406);
	echo "<center><br>
".LAN_407."<br><br>
<a href='news.php'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	closetable();
} else {
	if (isset($_POST['preview'])) {
		$subject = stripinput($_POST['subject']);
		$body = phpentities(stripslash($_POST['body']));
		$bodypreview = str_replace("src='".str_replace("../", "", FUSION_IMAGES_N), "src='".FUSION_IMAGES_N, stripslash($_POST['body']));
		if ($_POST['body2']) {
			$body2 = phpentities(stripslash($_POST['body2']));
			$body2preview = str_replace("src='".str_replace("../", "", FUSION_IMAGES_N), "src='".FUSION_IMAGES_N, stripslash($_POST['body2']));
		}
		if (isset($_POST['line_breaks'])) {
			$breaks = " checked";
			$bodypreview = nl2br($bodypreview);
			if ($body2) $body2preview = nl2br($body2preview);
		} else {
			$breaks = "";
		}
		opentable($subject);
		echo "$bodypreview\n";
		closetable();
		if (isset($body2preview)) {
			tablebreak();
			opentable($subject);
			echo "$body2preview\n";
			closetable();
		}
		tablebreak();
	}
	$editlist = ""; $sel = "";
	$result = dbquery("SELECT * FROM ".$fusion_prefix."news ORDER BY news_datestamp DESC");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			if (isset($news_id)) $sel = ($news_id == $data['news_id'] ? " selected" : "");
			$editlist .= "<option value='".$data['news_id']."'$sel>".$data['news_subject']."</option>\n";
		}
	}
	opentable(LAN_408);
	echo "<form name='selectform' method='post' action='".FUSION_SELF."'>
<center>
<select name='news_id' class='textbox' style='width:250px;'>
$editlist</select>
<input type='submit' name='edit' value='".LAN_409."' class='button'>
<input type='submit' name='delete' value='".LAN_410."' onclick='return DeleteNews();' class='button'>
</center>
</form>\n";
	closetable();
	tablebreak();
	if (isset($_POST['edit'])) {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."news WHERE news_id='$news_id'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$subject = $data['news_subject'];
			$body = phpentities(stripslashes($data['news_news']));
			$body2 = phpentities(stripslashes($data['news_extended']));
			if ($data['news_start']>0) $news_start = getdate($data['news_start']);
			if ($data['news_end']>0) $news_end = getdate($data['news_end']);
			if ($data['news_breaks'] == "y") { $breaks = " checked"; }
			$news_visibility = $data['news_visibility'];
		}
	}
	if (isset($news_id)) {
		$action = FUSION_SELF."?news_id=$news_id";
		opentable(LAN_400);
	} else {
		if (!isset($_POST['preview'])) {
			$subject = "";
			$body = "";
			$body2 = "";
			$breaks = " checked";
			$news_visibility = 0;
		}
		$action = FUSION_SELF;
		opentable(LAN_404);
	}
	$handle = opendir(FUSION_IMAGES_N);
	while ($file = readdir($handle)) {
		if (!in_array($file, array(".", "..", "/", "index.php", "smiley", "photoalbum"))) {
			$image_files[] = $file;
		}
	}
	closedir($handle); $image_list = "";
	if (isset($image_files)) {
		sort($image_files); $image_count = count($image_files);
		for ($i=0;$i < $image_count;$i++) {
			$image_list .= "<option value='".$image_files[$i]."'>".$image_files[$i]."</option>\n";
		}
	}
	$visibility_opts = ""; $sel = "";
	$user_groups = getusergroups();
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($news_visibility == $user_group['0'] ? " selected" : "");
		$visibility_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<form name='inputform' method='post' action='$action' onSubmit='return ValidateForm(this)'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td width='100'>".LAN_411."</td>
<td width='80%'><input type='text' name='subject' value='$subject' class='textbox' style='width: 250px;'></td>
</tr>
<tr>
<td valign='top' width='100'>".LAN_412."</td>
<td width='80%'><textarea name='body' cols='95' rows='5' class='textbox'>$body</textarea></td>
</tr>
<tr>
<td></td><td>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('body', '<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('body', '<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('body', '<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px;' onClick=\"addText('body', '<a href=\'\' target=\'_blank\'>', '</a>');\">
<input type='button' value='img' class='button' style='width:35px;' onClick=\"insertText('body', '<img src=\'".str_replace("../","",FUSION_IMAGES_N)."\' style=\'margin:5px;\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('body', '<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('body', '<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px;' onClick=\"addText('body', '<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px;' onClick=\"addText('body', '<span class=\'alt\'>', '</span>');\"><br>
<select name='setcolor' class='textbox' style='margin-top:5px;' onChange=\"addText('body', '<span style=\'color:' + this.options[this.selectedIndex].value + ';\'>', '</span>');this.selectedIndex=0;\">
<option value=''>".LAN_420."</option>
<option value='maroon' style='color:maroon;'>Maroon</option>
<option value='red' style='color:red;'>Red</option>
<option value='orange' style='color:orange;'>Orange</option>
<option value='brown' style='color:brown;'>Brown</option>
<option value='yellow' style='color:yellow;'>Yellow</option>
<option value='green' style='color:green;'>Green</option>
<option value='lime' style='color:lime;'>Lime</option>
<option value='olive' style='color:olive;'>Olive</option>
<option value='cyan' style='color:cyan;'>Cyan</option>
<option value='blue' style='color:blue;'>Blue</option>
<option value='navy' style='color:navy;'>Navy Blue</option>
<option value='purple' style='color:purple;'>Purple</option>
<option value='violet' style='color:violet;'>Violet</option>
<option value='black' style='color:black;'>Black</option>
<option value='gray' style='color:gray;'>Gray</option>
<option value='silver' style='color:silver;'>Silver</option>
<option value='white' style='color:white;'>White</option>
</select>
<select name='insertimage' class='textbox' style='margin-top:5px;' onChange=\"insertText('body', '<img src=\'".str_replace("../","",FUSION_IMAGES_N)."' + this.options[this.selectedIndex].value + '\' style=\'margin:5px;\' align=\'left\'>');this.selectedIndex=0;\">
<option value=''>".LAN_421."</option>
$image_list</select>
</td>
</tr>
<tr>
<td valign='top' width='100'>".LAN_413."</td><td><textarea name='body2' cols='95' rows='10' class='textbox'>$body2</textarea></td>
</tr>
<tr>
<td></td><td>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('body2', '<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('body2', '<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('body2', '<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px;' onClick=\"addText('body2', '<a href=\'\' target=\'_blank\'>', '</a>');\">
<input type='button' value='img' class='button' style='width:35px;' onClick=\"insertText('body2', '<img src=\'".str_replace("../","",FUSION_IMAGES_N)."\' style=\'margin:5px;\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('body2', '<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('body2', '<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px;' onClick=\"addText('body2', '<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px;' onClick=\"addText('body2', '<span class=\'alt\'>', '</span>');\"><br>
<select name='setcolor' class='textbox' style='margin-top:5px;' onChange=\"addText('body2', '<span style=\'color:' + this.options[this.selectedIndex].value + ';\'>', '</span>');this.selectedIndex=0;\">
<option value=''>".LAN_420."</option>
<option value='maroon' style='color:maroon;'>Maroon</option>
<option value='red' style='color:red;'>Red</option>
<option value='orange' style='color:orange;'>Orange</option>
<option value='brown' style='color:brown;'>Brown</option>
<option value='yellow' style='color:yellow;'>Yellow</option>
<option value='green' style='color:green;'>Green</option>
<option value='lime' style='color:lime;'>Lime</option>
<option value='olive' style='color:olive;'>Olive</option>
<option value='cyan' style='color:cyan;'>Cyan</option>
<option value='blue' style='color:blue;'>Blue</option>
<option value='navy' style='color:navy;'>Navy Blue</option>
<option value='purple' style='color:purple;'>Purple</option>
<option value='violet' style='color:violet;'>Violet</option>
<option value='black' style='color:black;'>Black</option>
<option value='gray' style='color:gray;'>Gray</option>
<option value='silver' style='color:silver;'>Silver</option>
<option value='white' style='color:white;'>White</option>
</select>
<select name='insertimage' class='textbox' style='margin-top:5px;' onChange=\"insertText('body2', '<img src=\'".str_replace("../","",FUSION_IMAGES_N)."' + this.options[this.selectedIndex].value + '\' style=\'margin:5px;\' align=\'left\'>');this.selectedIndex=0;\">
<option value=''>".LAN_421."</option>
$image_list</select>
</td>
</tr>
<tr>
<td>".LAN_414."</td>
<td><select name='news_start[mday]' class='textbox'>\n<option> </option>\n";
	for ($i=1;$i<=31;$i++) echo "<option".($news_start['mday'] == $i ? " selected" : "").">$i</option>\n";
	echo "</select>
<select name='news_start[mon]' class='textbox'>\n<option> </option>\n";
	for ($i=1;$i<=12;$i++) echo "<option".($news_start['mon'] == $i ? " selected" : "").">$i</option>\n";
	echo "</select>
<select name='news_start[year]' class='textbox'>\n<option> </option>\n";
	for ($i=2004;$i<=2010;$i++) echo "<option".($news_start['year'] == $i ? " selected" : "").">$i</option>\n";
	echo "</select> /
<select name='news_start[hours]' class='textbox'>\n<option> </option>\n";
	for ($i=0;$i<=24;$i++) echo "<option".($news_start['hours'] == $i ? " selected" : "").">$i</option>\n";
	echo "</select> :
<select name='news_start[minutes]' class='textbox'>\n<option> </option>\n";
	for ($i=0;$i<=60;$i++) echo "<option".($news_start['minutes'] == $i ? " selected" : "").">$i</option>\n";
	echo "</select> : 00 <span class='alt'>".LAN_416."</span></td>
</tr>
<tr>
<td>".LAN_415."</td>
<td><select name='news_end[mday]' class='textbox'>\n<option> </option>\n";
	for ($i=1;$i<=31;$i++) echo "<option".($news_end['mday'] == $i ? " selected" : "").">$i</option>\n";
	echo "</select>
<select name='news_end[mon]' class='textbox'>\n<option> </option>\n";
	for ($i=1;$i<=12;$i++) echo "<option".($news_end['mon'] == $i ? " selected" : "").">$i</option>\n";
	echo "</select>
<select name='news_end[year]' class='textbox'>\n<option> </option>\n";
	for ($i=2004;$i<=2010;$i++) echo "<option".($news_end['year'] == $i ? " selected" : "").">$i</option>\n";
	echo "</select> /
<select name='news_end[hours]' class='textbox'>\n<option> </option>\n";
	for ($i=0;$i<=24;$i++) echo "<option".($news_end['hours'] == $i ? " selected" : "").">$i</option>\n";
	echo "</select> :
<select name='news_end[minutes]' class='textbox'>\n<option> </option>\n";
	for ($i=0;$i<=60;$i++) echo "<option".($news_end['minutes'] == $i ? " selected" : "").">$i</option>\n";
	echo "</select> : 00 <span class='alt'>".LAN_416."</span></td>
</tr>
<tr>
<td>".LAN_422."</td>
<td><select name='news_visibility' class='textbox'>
$visibility_opts</select></td>
</tr>
<tr>
<td align='center' colspan='2'><br>
<input type='checkbox' name='line_breaks' value='yes'$breaks>".LAN_417."<br><br>
<input type='submit' name='preview' value='".LAN_418."' class='button'>
<input type='submit' name='save' value='".LAN_419."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
	echo "<script language='JavaScript'>
function DeleteNews() {
	return confirm('".LAN_551."');
}
function ValidateForm(frm) {
	if(frm.subject.value=='') {
		alert('".LAN_550."');
		return false;
	}
}
</script>\n";
}

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>