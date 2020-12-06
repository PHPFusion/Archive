<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/infusions.php";

if (!checkrights("I")) fallback("../index.php");

$temp = opendir(INFUSIONS);
$file_list = array();
while ($folder = readdir($temp)) {
	if (!in_array($folder, array("..","."))) {
		if (is_dir(INFUSIONS.$folder)) $file_list[] = $folder;
	}
}
closedir($temp); sort($file_list);

opentable($locale['400']);
echo "<form name='infuseform' method='post' action='".FUSION_SELF."'>
<center><select name='infusion' class='textbox'>\n";
for ($i=0;$i < count($file_list);$i++) {
	echo "<option value='".$file_list[$i]."'$sel>".ucwords(str_replace("_"," ",$file_list[$i]))."</option>\n";
}
echo "</select> <input type='submit' name='infuse' value='".$locale['401']."' class='button'>\n";
if (isset($error)) echo "<br><br>\n".($error == 1 ? $locale['402'] : $locale['403'])."<br><br>\n";
echo "</center>\n</form>\n";
closetable();

if (isset($_POST['infuse']) && isset($_POST['infusion'])) {
	$infusion = stripinput($_POST['infusion']);
	if (file_exists(INFUSIONS.$infusion."/infusion.php")) {
		include INFUSIONS.$infusion."/infusion.php";
		$result = dbquery("SELECT * FROM ".$db_prefix."infusions WHERE inf_folder='$inf_folder'");
		if (dbrows($result)) {
			$data = dbarray($result);
			if ($inf_version > $data['inf_version']) {
				if ($inf_altertables >= 1) {
					for ($i=1;$i < ($inf_newtables+1);$i++) $result = dbquery("ALTER TABLE ".$db_prefix.$inf_altertable_[$i]);
				}
				$result2 = dbquery("UPDATE ".$db_prefix."infusions WHERE inf_id='".$data['inf_id']."'");
			} else {
				$error = "1";
			}
		} else {
			if ($inf_admin_panel != "") {
				if ($inf_admin_image == "") $inf_admin_image = "infusion_panel.gif";
				$result = dbquery("INSERT INTO ".$db_prefix."admin VALUES('', 'IP', '$inf_admin_image', '$inf_title', '".INFUSIONS."$inf_folder/$inf_admin_panel', '4')");
			}
			if ($inf_link_name != "") {
				$link_order = dbresult(dbquery("SELECT MAX(link_order) FROM ".$db_prefix."site_links"),0)+1;
				$result = dbquery("INSERT INTO ".$db_prefix."site_links VALUES('', '$inf_link_name', '".str_replace("../","",INFUSIONS).$inf_folder."/".$inf_link_url."', '$inf_link_visibility', '1', '0', '$link_order')");
			}
			if ($inf_newtables >= 1) {
				for ($i=1;$i < ($inf_newtables+1);$i++) $result = dbquery("CREATE TABLE ".$db_prefix.$inf_newtable_[$i]);
			}
			if ($inf_insertdbrows >= 1) {
				for ($i=1;$i < ($inf_insertdbrows+1);$i++) $result = dbquery("INSERT INTO ".$db_prefix.$inf_insertdbrow_[$i]);
			}
			$result = dbquery("INSERT INTO ".$db_prefix."infusions VALUES('', '$inf_title', '$inf_folder', '$inf_version')");
		}
	} else {
		$error = "2";
	}
	redirect(FUSION_SELF.(isset($error) ? "?error=".$error : ""));
}


if (isset($defuse)) {
	if (!isNum($defuse)) fallback(FUSION_SELF);
	$result = dbquery("SELECT * FROM ".$db_prefix."infusions WHERE inf_id='$defuse'");
	$data = dbarray($result);
	include INFUSIONS.$data['inf_folder']."/infusion.php";
	if ($inf_admin_panel != "") {
		$result = dbquery("DELETE FROM ".$db_prefix."admin WHERE admin_rights='IP' AND admin_link='".INFUSIONS."$inf_folder/$inf_admin_panel' AND admin_page='4'");
	}
	if ($inf_link_name != "") {
		$result2 = dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_url='".str_replace("../","",INFUSIONS).$inf_folder."/".$inf_link_url."'");
		if (dbrows($result2) == "1") {
			$data2 = dbarray($result2);
			$result = dbquery("UPDATE ".$db_prefix."site_links SET link_order=link_order-1 WHERE link_order>'".$data2['link_order']."'");
			$result = dbquery("DELETE FROM ".$db_prefix."site_links WHERE link_id='".$data2['link_id']."'");
		}
	}
	if ($inf_newtables >= 1) {
		for ($i=1;$i < ($inf_newtables+1);$i++) $result = dbquery("DROP TABLE ".$db_prefix.$inf_droptable_[$i]);
	}
	$result = dbquery("DELETE FROM ".$db_prefix."infusions WHERE inf_id='$defuse'");
	redirect(FUSION_SELF);
}

$result = dbquery("SELECT * FROM ".$db_prefix."infusions ORDER BY inf_title");
if (dbrows($result)) {
	$i = 0;
	tablebreak();
	opentable($locale['404']);
	echo "<table align='center' width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>
<tr>
<td class='tbl2'><b>".$locale['405']."</b></td>
<td align='center' width='50' class='tbl2'><b>".$locale['406']."</b></td>
<td align='center' width='150' class='tbl2'><b>".$locale['407']."</b></td>
<td align='center' width='85' class='tbl2'><b>".$locale['408']."</b></td>
<td width='85' class='tbl2'> </td>
</tr>\n";
	while ($data = dbarray($result)) {
		if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
		include INFUSIONS.$data['inf_folder']."/infusion.php";
		echo "<tr>
<td class='$row_color'><span title='$inf_description' style='cursor:hand'>$inf_title</span></td>
<td align='center' class='$row_color'>$inf_version</td>
<td align='center' class='$row_color'>$inf_developer</td>
<td align='center' class='$row_color'><a href='mailto:$inf_email'>".$locale['409']."</a> / <a href='$inf_weburl'>".$locale['410']."</a></td>
<td align='center' class='$row_color'><a href='".FUSION_SELF."?defuse=".$data['inf_id']."' onclick='return Defuse();'>".$locale['411']."</a></td>
</tr>\n";
		$i++;
	}
	echo "</table>\n";
	closetable();
}
		
echo "<script language=\"JavaScript\">
function Defuse() {
	return confirm('".$locale['412']."');
}
</script>\n";

echo "</td>\n";
require_once BASEDIR."footer.php";
?>