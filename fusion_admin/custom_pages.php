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
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_main.php";
require fusion_langdir."admin/admin_custom_pages.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (SuperAdmin()) {
	if (isset($_POST['save'])) {
		$title = stripinput($title);
		$body = stripslashes($body);
		if (isset($page_id)) {
			$result = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_title='$title', page_access='$page_access' WHERE page_id='$page_id'");
			$file = fopen(fusion_basedir."fusion_pages/".$page_id.".php", "wb");
			$write = fwrite($file, $body);
			fclose($file);
			opentable(LAN_400);
			echo "<center><br>
".LAN_401."<br><br>
".LAN_402."<br>
<a href='../fusion_pages/index.php?page_id=$page_id'>fusion_pages/index.php?page_id=$page_id</a><br><br>
<a href='custom_pages.php'>".LAN_403."</a><br><br>
<a href='index.php'>".LAN_404."</a><br><br>
</center>\n";
			closetable();
		} else {
			$result = dbquery("INSERT INTO ".$fusion_prefix."custom_pages VALUES('', '$title', '$page_access')");
			$file_id = mysql_insert_id();
			$file = fopen(fusion_basedir."fusion_pages/".$file_id.".php", "wb");
			$write = fwrite($file, $body);
			fclose($file);
			chmod(fusion_basedir."fusion_pages/".$file_id.".php",0644);
			$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links ORDER BY link_order DESC LIMIT 1");
			$data = dbarray($result);
			$link_order = $data[link_order] + 1;
			$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '$title', 'fusion_pages/index.php?page_id=$file_id', '0', '$link_order')");
			opentable(LAN_404);
			echo "<center><br>
".LAN_406."<br><br>
".LAN_402."<br>
<a href='../fusion_pages/index.php?page_id=$file_id'>fusion_pages/index.php?page_id=$file_id</a><br><br>
<a href='custom_pages.php'>".LAN_403."</a><br><br>
<a href='index.php'>".LAN_404."</a><br><br>
</center>\n";
			closetable();
		}
	} else if (isset($_POST['delete'])) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."custom_pages WHERE page_id='$page_id'");
		$result = dbquery("DELETE FROM ".$fusion_prefix."site_links WHERE link_url='fusion_pages/index.php?page_id=$page_id'");
		unlink(fusion_basedir."fusion_pages/".$page_id.".php");
		opentable(LAN_406);
		echo "<center><br>
".LAN_408."<br><br>
<a href='custom_pages.php'>".LAN_403."</a><br><br>
<a href='index.php'>".LAN_404."</a><br><br>
</center>\n";
		closetable();
	} else {
		if (isset($_POST['preview'])) {
			$title = stripinput($title);
			$body = stripslashes($body);
			$file = fopen(fusion_basedir."fusion_pages/temp","wb");
			$write = fwrite($file, $body);
			fclose($file);
			opentable($title);
			require fusion_basedir."fusion_pages/temp";
			unlink(fusion_basedir."fusion_pages/temp");
			closetable();
			tablebreak();
		}
		$result = dbquery("SELECT * FROM ".$fusion_prefix."custom_pages ORDER BY page_title DESC");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				if (isset($page_id)) {
					if ($page_id == $data['page_id']) { $sel = " selected"; } else { $sel = ""; }
				}
				$editlist .= "<option value='".$data['page_id']."'$sel>".$data['page_title']."</option>\n";
			}
		}
		opentable(LAN_420);
		echo "<form name='selectform' method='post' action='$PHP_SELF'>
<center>
<select name='page_id' class='textbox' style='width:200px;'>
$editlist</select>
<input type='submit' name='edit' value='".LAN_421."' class='button'>
<input type='submit' name='delete' value='".LAN_422."' class='button'>
</center>
</form>\n";
		closetable();
		tablebreak();
		if (isset($_POST['edit'])) {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."custom_pages WHERE page_id='$page_id'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
				$title = $data['page_title'];
				$file = fopen(fusion_basedir."fusion_pages/".$data['page_id'].".php","rb");
				$body = fread($file, filesize(fusion_basedir."fusion_pages/".$data['page_id'].".php"));
				fclose($file);
			}
		}
		$access = array(USER0,USER1,USER2,USER3,USER4);
		for ($i=0;$access[$i]!="";$i++) {
			if (isset($page_id)) {
				if ($data[page_access] == $i) { $sel = " selected"; } else { $sel = ""; }
			}
			$access_opts .= "<option value='$i'$sel>".$access[$i]."</option>\n";
		}
		if (isset($page_id)) {
			$action = $PHP_SELF."?page_id=$page_id";
			opentable(LAN_400);
		} else {
			$action = $PHP_SELF;
			opentable(LAN_405);
		}
		echo "<table align='center'>
<form name='editform' method='post' action='$action'>
<tr>
<td width='100'>".LAN_430."</td><td width='80%'><input type='textbox' name='title' value='$title' class='textbox' style='width: 250px;'>
&nbsp;".LAN_431."<select name='page_access' class='textbox' style='width:150px;'>
$access_opts</select></td>
</tr>
<tr>
<td valign='top' width='100'>".LAN_432."</td><td width='80%'><textarea name='body' cols='95' rows='15' class='textbox' onselect='updatePos(this);' onkeyup='updatePos(this);' onclick='updatePos(this);' ondblclick='updatePos(this);'>$body</textarea></td>
</tr>
<tr>
<td></td><td>
<input type='button' value='<?php?>' class='button' style='width:60px;' onClick=\"AddText('<?php\\n', '\\n?>');\">
<input type='button' value='<p>' class='button' style='width:35px;' onClick=\"insertText('<p>');\">
<input type='button' value='<br>' class='button' style='width:40px;' onClick=\"insertText('<br>');\">
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"AddText('<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"AddText('<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"AddText('<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px;' onClick=\"AddText('<a href=\'\' target=\'_blank\'>', '</a>');\">
<input type='button' value='img' class='button' style='width:35px;' onClick=\"insertText('<img src=\'../fusion_images/\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"AddText('<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"AddText('<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px;' onClick=\"AddText('<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px;' onClick=\"AddText('<span class=\'alt\'>', '</span>');\"><br>
</td>
</tr>
<tr>
<td align='center' colspan='2'><br>
<input type='submit' name='preview' value='".LAN_433."' class='button'>
<input type='submit' name='save' value='".LAN_434."' class='button'></td>
</tr>
</form>
</table>\n";
		closetable();
		echo "<script language='JavaScript'>
var editBody = document.editform.body;
var editBody2 = document.editform.body2;
function insertText(theText) {
	if (editBody.createTextRange && editBody.curPos) {
		editBody.curPos.text = theText;
	} else {
		editBody.value += theText;
	}
	editBody.focus();
}
function insertText2(theText) {
	if (editBody2.createTextRange && editBody2.curPos) {
		editBody2.curPos.text = theText;
	} else {
		editBody2.value += theText;
	}
	editBody2.focus();
}
function AddText(wrap,unwrap) {
	if (editBody.curPos) {
		insertText(wrap + editBody.curPos.text + unwrap);
	} else {
		insertText(wrap + unwrap);
	}
}
function AddText2(wrap,unwrap) {
	if (editBody2.curPos) {
		insertText2(wrap + editBody2.curPos.text + unwrap);
	} else {
		insertText2(wrap + unwrap);
	}
}
function updatePos(obj) {
	if (obj.createTextRange) {
		obj.curPos = document.selection.createRange().duplicate();
	}
}
</script>\n";
	}
}

echo "</td>\n";
require "../footer.php";
?>