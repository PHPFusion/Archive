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
require fusion_langdir."admin/admin_shoutbox.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (Admin()) {
	if ($action == "deleteshouts") {
		$deletetime = time() - ($_POST['num_days'] * 86400);
		$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox WHERE shout_datestamp < '$deletetime'");
		$numrows = dbrows($result);
		$result = dbquery("DELETE FROM ".$fusion_prefix."shoutbox WHERE shout_datestamp < '$deletetime'");
		opentable(LAN_400);
		echo "<center><br>
$numrows ".LAN_401."<br><br>
<a href='shoutbox.php'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
		closetable();
	} else if ($action == "delete") {
		opentable(LAN_404);
		$result = dbquery("DELETE FROM ".$fusion_prefix."shoutbox WHERE shout_id='$shout_id'");
		echo "<center><br>
".LAN_405."<br><br>
<a href='shoutbox.php'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
		closetable();
	} else {
		if (isset($_POST['saveshout'])) {
			if ($action == "edit") {
				$shout_message = str_replace("\n", " ", $_POST['shout_message']);
				$shout_message = preg_replace("/^(.{255}).*$/", "$1", $shout_message);
				$shout_message = preg_replace("/([^\s]{25})/", "$1\n", $shout_message);
				$shout_message = stripinput($shout_message);
				$shout_message = str_replace("\n", "<br>", $shout_message);
				$result = dbquery("UPDATE ".$fusion_prefix."shoutbox SET shout_message='$shout_message' WHERE shout_id='$shout_id'");
				header("Location: shoutbox.php");
			}
		}
		if ($action == "edit") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox WHERE shout_id='$shout_id'");
			$data = dbarray($result);
			opentable(LAN_420);
			echo "<form name='editform' method='post' action='$PHP_SELF?action=edit&shout_id=".$data['shout_id']."'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_421."</td>
</tr>
<tr>
<td><textarea name='shout_message' rows='3' class='textbox' style='width:250px;'>".str_replace("<br>", "", $data['shout_message'])."</textarea></td>
</tr>
<tr>
<td align='center'><input type='submit' name='saveshout' value='".LAN_422."' class='button'></td>
</tr>
</form>
</table>\n";
			closetable();
			tablebreak();
		}
		opentable(LAN_440);
		$itemsperpage = 20;
		$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox");
		$rows = dbrows($result);
		if (!$rowstart) {
			$rowstart = 0;
		}
		echo "<form name='deleteform' method='post' action='$PHP_SELF?action=deleteshouts'>
<div align='center'>\n";
		if ($rows != 0) {
			if ($rowstart == 0) {
				$opts = "<option value='90'>90</option>
<option value='60'>60</option>
<option value='30'>30</option>
<option value='20'>20</option>
<option value='10'>10</option>\n";
				echo LAN_430." <select name='num_days' class='textbox' style='width:50px'>$opts</select>".LAN_431."<br><br>
<input type='submit' name='deleteshouts' value='".LAN_432."' class='button'><br><hr>
</div>
</form>\n";
			}
			$totalpages = ceil($rows / $itemsperpage);	
			$currentpage = $rowstart / $itemsperpage + 1;
			$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox LEFT JOIN ".$fusion_prefix."users ON ".$fusion_prefix."shoutbox.shout_name=".$fusion_prefix."users.user_id ORDER BY shout_datestamp DESC LIMIT $rowstart,$itemsperpage");
			$i = 1;
			$numrows = dbrows($result);
			while ($data = dbarray($result)) {
				echo "<table align='center' border='0' cellpadding='0' cellspacing='1' width='80%' class='tbl-border'>
<tr>
<td class='tbl2'>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='small'>";
				if ($data['user_name']) {
					echo "<a href='".fusion_basedir."profile.php?lookup=".$data['shout_name']."' class='slink'>".$data['user_name']."</a>";
				} else {
					echo "<span class='alt'>".$data['shout_name']."</span>";
				}
				echo "</td>\n";
				if (SuperAdmin()) echo "<td align='right' class='small'>IP: ".$data['shout_ip']."</td>\n";
				echo "</tr>
</table>
</td>
</tr>
<tr>
<td class='tbl1'>".str_replace("<br>", "", parsesmileys($data['shout_message']))."</td>
</tr>
<tr>
<td class='tbl2'>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='small'><a href='$PHP_SELF?action=edit&shout_id=".$data['shout_id']."'>".LAN_441."</a> |
<a href='$PHP_SELF?action=delete&shout_id=".$data['shout_id']."'>".LAN_442."</a></td>
<td align='right' class='small'>".strftime($settings['shortdate'], $data['shout_datestamp']+($settings['timeoffset']*3600))."</td>
</tr>
</table>
</td>
</tr>
</table>\n";
				if ($i != $numrows) echo "<br>\n";
				$i++;
			}
		} else {
			echo "<center><br>
".LAN_443."<br><br>
</center>\n";
		}
		closetable();
		echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"$PHP_SELF?")."
</div>\n";
	}
}

echo "</td>\n";
require "../footer.php";
?>