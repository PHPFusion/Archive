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

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_custom_pages.php";

if (!checkrights("5")) { header("Location:../index.php"); exit; }

if (isset($_POST['save'])) {
	$page_title = stripinput($_POST['page_title']);
	$page_access = $_POST['page_access'];
	$page_content = addslash($_POST['page_content']);
	if (isset($page_id)) {
		$result = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_title='$page_title', page_access='$page_access', page_content='$page_content' WHERE page_id='$page_id'");
		opentable(LAN_400);
		echo "<center><br>
".LAN_401."<br><br>
".LAN_402."<br>
<a href='".FUSION_BASE."viewpage.php?page_id=$page_id'>viewpage.php?page_id=$page_id</a><br><br>
<a href='custom_pages.php'>".LAN_403."</a><br><br>
<a href='index.php'>".LAN_404."</a><br><br>
</center>\n";
		closetable();
	} else {
		$result = dbquery("INSERT INTO ".$fusion_prefix."custom_pages VALUES('', '$page_title', '$page_access', '$page_content')");
		$page_id = mysql_insert_id();
		if (isset($_POST['add_link'])) {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links ORDER BY link_order DESC LIMIT 1");
			$data = dbarray($result);
			$link_order = $data['link_order'] + 1;
			$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '$page_title', 'viewpage.php?page_id=$page_id', '$page_access', '1', '0', '$link_order')");
		}
		opentable(LAN_404);
		echo "<center><br>
".LAN_406."<br><br>
".LAN_402."<br>
<a href='".FUSION_BASE."viewpage.php?page_id=$page_id'>viewpage.php?page_id=$page_id</a><br><br>
<a href='custom_pages.php'>".LAN_403."</a><br><br>
<a href='index.php'>".LAN_404."</a><br><br>
</center>\n";
		closetable();
	}
} else if (isset($_POST['delete'])) {
	$result = dbquery("DELETE FROM ".$fusion_prefix."custom_pages WHERE page_id='$page_id'");
	$result = dbquery("DELETE FROM ".$fusion_prefix."site_links WHERE link_url='fusion_pages/index.php?page_id=$page_id'");
	opentable(LAN_406);
	echo "<center><br>
".LAN_408."<br><br>
<a href='custom_pages.php'>".LAN_403."</a><br><br>
<a href='index.php'>".LAN_404."</a><br><br>
</center>\n";
	closetable();
} else {
	if (isset($_POST['preview'])) {
		$addlink = isset($_POST['add_link']) ? " checked" : "";
		$page_title = stripinput($_POST['page_title']);
		$page_access = $_POST['page_access'];
		$page_content = $_POST['page_content'];
		$page_content = stripslash($page_content);
		opentable($page_title);
		eval("?>".$page_content."<?php ");
		closetable();
		tablebreak();
		$page_content = stripinput((QUOTES_GPC ? addslashes($page_content) : $page_content));
	}
	$editlist = ""; $sel = "";
	$result = dbquery("SELECT * FROM ".$fusion_prefix."custom_pages ORDER BY page_title DESC");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			if (isset($page_id)) $sel = ($page_id == $data['page_id'] ? " selected" : "");
			$editlist .= "<option value='".$data['page_id']."'$sel>".$data['page_title']."</option>\n";
		}
	}
	opentable(LAN_420);
	echo "<form name='selectform' method='post' action='".FUSION_SELF."'>
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
			$page_title = $data['page_title'];
			$page_access = $data['page_access'];
			$page_content = stripinput((QUOTES_GPC ? $data['page_content'] : stripslashes($data['page_content'])));
			$addlink = "";
		}
	}
	if (isset($page_id)) {
		$action = FUSION_SELF."?page_id=$page_id";
		opentable(LAN_400);
	} else {
		if (!isset($_POST['preview'])) {
			$page_title = "";
			$page_access = "";
			$page_content = "";
			$addlink = "";
		}
		$action = FUSION_SELF;
		opentable(LAN_405);
	}
	$user_groups = getusergroups(); $access_opts = ""; $sel = "";
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($page_access == $user_group['0'] ? " selected" : "");
		$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<table align='center'>
<form name='inputform' method='post' action='$action'>
<tr>
<td width='100'>".LAN_430."</td>
<td width='80%'><input type='textbox' name='page_title' value='$page_title' class='textbox' style='width: 250px;'>
&nbsp;".LAN_431."<select name='page_access' class='textbox' style='width:150px;'>
$access_opts</select></td>
</tr>
<tr>
<td valign='top' width='100'>".LAN_432."</td>
<td width='80%'><textarea name='page_content' cols='95' rows='15' class='textbox'>$page_content</textarea></td>
</tr>
<tr>
<td></td><td>
<input type='button' value='<?php?>' class='button' style='width:60px;' onClick=\"addText('page_content', '<?php\\n', '\\n?>');\">
<input type='button' value='<p>' class='button' style='width:35px;' onClick=\"insertText('page_content', '<p>');\">
<input type='button' value='<br>' class='button' style='width:40px;' onClick=\"insertText('page_content', '<br>');\">
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('page_content', '<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('page_content', '<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('page_content', '<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px;' onClick=\"addText('page_content', '<a href=\'\' target=\'_blank\'>', '</a>');\">
<input type='button' value='img' class='button' style='width:35px;' onClick=\"insertText('page_content', '<img src=\'fusion_images/\' style=\'margin:5px;\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('page_content', '<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('page_content', '<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px;' onClick=\"addText('page_content', '<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px;' onClick=\"addText('page_content', '<span class=\'alt\'>', '</span>');\">
</td>
</tr>
<tr>
<td align='center' colspan='2'><br>\n";
	if (!isset($page_id)) echo "<input type='checkbox' name='add_link' value='1'$addlink>".LAN_433."<br><br>\n";
	echo "<input type='submit' name='preview' value='".LAN_434."' class='button'>
<input type='submit' name='save' value='".LAN_435."' class='button'></td>
</tr>
</form>
</table>\n";
	closetable();
}

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>