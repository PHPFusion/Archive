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
@include "../fusion_config.php";
include "../header.php";
include fusion_basedir."subheader.php";
include "navigation.php";

if (!SuperAdmin()) { header("Location:../index.php"); exit; }

opentable("Upgrade");
if (SuperAdmin()) {
	if ($settings['version'] < 500) {
		if (!isset($_POST['stage'])) {
			echo "<table align='center' cellpadding='0' cellspacing='0' class='body'>
<form name='upgradeform' method='post' action='$PHP_SELF'>
<tr>
<td>
A database upgrade is available for this installation of PHP-Fusion. It is highly
recommended that you back-up your database prior to completing this process. This
process requires your fusion_config.php to be writable, so please ensure you CHMOD
fusion_config.php to 777. Once you have completed the upgrade, CHMOD the file back
to 644.<br><br>
<center>
<input type='hidden' name='stage' value='2'>
<input type='submit' name='upgrade' value='Upgrade' class='button'>
</center>
</td>
</tr>
</form>
</table>\n";
		}
		if ($_POST['stage'] == 2) {
			if (isset($_POST['upgrade'])) {
				$config = chr(60)."?\n// database settings\n";
				$config .= chr(36)."dbhost=".chr(34).$dbhost.chr(34).";\n";
				$config .= chr(36)."dbusername=".chr(34).$dbusername.chr(34).";\n";
				$config .= chr(36)."dbpassword=".chr(34).$dbpassword.chr(34).";\n";
				$config .= chr(36)."dbname=".chr(34).$dbname.chr(34).";\n";
				$config .= chr(36)."fusion_prefix=".chr(34).$fusion_prefix.chr(34).";\n\n";
				$config .= "define(".chr(34)."FUSION_PREFIX".chr(34).", ".chr(34).$fusion_prefix.chr(34).");\n";
				$config .= "define(".chr(34)."FUSION_ROOT".chr(34).", ".chr(34).fusion_root.chr(34).");\n";
				$config .= "?".chr(62);
				$fp = fopen("../fusion_config.php","w");
				if (fwrite($fp, $config)) {
					$sql = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."new_users");
					$sql = dbquery("CREATE TABLE ".$fusion_prefix."new_users (
					user_code VARCHAR(32) NOT NULL,
					user_email VARCHAR(100) NOT NULL,
					user_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
					user_info TEXT NOT NULL
					) TYPE=MyISAM;");
	
					$sql = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."infusions");
					$sql = dbquery("CREATE TABLE ".$fusion_prefix."infusions (
					inf_id smallint(5) unsigned NOT NULL auto_increment,
					inf_name varchar(100) NOT NULL default '',
					inf_folder varchar(100) NOT NULL default '',
					inf_admin varchar(100) NOT NULL default '',
					inf_version smallint(5) unsigned NOT NULL default '0',
					inf_installed tinyint(1) unsigned NOT NULL default '0',
					PRIMARY KEY (inf_id)
					) TYPE=MyISAM;");
					
					$sql = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."ratings");
					$sql = dbquery("CREATE TABLE ".$fusion_prefix."ratings (
					rating_id smallint(5) unsigned NOT NULL auto_increment,
					rating_item_id smallint(5) unsigned NOT NULL default '0',
					rating_type char(1) NOT NULL default '',
					rating_user smallint(5) unsigned NOT NULL default '0',
					rating_vote tinyint(1) unsigned NOT NULL default '0',
					rating_datestamp int(10) unsigned NOT NULL default '0',
					rating_ip varchar(20) NOT NULL default '0.0.0.0',
					PRIMARY KEY (rating_id)
					) TYPE=MyISAM;");
					
					$sql = dbquery("CREATE TABLE ".$fusion_prefix."submissions (
					submit_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT ,
					submit_type CHAR(1) NOT NULL ,
					submit_user SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL ,
					submit_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL ,
					submit_criteria TEXT NOT NULL ,
					PRIMARY KEY (submit_id)
					) TYPE=MyISAM;");
					
					$sql = dbquery("DELETE FROM ".$fusion_prefix."panels WHERE panel_type='file'");
					$sql = dbquery("UPDATE ".$fusion_prefix."panels SET panel_side='1' WHERE panel_side='l'");
					$sql = dbquery("UPDATE ".$fusion_prefix."panels SET panel_side='3' WHERE panel_side='r'");
					$sql = dbquery("ALTER TABLE ".$fusion_prefix."panels CHANGE panel_side panel_side TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL");
					// Refresh remaining panels
					$i = 1;
					$sql = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='1' ORDER BY panel_order");
					while ($data = dbarray($sql)) {
						$sql2 = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
						$i++;
					}
					$i = 1;
					$sql = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='3' ORDER BY panel_order");
					while ($data = dbarray($sql)) {
						$sql2 = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
						$i++;
					}
					
					$sql = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order=panel_order+4 WHERE panel_order>='1' AND panel_side='1'");
					$sql = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES('', 'Navigation', 'navigation_panel', '', '1', '1', 'file', '0', '1')");
					$sql = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Online Users', 'online_users_panel', '', '1', 2, 'file', 0, 1)");
					$sql = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Forum Threads', 'forum_threads_panel', '', '1', 3, 'file', 0, 1)");
					$sql = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Latest Articles', 'latest_articles_panel', '', '1', 4, 'file', 0, 1)");
	
					$sql = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Welcome Message', 'welcome_message_panel', '', '2', 1, 'file', 0, 1)");
					$sql = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Active Forum Threads', 'forum_threads_list_panel', '', '2', 2, 'file', 0, 1)");
			
					$sql = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order=panel_order+3 WHERE panel_order>='1' AND panel_side='1'");
					$sql = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'User Info', 'user_info_panel', '', '3', 1, 'file', 0, 1)");
					$sql = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Member Poll', 'member_poll_panel', '', '3', 2, 'file', 0, 1)");
					$sql = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Shoutbox', 'shoutbox_panel', '', '3', 3, 'file', 0, 1)");
					
					$sql = dbquery("ALTER TABLE ".$fusion_prefix."site_links ADD link_position TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL AFTER link_visibility");
					$sql = dbquery("UPDATE ".$fusion_prefix."site_links SET link_url='submit.php?stype=n', link_visibility='1' WHERE link_url='submit_news.php'");
					$sql = dbquery("UPDATE ".$fusion_prefix."site_links SET link_url='submit.php?stype=a', link_visibility='1' WHERE link_url='submit_article.php'");
					$sql = dbquery("UPDATE ".$fusion_prefix."site_links SET link_url='submit.php?stype=l', link_visibility='1' WHERE link_url='submit_link.php'");

					$sql = dbquery("UPDATE ".$fusion_prefix."posts SET post_showsig='0' WHERE post_showsig='n'");
					$sql = dbquery("UPDATE ".$fusion_prefix."posts SET post_showsig='1' WHERE post_showsig='y'");
					$sql = dbquery("UPDATE ".$fusion_prefix."posts SET post_smileys='0' WHERE post_smileys='n'");
					$sql = dbquery("UPDATE ".$fusion_prefix."posts SET post_smileys='1' WHERE post_smileys='y'");
				
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."posts CHANGE post_showsig post_showsig TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."posts CHANGE post_smileys post_smileys TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");

					$sql = dbquery("UPDATE ".$fusion_prefix."threads SET thread_sticky='0' WHERE thread_sticky='n'");
					$sql = dbquery("UPDATE ".$fusion_prefix."threads SET thread_sticky='1' WHERE thread_sticky='y'");
					$sql = dbquery("UPDATE ".$fusion_prefix."threads SET thread_locked='0' WHERE thread_locked='n'");
					$sql = dbquery("UPDATE ".$fusion_prefix."threads SET thread_locked='1' WHERE thread_locked='y'");
				
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."threads CHANGE thread_sticky thread_sticky TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."threads CHANGE thread_locked thread_locked TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");

					$sql=dbquery("ALTER TABLE ".$fusion_prefix."forums DROP forum_threads");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."forums DROP forum_posts");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."news ADD news_start INT(10) UNSIGNED DEFAULT '0' NOT NULL AFTER news_datestamp");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."news ADD news_end INT(10) UNSIGNED DEFAULT '0' NOT NULL AFTER news_start");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."polls DROP poll_optcount");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."polls DROP poll_votes");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD start_page VARCHAR(50) DEFAULT 'news.php' NOT NULL AFTER footer");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD other_page VARCHAR(100) DEFAULT '' NOT NULL AFTER start_page");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD enable_registration TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL AFTER attachtypes");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD email_verification TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL AFTER enable_registration");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD display_validation TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL AFTER email_verification");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD validation_method VARCHAR(5) DEFAULT 'image' NOT NULL AFTER display_validation");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD thumb_compression CHAR(3) DEFAULT 'gd2' NOT NULL AFTER thumb_image_h");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD bad_words TEXT NOT NULL AFTER album_max_b");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD bad_word_replace VARCHAR(20) DEFAULT '[censored]' NOT NULL AFTER bad_words");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."settings DROP forumpanel");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."threads DROP thread_replies");
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."articles DROP article_comments"); 
					$sql=dbquery("ALTER TABLE ".$fusion_prefix."news DROP news_comments"); 
					$sql=dbquery("UPDATE ".$fusion_prefix."settings SET language='English', theme='Prometheus', version='500'");
					
					echo "<center><br>\nUpgrade Successful.<br><br>\n</center>";
				}
				fclose($fp);
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

include "../footer.php";
?>