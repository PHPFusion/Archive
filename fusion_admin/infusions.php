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

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_infusions.php";

if (!checkrights("C")) { header("Location:../index.php"); exit; }

// Check for any new Infusions and add them to the infusions table
$handle = opendir(FUSION_INFUSIONS);
while ($folder = readdir($handle)) {
	if ($folder != "." && $folder != ".." && is_dir(FUSION_INFUSIONS.$folder)) {
		if (file_exists(FUSION_INFUSIONS.$folder."/infusion.php")) {
			@include FUSION_INFUSIONS.$folder."/infusion.php";
			if (dbrows(dbquery("SELECT * FROM ".$fusion_prefix."infusions WHERE inf_name='$inf_name'")) == 0) {
				if ($inf_admin || $inf_create_tables) {
					$sql = dbquery("INSERT INTO ".$fusion_prefix."infusions VALUES('', '$inf_name', '$inf_folder', '$inf_admin', '$inf_version', '0')");
				}
			}
			unset($inf_folder,$inf_admin,$inf_link_name,$inf_link_url,$inf_create_tables,$inf_drop_tables,$inf_upgrade_alter_tables);
		}
	}
}
closedir($handle);

// Check if any Infusions have been removed and delete them from the infusions table 
$sql = dbquery("SELECT * FROM ".$fusion_prefix."infusions");
if (dbrows($sql) != 0) {
	while ($data = dbarray($sql)) {
		if (!is_dir(FUSION_INFUSIONS.$data['inf_folder'])) {
			$sql2 = dbquery("DELETE FROM ".$fusion_prefix."infusions WHERE inf_folder='".$data['inf_folder']."'");
		}
	}
}

// Install the Infusion components specified in infusion.php
if (isset($install)) {
	$sql = dbquery("SELECT * FROM ".$fusion_prefix."infusions WHERE inf_id='$install' AND inf_installed='0'");
	if (dbrows($sql) != 0) {
		$data = dbarray($sql);
		@include FUSION_INFUSIONS.$data['inf_folder']."/infusion.php";
		if ($inf_link_name) {
			$link_order = dbresult(dbquery("SELECT MAX(link_order) FROM ".$fusion_prefix."site_links"),0)+1;
			$sql = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '$inf_link_name', '".str_replace("../","",FUSION_INFUSIONS).$inf_folder."/".$inf_link_url."', '0', '1', '0', '$link_order')");
		}
		if ($inf_create_tables[0]) {
			$i=0;
			while ($i < count($inf_create_tables)) {
				$sql = dbquery($inf_create_tables[$i]);
				$i++;
			}
		}
		$sql = dbquery("UPDATE ".$fusion_prefix."infusions SET inf_installed='1' WHERE inf_id='".$data['inf_id']."'");	
		header("Location: ".FUSION_SELF);
	}
}

// Uninnstall the Infusion components specified in infusion.php
if (isset($uninstall)) {
	$sql = dbquery("SELECT * FROM ".$fusion_prefix."infusions WHERE inf_id='$uninstall' AND inf_installed='1'");
	if (dbrows($sql) != 0) {
		$data = dbarray($sql);
		@include FUSION_INFUSIONS.$data['inf_folder']."/infusion.php";
		
		if ($inf_link_name) {
			$data2 = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."site_links WHERE link_url='".str_replace("../","",FUSION_INFUSIONS).$inf_folder."/".$inf_link_url."'"));
			$sql = dbquery("UPDATE ".$fusion_prefix."site_links SET link_order=link_order-1 WHERE link_order>'".$data2['link_order']."'");
			$sql = dbquery("DELETE FROM ".$fusion_prefix."site_links WHERE link_id='".$data2['link_id']."'");
		}
		if ($inf_drop_tables[0]) {
			$i=0;
			while ($i < count($inf_drop_tables)) {
				$sql = dbquery($inf_drop_tables[$i]);
				$i++;
			}
		}
		$sql = dbquery("UPDATE ".$fusion_prefix."infusions SET inf_installed='0' WHERE inf_id='".$data['inf_id']."'");	
		header("Location: ".FUSION_SELF);
	}
}

// Upgrade the Infusion components specified in infusion.php
if (isset($upgrade)) {
	$sql = dbquery("SELECT * FROM ".$fusion_prefix."infusions WHERE inf_id='$upgrade' AND inf_installed='1'");
	if (dbrows($sql) != 0) {
		$data = dbarray($sql);
		@include FUSION_INFUSIONS.$data['inf_folder']."/infusion.php";
		if ($inf_upgrade_alter_tables[0]) {
			$i=0;
			while ($i < count($inf_upgrade_alter_tables)) {
				$sql = dbquery($inf_upgrade_alter_tables[$i]);
				$i++;
			}
		}
		$sql = dbquery("UPDATE ".$fusion_prefix."infusions SET inf_version='$inf_version' WHERE inf_id='".$data['inf_id']."'");	
		header("Location: ".FUSION_SELF);
	}
}

// Show a list of Infusions currently installed or not installed
opentable(LAN_400);
$sql = dbquery("SELECT * FROM ".$fusion_prefix."infusions ORDER BY inf_name");
if (dbrows($sql) != 0) {
	$i=0;
	echo "<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>
<tr>
<td class='tbl2' style='padding:0px 10px 0px 10px;'>".LAN_401."</td>
<td class='tbl2'>".LAN_402."</td>
</tr>\n";
	while($data = dbarray($sql)) {
		@include FUSION_INFUSIONS.$data['inf_folder']."/infusion.php";
		if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
		echo "<tr>
<td align='center' class='$row_color'>
<img src='".FUSION_IMAGES."infusion_".($data['inf_installed'] ? "on" : "off").".gif'>
<b>".$data['inf_name']."</b><br>
".LAN_403.$data['inf_version']."</td>
<td class='$row_color'><b>".LAN_404."</b> ".$inf_author."\n";
		if (isset($inf_email) || $inf_email != "") {
			echo "[<a href='mailto:$inf_email'>".LAN_405."</a>]\n";
		}
		if (isset($inf_email) || $inf_email != "") {
			echo "[<a href='".(strstr($inf_web, "http://") ? "" : "http://").$inf_web."'>".LAN_406."</a>]\n";
		}
		echo "<br><b>".LAN_407."</b> $inf_description<br><br>\n";
		if (!$data['inf_installed']) {
			echo "<a href='".FUSION_SELF."?install=".$data['inf_id']."' onClick='return InstallThis();'>".LAN_408."</a>\n";
		} else {
			echo "<a href='".FUSION_SELF."?uninstall=".$data['inf_id']."' onClick='return UninstallThis();'>".LAN_409."</a>\n";
		}
		if ($inf_version > $data['inf_version'] && $data['inf_installed']) {
			echo "| <a href='".FUSION_SELF."?upgrade=".$data['inf_id']."' onClick='return UpgradeThis();'>".LAN_410."</a>\n";
		}
		echo "</td>\n</tr>\n";
		$i++;
	}
	echo "</table>
<center><br>
· <img src='".FUSION_IMAGES."infusion_on.gif'> ".LAN_450." ·
<img src='".FUSION_IMAGES."infusion_off.gif'> ".LAN_451." ·
</center>\n";
} else {
	echo "<center><br>\n".LAN_411."<br><br>\n</center>\n";
}
closetable();
echo "<script language='JavaScript'>
function InstallThis() {
	return confirm('".LAN_430."');
}
function UninstallThis() {
	return confirm('".LAN_431."');
}
function UpgradeThis() {
	return confirm('".LAN_432."');
}
</script>\n";

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>