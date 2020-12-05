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
@require_once "../fusion_config.php";
require_once "../fusion_core.php";
require_once FUSION_BASE."subheader.php";
require_once "navigation.php";

if (!iADMIN) { header("Location:../index.php"); exit; }

opentable("Upgrade");
if (iADMIN) {
	if ($settings['version'] < 501) {
		if (!isset($_POST['stage'])) {
			echo "<table align='center' cellpadding='0' cellspacing='0' class='body'>
<form name='upgradeform' method='post' action='upgrade.php'>
<tr>
<td>
<center>
A database upgrade is available for this installation of PHP-Fusion.<br>
It is highly recommended that you back-up your database prior to<br>
completing this process.<br><br>
Please select the primary administrator the click Upgrade<br><br>
<select name='prim_admin' class='textbox'>\n";
$sql = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_mod='4' ORDER BY user_id");
while ($data = dbarray($sql)) {
	echo "<option value='".$data['user_id']."'>".$data['user_name']."</option>\n";
}
echo "</select><br><br>
<input type='checkbox' name='move_pages' value='1' checked> Transfer custom pages to my database<br>
<span class='small2'>Leave ticked if you are updating from v5.00</span><br><br>
<input type='hidden' name='stage' value='2'>
<input type='submit' name='upgrade' value='Upgrade' class='button'>
</center>
</td>
</tr>
</form>
</table>\n";
		}
		if (isset($_POST['stage']) && $_POST['stage'] == 2) {
			if (isset($_POST['upgrade'])) {
				$forum_mods == ""; $i = 1;
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."users CHANGE user_mod user_level TINYINT(3) UNSIGNED DEFAULT '250' NOT NULL");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."forums ADD forum_moderators TEXT NOT NULL AFTER forum_description");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."forums ADD forum_posting TINYINT(3) UNSIGNED DEFAULT '250' NOT NULL AFTER forum_access");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."infusions CHANGE inf_version inf_version VARCHAR(10) DEFAULT '1' NOT NULL");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."messages CHANGE message_subject message_subject VARCHAR(200) DEFAULT '' NOT NULL");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."news ADD news_visibility TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL AFTER news_end");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."site_links ADD link_window TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER link_position");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."users ADD user_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0' AFTER user_lastvisit");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."users ADD user_rights TEXT NOT NULL AFTER user_ip");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."users ADD user_groups TEXT NOT NULL AFTER user_rights");
				$sql = dbquery("CREATE TABLE ".$fusion_prefix."blacklist (
					blacklist_id smallint(5) unsigned NOT NULL auto_increment,
					blacklist_ip varchar(20) NOT NULL default '',
					blacklist_email varchar(100) NOT NULL default '',
					blacklist_reason text NOT NULL,
					PRIMARY KEY (blacklist_id)
				) TYPE=MyISAM;");
				$sql = dbquery("CREATE TABLE ".$fusion_prefix."user_groups (
					group_id TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
					group_name VARCHAR(100) NOT NULL,
					group_description VARCHAR(200) NOT NULL,
					PRIMARY KEY (group_id) 
				) TYPE=MyISAM;");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."custom_pages CHANGE page_access page_access TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."forums CHANGE forum_access forum_access TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."panels CHANGE panel_access panel_access TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL");
				$sql = dbquery("ALTER TABLE ".$fusion_prefix."site_links CHANGE link_visibility link_visibility TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL");
				$sql = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_access='251' WHERE page_access>='2'");
				$sql = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_access='250' WHERE page_access='1'");
				$sql = dbquery("UPDATE ".$fusion_prefix."forums SET forum_access='251' WHERE forum_access>='2'");
				$sql = dbquery("UPDATE ".$fusion_prefix."forums SET forum_access='250' WHERE forum_access='1'");
				$sql = dbquery("UPDATE ".$fusion_prefix."panels SET panel_access='251' WHERE panel_access>='2'");
				$sql = dbquery("UPDATE ".$fusion_prefix."panels SET panel_access='250' WHERE panel_access='1'");
				$sql = dbquery("UPDATE ".$fusion_prefix."site_links SET link_visibility='251' WHERE link_visibility>='2'");
				$sql = dbquery("UPDATE ".$fusion_prefix."site_links SET link_visibility='250' WHERE link_visibility='1'");
				$sql = dbquery("UPDATE ".$fusion_prefix."users SET user_level='252', user_rights='1.2.3.4.5.6.7.8.9.A.B.C.D.E.F.G.H.I.J.K.L.M.N.O.P.Q.R.S' WHERE user_level='4' AND user_id='".$_POST['prim_admin']."'");
				$sql = dbquery("UPDATE ".$fusion_prefix."users SET user_level='251', user_rights='1.2.3.4.5.6.7.8.9.A.B.C.D.E.F.G.H.I.J.K.L.M.N.O.P.Q.R.S' WHERE user_level='4'");
				$sql = dbquery("UPDATE ".$fusion_prefix."users SET user_level='251', user_rights='1.4.6.9.B.D.E.G.I.J.M.N' WHERE user_level='3'");
				$sql = dbquery("UPDATE ".$fusion_prefix."users SET user_level='250' WHERE user_level<'251'");
				$sql = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_level>'250'");
				while ($data = dbarray($sql)) {
					$forum_mods .= $data['user_id'];
					if ($i < dbrows($sql)) $forum_mods .= ".";
					$i++;
				}
				$sql = dbquery("UPDATE ".$fusion_prefix."forums SET forum_moderators='$forum_mods'");
				$sql = dbquery("UPDATE ".$fusion_prefix."settings SET version='501'");
				if (isset($_POST['move_pages'])) {
					$sql = dbquery("ALTER TABLE ".$fusion_prefix."custom_pages ADD page_content TEXT NOT NULL AFTER page_access");
					$result = dbquery("SELECT * FROM ".$fusion_prefix."custom_pages");
					if (dbrows($result) != 0) {
						while ($data = dbarray($result)) {
							$file = fopen("../fusion_pages/".$data['page_id'].".php","rb");
							$content = fread($file, filesize("../fusion_pages/".$data['page_id'].".php"));
							fclose($file);
							$content = addslashes(addslashes($content));
							$sql = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_content='$content' WHERE page_id='".$data['page_id']."'");
						}
					}
					$sql= dbquery("SELECT * FROM ".$fusion_prefix."site_links WHERE link_url LIKE '%fusion_pages/index.php%'");
					while ($data = dbarray($sql)) {
						$new_url = str_replace("fusion_pages/index.php", "viewpage.php", $data['link_url']);
						$sql2 = dbquery("UPDATE ".$fusion_prefix."site_links SET link_url='$new_url' WHERE link_id='".$data['link_id']."'");
					}
					echo "<center><br>\nCustom pages successfully imported into database.<br><br>\n</center>\n";
				}				
				echo "<center><br>\nDatabase upgrade complete.<br><br>\n</center>\n";
			}
		}
	} else {
		echo "<center><br>\nThere is no database upgrade available.<br><br>\n</center>\n";
	}
}
closetable();

echo "</td>
</tr>
</table>\n";

require_once FUSION_BASE."footer.php";
?>