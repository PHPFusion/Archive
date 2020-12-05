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
require fusion_langdir."guestbook.php";
require "side_left.php";

if ($action == "delete") {
	if (Moderator()) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."guestbook WHERE guestbook_id='$guestbook_id'");
		header("Location: ".$PHP_SELF);
	}
}
if (isset($_POST['guest_submit'])) {
	if ($guest_name != "" && $guest_email != "" && $guest_message != "") {
		$guest_name = stripinput($guest_name);
		$guest_email = stripinput($guest_email);
		$guest_weburl = stripinput($guest_weburl);
		$guest_webtitle = stripinput($guest_webtitle);
		$guest_message = stripinput($guest_message);
		if ($action == "edit") {
			if (Moderator()) {
				$result = dbquery("UPDATE ".$fusion_prefix."guestbook SET guestbook_name='$guest_name', guestbook_email='$guest_email', guestbook_weburl='$guest_weburl', guestbook_webtitle='$guest_webtitle', guestbook_message='$guest_message' WHERE guestbook_id='$guestbook_id'");
			}
		} else {		
			$result = dbquery("INSERT INTO ".$fusion_prefix."guestbook VALUES('', '$guest_name', '$guest_email', '$guest_weburl', '$guest_webtitle', '$guest_message', '".time()."')");
		}
		header("Location: ".$PHP_SELF);
	}
}
if ($action == "edit") {
	if (Moderator()) {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."guestbook WHERE guestbook_id='$guestbook_id'");
		$data = dbarray($result);
		$formaction = "$PHP_SELF?action=edit&guestbook_id=$guestbook_id";
	}
} else {
	$formaction = "$PHP_SELF";
}
opentable(LAN_400);
echo "<form name='guestbook' method='post' action=$formaction>
<table align='center' cellpadding='0' cellspacing='0' class='body'>
<tr>
<td align='right'>".LAN_401."</td>
<td><input type='text' name='guest_name' value='$data[guestbook_name]' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td align='right'>".LAN_402."</td>
<td><input type='text' name='guest_email' value='$data[guestbook_email]' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td align='right'>".LAN_403."</td>
<td><input type='text' name='guest_weburl' value='$data[guestbook_weburl]' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td align='right'>".LAN_404."</td>
<td><input type='text' name='guest_webtitle' value='$data[guestbook_webtitle]' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td align='right' valign='top'>".LAN_405."</td>
<td><textarea name='guest_message' rows='5' class='textbox' style='width:250px'>$data[guestbook_message]</textarea></td>
</tr>
<tr>
<td></td>
<td><input type='submit' name='guest_submit' value='".LAN_406."' class='button'>
<input type='reset' name='guest_reset' value='".LAN_407."' class='button'></td>
</tr>
</table>
</form>\n";
closetable();
tablebreak();
opentable(LAN_420);
$itemsperpage = 10;
$result = dbquery("SELECT * FROM ".$fusion_prefix."guestbook");
$rows = dbrows($result);
if (!$rowstart) { $rowstart = 0; }
if ($rows != 0) {
	tablebreak();
	$i = 1;
	$result = dbquery("SELECT * FROM ".$fusion_prefix."guestbook ORDER BY guestbook_datestamp DESC LIMIT $rowstart,$itemsperpage");
	$numrows = dbrows($result);
	while ($data = dbarray($result)) {
		if ($data[guestbook_weburl]) {
			$weburl = str_replace("http://", "", $data[guestbook_weburl]);
			$web = "\n | <a href='http://$weburl' target='_blank'>";
			if ($data[guestbook_webtitle]) { 
				$web .= $data[guestbook_webtitle]."</a>\n";
			} else {
				$web .= $weburl."</a>\n";
			}
		} else {
			$web = "";
		}
		echo "<table align='center' border='0' cellpadding='0' cellspacing='0' width='80%' class='forum-border'>
<tr>
<td>
<table align='center' border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td class='forum2'><span class='small'><b><a href='mailto:$data[guestbook_email]'>$data[guestbook_name]</a></b>$web</span></td>
</tr>
<tr>
<td class='forum1'>".parsesmileys(parseubb($data[guestbook_message]))."</td>
</tr>
<tr>
<td class='forum2'>
<table cellpadding='0' cellspacing='0' width='100%' class='small'>
<tr>
<td class='small'>
".strftime($settings[longdate], $data[guestbook_datestamp]+($settings[timeoffset]*3600))."</td>\n";
		if (Moderator()) {
			echo "<td align='right' class='small'><a href='$PHP_SELF?action=edit&guestbook_id=$data[guestbook_id]'>".LAN_421."</a> |
<a href='$PHP_SELF?action=delete&guestbook_id=$data[guestbook_id]' onClick='return DeleteMessage();'>".LAN_422."</a></td>\n";
		}
		echo "</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>\n";
		if ($i != $numrows) echo "<br />\n";
		$i++;
	}
	tablebreak();
} else {
	echo "<center><br />
".LAN_423."
<br /><br /></center>\n";
}
closetable();

echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"small","$PHP_SELF?")."
</div>\n";

echo "<script>
function DeleteMessage() {
	return confirm(\"".LAN_424."\");
}
</script>\n";

require "side_right.php";
require "footer.php";
?>