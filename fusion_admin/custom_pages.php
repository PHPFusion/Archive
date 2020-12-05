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
@include "../fusion_config.php";
include "../fusion_core.php";
include FUSION_BASE."subheader.php";
include FUSION_LANGUAGES.FUSION_LAN."admin/admin_main.php";
include FUSION_LANGUAGES.FUSION_LAN."admin/admin_custom_pages.php";
include FUSION_ADMIN."navigation.php";

if (!iADMIN) { header("Location:../index.php"); exit; }

if (iSUPERADMIN) {
	if (isset($_POST['save'])) {
		$title = stripinput($title);
		$body = stripslashes($body);
		if (isset($page_id)) {
			$result = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_title='$title', page_access='$page_access' WHERE page_id='$page_id'");
			$file = fopen(FUSION_BASE."fusion_pages/".$page_id.".php", "wb");
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
			$file = fopen(FUSION_BASE."fusion_pages/".$file_id.".php", "wb");
			$write = fwrite($file, $body);
			fclose($file);
			chmod(FUSION_BASE."fusion_pages/".$file_id.".php",0644);
			if ($_POST['add_link']) {
				$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links ORDER BY link_order DESC LIMIT 1");
				$data = dbarray($result);
				$link_order = $data[link_order] + 1;
				$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '$title', 'fusion_pages/index.php?page_id=$file_id', '0', '1', '$link_order')");
			}
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
		unlink(FUSION_BASE."fusion_pages/".$page_id.".php");
		opentable(LAN_406);
		echo "<center><br>
".LAN_408."<br><br>
<a href='custom_pages.php'>".LAN_403."</a><br><br>
<a href='index.php'>".LAN_404."</a><br><br>
</center>\n";
		closetable();
	} else {
		if (isset($_POST['preview'])) {
			if (!$page_id && $_POST['add_link']) $addlink = " checked";
			$title = stripinput($title);
			$page_access = $_POST['page_access'];
			$body = stripslashes($body);
			$file = fopen(FUSION_BASE."fusion_pages/temp","wb");
			$write = fwrite($file, $body);
			fclose($file);
			opentable($title);
			include FUSION_BASE."fusion_pages/temp";
			unlink(FUSION_BASE."fusion_pages/temp");
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
				$page_access = $data['page_access'];
				$file = fopen(FUSION_BASE."fusion_pages/".$data['page_id'].".php","rb");
				$body = fread($file, filesize(FUSION_BASE."fusion_pages/".$data['page_id'].".php"));
				fclose($file);
			}
		}
		$access = array(USER0,USER1,USER2,USER3,USER4);
		for ($i=0;$access[$i]!="";$i++) {
			if (isset($page_id)) {
				if ($page_access == $i) { $sel = " selected"; } else { $sel = ""; }
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
<form name='inputform' method='post' action='$action'>
<tr>
<td width='100'>".LAN_430."</td><td width='80%'><input type='textbox' name='title' value='$title' class='textbox' style='width: 250px;'>
&nbsp;".LAN_431."<select name='page_access' class='textbox' style='width:150px;'>
$access_opts</select></td>
</tr>
<tr>
<td valign='top' width='100'>".LAN_432."</td><td width='80%'><textarea name='body' cols='95' rows='15' class='textbox'>$body</textarea></td>
</tr>
<tr>
<td></td><td>
<input type='button' value='<?php?>' class='button' style='width:60px;' onClick=\"addText('body', '<?php\\n', '\\n?>');\">
<input type='button' value='<p>' class='button' style='width:35px;' onClick=\"insertText('body', '<p>');\">
<input type='button' value='<br>' class='button' style='width:40px;' onClick=\"insertText('body', '<br>');\">
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('body', '<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('body', '<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('body', '<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px;' onClick=\"addText('body', '<a href=\'\' target=\'_blank\'>', '</a>');\">
<input type='button' value='img' class='button' style='width:35px;' onClick=\"insertText('body', '<img src=\'fusion_images/\' style=\'margin:5px;\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('body', '<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('body', '<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px;' onClick=\"addText('body', '<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px;' onClick=\"addText('body', '<span class=\'alt\'>', '</span>');\">
</td>
</tr>
<tr>
<td align='center' colspan='2'><br>\n";
		if (!$page_id) echo "<input type='checkbox' name='add_link' value='1'$addlink>".LAN_433."<br><br>\n";
		echo "<input type='submit' name='preview' value='".LAN_434."' class='button'>
<input type='submit' name='save' value='".LAN_435."' class='button'></td>
</tr>
</form>
</table>\n";
		closetable();
	}
}

echo "</td>\n";
include "../footer.php";
?>