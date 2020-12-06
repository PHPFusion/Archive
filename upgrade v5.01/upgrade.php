<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../fusion_config.php";
require_once "../fusion_core.php";
require_once FUSION_BASE."subheader.php";
require_once "navigation.php";

if (!checkrights("O")) { header("Location:../index.php"); exit; }
$error = "0";

opentable("Upgrade");
if (str_replace(".","",$settings['version']) < 600100) {
	if (!isset($_POST['stage'])) {
		echo "<table align='center' cellpadding='0' cellspacing='0' class='body'>
<form name='upgradeform' method='post' action='upgrade.php'>
<tr>
<td>
<center>
A database upgrade is available for this installation of PHP-Fusion.<br>
It is highly recommended that you back-up your database prior to<br>
completing this process.<br><br>\n";

if (!file_exists("../config.php")) {
	echo "config.php does not exist, please upload it to your root folder.<br><br>\n";
	$error = "1";
} elseif (!is_writable("../config.php")) {
	echo "config.php is not writable, please chmod it to 777 and try again.<br><br>\n";
	$error = "1";
}

if ($error == "0") { echo "<input type='hidden' name='stage' value='2'>
<input type='submit' name='upgrade' value='Upgrade' class='button'>\n"; }

echo "</center>
</td>
</tr>
</form>
</table>\n";
	}
	if (isset($_POST['stage']) && $_POST['stage'] == 2) {
		if (isset($_POST['upgrade'])) {
			$config = "<?php
// database settings
"."$"."db_host="."\"".$dbhost."\"".";
"."$"."db_user="."\"".$dbusername."\"".";
"."$"."db_pass="."\"".$dbpassword."\"".";
"."$"."db_name="."\"".$dbname."\"".";
"."$"."db_prefix="."\"".$fusion_prefix."\"".";
define("."\""."DB_PREFIX"."\"".", "."\"".$fusion_prefix."\"".");
?>";
			$temp = fopen("../config.php","w");
			if (!fwrite($temp, $config)) {
				echo "<center><br>\nUnable to write new config.<br><br>\n</center>\n";
				fclose($temp);
				exit;
			} else {
				fclose($temp);
				$result = dbquery("DROP TABLE ".$fusion_prefix."temp");
				$result = dbquery("CREATE TABLE ".$fusion_prefix."vcode (
				vcode_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
				vcode_1 VARCHAR(5) NOT NULL DEFAULT '',
				vcode_2 VARCHAR(32) NOT NULL DEFAULT ''
				) TYPE=MyISAM;");

				$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."admin");
				$result = dbquery("CREATE TABLE ".$fusion_prefix."admin (
				admin_id TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
				admin_rights CHAR(2) NOT NULL default '',
				admin_image VARCHAR(50) NOT NULL default '',
				admin_title VARCHAR(50) NOT NULL default '',
				admin_link VARCHAR(100) NOT NULL default 'reserved',
				admin_page TINYINT(1) UNSIGNED NOT NULL default '1',
				PRIMARY KEY (admin_id)
				) TYPE=MyISAM;");
				
				$result = dbquery("ALTER TABLE ".$fusion_prefix."messagesv5 RENAME ".$fusion_prefix."messages_old");
				if (!$result) {
					echo " Don't worry this is an expected error.<br>";
					$result = dbquery("ALTER TABLE ".$fusion_prefix."messages RENAME ".$fusion_prefix."messages_old");
				}
				
				$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."messages");
				$result = dbquery("CREATE TABLE ".$fusion_prefix."messages (
				message_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
				message_to SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
				message_from SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
				message_subject VARCHAR(100) NOT NULL DEFAULT '',
				message_message TEXT NOT NULL,
				message_smileys CHAR(1) NOT NULL DEFAULT '',
				message_read TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				message_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
				message_folder TINYINT(1) UNSIGNED NOT NULL DEFAULT  '0',
				PRIMARY KEY (message_id)
				) TYPE=MyISAM;");
				
				$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."messages_options");
				$result = dbquery("CREATE TABLE ".$fusion_prefix."messages_options (
				user_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
				pm_email_notify TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				pm_save_sent TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				pm_inbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
				pm_savebox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
				pm_sentbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
				PRIMARY KEY (user_id)
				) TYPE=MyISAM;");

				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (1, 'AD', 'admins.gif', 'Administrators', 'administrators.php', 2)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (2, 'AC', 'article_cats.gif', 'Article Categories', 'article_cats.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (3, 'A', 'articles.gif', 'Articles', 'articles.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (4, 'B', 'blacklist.gif', 'Blacklist', 'blacklist.php', 2)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (5, 'C', '', 'Comments', 'reserved', 2)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (6, 'CP', 'c-pages.gif', 'Custom Pages', 'custom_pages.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (7, 'DB', 'db_backup.gif', 'Database Backup', 'db_backup.php', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (8, 'DC', 'dl_cats.gif', 'Download Categories', 'download_cats.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (9, 'D', 'dl.gif', 'Downloads', 'downloads.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (10, 'FQ', 'faq.gif', 'FAQs', 'faq.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (11, 'F', 'forums.gif', 'Forums', 'forums.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (12, 'IM', 'images.gif', 'Images', 'images.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (13, 'I', 'infusions.gif', 'Infusions', 'infusions.php', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (14, 'IP', '', 'Infusion Panels', 'reserved', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (15, 'M', 'members.gif', 'Members', 'members.php', 2)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (16, 'N', 'news.gif', 'News', 'news.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (17, 'P', 'panels.gif', 'Panels', 'panels.php', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (18, 'PH', 'photoalbums.gif', 'Photo Albums', 'photoalbums.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (19, 'PI', 'phpinfo.gif', 'PHP Info', 'phpinfo.php', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (20, 'PO', 'polls.gif', 'Polls', 'polls.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (21, 'S', 'shout.gif', 'Shoutbox', 'shoutbox.php', 2)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (22, 'SL', 'site_links.gif', 'Site Links', 'site_links.php', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (23, 'SU', 'submissions.gif', 'Submissions', 'submissions.php', 2)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (24, 'U', 'upgrade.gif', 'Upgrade', 'upgrade.php', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (25, 'UG', 'user_groups.gif', 'User Groups', 'user_groups.php', 2)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (26, 'WC', 'wl_cats.gif', 'Web Link Categories', 'weblink_cats.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (27, 'W', 'wl.gif', 'Web Links', 'weblinks.php', 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (28, 'S1', 'settings.gif', 'Main Settings', 'settings_main.php', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (29, 'S2', 'settings_time.gif', 'Time & Date Settings', 'settings_time.php', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (30, 'S3', 'settings_forum.gif', 'Forum Settings', 'settings_forum.php', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (31, 'S4', 'registration.gif', 'Registration Settings', 'settings_registration.php', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (32, 'S5', 'photoalbums.gif', 'Photo Gallery Settings', 'settings_photo.php', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (33, 'S6', 'settings_misc.gif', 'Miscellaneous Settings', 'settings_misc.php', 3)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES (34, 'S7', 'settings_pm.gif', 'Private Message Settings', 'settings_messages.php', 3)");
				echo "2";
				$result = dbquery("INSERT INTO ".$fusion_prefix."messages_options VALUES ('0', '0', '1', '20', '20', '20')");

				$result = dbquery("ALTER TABLE ".$fusion_prefix."infusions DROP inf_admin");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."infusions DROP inf_installed");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."settings DROP start_page");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."settings DROP forumdate");

				$result = dbquery("ALTER TABLE ".$fusion_prefix."article_cats ADD article_cat_access TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL AFTER article_cat_description");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."articles ADD article_allow_comments TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."articles ADD article_allow_ratings TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."comments ADD comment_smileys TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL AFTER comment_message");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."custom_pages ADD page_allow_comments TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."custom_pages ADD page_allow_ratings TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."downloads CHANGE download_description download_description TEXT NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."download_cats ADD download_cat_access TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL AFTER download_cat_description");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."infusions CHANGE inf_name inf_title VARCHAR(100) NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."news ADD news_allow_comments TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."news ADD news_allow_ratings TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."posts ADD post_ip VARCHAR(20) DEFAULT '0.0.0.0' NOT NULL AFTER post_datestamp");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD forumdate VARCHAR(50) DEFAULT '%d-%m-%Y %H:%M' NOT NULL AFTER longdate");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD subheaderdate VARCHAR(50) DEFAULT '%B %d %Y %H:%M:%S' NOT NULL AFTER forumdate");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD admin_activation TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER email_verification");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD bad_words_enabled TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER album_max_b");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."users ADD user_aim VARCHAR(16) DEFAULT '' NOT NULL AFTER user_birthdate");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."weblinks CHANGE weblink_description weblink_description TEXT NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."weblink_cats ADD weblink_cat_access TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL AFTER weblink_cat_description");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."settings CHANGE language locale VARCHAR(20) DEFAULT 'English' NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."settings CHANGE other_page opening_page VARCHAR(100) DEFAULT '0' NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."settings CHANGE version version VARCHAR(10) DEFAULT '0' NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."users CHANGE user_level user_level TINYINT(3) UNSIGNED DEFAULT '101' NOT NULL");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."users CHANGE user_ban user_status TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");

				$result = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_access='101' WHERE page_access='250'");
				$result = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_access='102' WHERE page_access='251'");
				$result = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_access='103' WHERE page_access='252'");
	
				$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_access='101' WHERE forum_access='250'");
				$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_access='102' WHERE forum_access='251'");
				$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_access='103' WHERE forum_access='252'");
	
				$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_posting='101' WHERE forum_posting='250'");
				$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_posting='102' WHERE forum_posting='251'");
				$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_posting='103' WHERE forum_posting='252'");
	
				$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_access='101' WHERE panel_access='250'");
				$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_access='102' WHERE panel_access='251'");
				$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_access='103' WHERE panel_access='252'");
	
				$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_visibility='101' WHERE link_visibility='250'");
				$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_visibility='102' WHERE link_visibility='251'");
				$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_visibility='103' WHERE link_visibility='252'");
	
				$result = dbquery("UPDATE ".$fusion_prefix."users SET user_level='101' WHERE user_level='250'");
				$result = dbquery("UPDATE ".$fusion_prefix."users SET user_level='102', user_rights='' WHERE user_level='251'");
				$result = dbquery("UPDATE ".$fusion_prefix."users SET user_level='103', user_rights='A.AC.AD.B.C.CP.DB.DC.D.FQ.F.IM.I.IP.M.N.P.PH.PI.PO.S.SL.S1.S2.S3.S4.S5.S6.S7.SU.UG.U.W.WC' WHERE user_level='252'");
	
				$result = dbquery("UPDATE ".$fusion_prefix."settings SET opening_page='news.php', theme='Milestone', version='6.00.104'");
				echo "<center><br>\nDatabase upgrade complete.<br><br>\n</center>\n";
			}
		}
	}
} else {
	echo "<center><br>\nThere is no database upgrade available.<br><br>\n</center>\n";
}
closetable();

echo "</td>
</tr>
</table>\n";

require_once FUSION_BASE."footer.php";
?>