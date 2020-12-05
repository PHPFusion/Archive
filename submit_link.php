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
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."submit_link.php";
require "side_left.php";

if (isset($_POST['submit_link'])) {
	$link_sitename = stripinput($_POST['link_sitename']);
	$link_url = stripinput($_POST['link_url']);
	$link_name = stripinput($_POST['link_name']);
	$link_email = stripinput($_POST['link_email']);
	if ($link_sitename != "" && $link_url != "" && $link_name != "" && $link_email != "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."submitted_links VALUES ('', '$link_sitename', '$link_description', '$link_url', '$link_category', '$link_name', '$link_email', '".time()."', '$user_ip')");
		opentable(LAN_400);
		echo "<center><br>
".LAN_410."<br>
".LAN_411."<br><br>
<a href='submit_link.php'>".LAN_412."</a><br><br>
<a href='index.php'>".LAN_413."</a><br><br>
</center>\n";
		closetable();
	} else {
		opentable(LAN_400);
		echo "<center><br>
".LAN_420."<br>
".LAN_421."<br><br>
<a href='submit_link.php'>".LAN_422."</a><br><br>
</center>\n";
		closetable();
	}
} else {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
	while ($data = dbarray($result)) {
		$opts .= "<option>".$data['weblink_cat_name']."</option>\n";
	}
	$opts .= "<option>Other</option>\n";
	opentable(LAN_400);
	echo LAN_430."<br><br>
<form name='userform' method='post' action='$PHP_SELF'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_431."</td>
<td><input type='text' name='link_sitename' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_432."</td>
<td><input type='text' name='link_description' maxlength='200' class='textbox' style='width:300px;'></td>
</tr>
<tr>
<td>".LAN_433."</td>
<td><input type='text' name='link_url' value='http://' maxlength='200' class='textbox' style='width:300px;'></td>
</tr>
<tr>
<td>".LAN_434."</td>
<td><select name='link_category' class='textbox' style='width:200px;'>
$opts</select></td>
</tr>
<tr>
<td>".LAN_435."</td>
<td><input type='text' name='link_name' value='".$userdata['user_name']."' maxlength='30' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_436."</td>
<td><input type='text' name='link_email' value='".$userdata['user_email']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td align='center' colspan='2'><br>
<input type='submit' name='submit_link' value='".LAN_437."' class='button'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}

require "side_right.php";
require "footer.php";
?>