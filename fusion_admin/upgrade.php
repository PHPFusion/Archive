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
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

opentable("Upgrade");
if (SuperAdmin()) {
	if ($settings['version'] < 401) {
		if (!isset($_POST['stage'])) {
			echo "<table align='center' cellpadding='0' cellspacing='0' class='body'>
<form name='upgradeform' method='post' action='$PHP_SELF'>
<tr>
<td>
A database upgrade is available for this installation of PHP-Fusion. It is highly
recommended that you back-up your database prior to completing this process. The
following adjustments will be made:<br><br>
<b>The following changes will be applied:</b><br>
· news_name column will be altered to news_name smallint(5) in news database.<br>
· article_name column will be altered to article_name smallint(5) in article database.<br>
· news_email column will be dropped from news database.<br>
· article_email column will be dropped from article database.<br>
· user_birthdate (date) column will be added to users database table.<br>
· several photo album settings columns will be added to settings database table.<br>
· link_visibility smallint(5) column will be added to site_links database table.<br>
· comment_name in comments database table will be converted to new format.<br>
· shout_name in shoutbox database table will be converted to new format.<br>
· faq_cats and faqs tables will be created.<br>
· photo_albums and photos database tables will be created (optional).<br>
· guestbook_ip varchar(20) column will be added to guestbook database table.<br><br>
· maintenance_message text column will be added to settings database table.<br><br>
<center>
<input type='checkbox' name='createphotos' value='y' checked> Create photo_albums and photos database tables.<br><br>
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
				if (isset($_POST['createphotos'])) {
					$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."photo_albums");
					$result = dbquery("CREATE TABLE ".$fusion_prefix."photo_albums (
					album_id smallint(5) unsigned NOT NULL auto_increment,
					album_title varchar(50) NOT NULL default '',
					album_info varchar(200) default '',
					album_order smallint(5) unsigned NOT NULL default '0',
					PRIMARY KEY (album_id)
					) TYPE=MyISAM;");
								
					$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."photos");
					$result = dbquery("CREATE TABLE ".$fusion_prefix."photos (
					photo_id smallint(5) unsigned NOT NULL auto_increment,
					album_id smallint(5) unsigned NOT NULL default '0',
					photo_title varchar(50) NOT NULL default '',
					photo_date int(10) unsigned NOT NULL default '0',
					user_id smallint(5) unsigned NOT NULL default '0',
					photo_views smallint(5) unsigned NOT NULL default '0',
					photo_order smallint(5) unsigned NOT NULL default '0',
					PRIMARY KEY (photo_id)
					) TYPE=MyISAM;");
				}

				$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."faq_cats");
				$result = dbquery("CREATE TABLE ".$fusion_prefix."faq_cats (
				faq_cat_id smallint(5) unsigned NOT NULL auto_increment,
				faq_cat_name varchar(200) NOT NULL default '',
				PRIMARY KEY(faq_cat_id)
				) TYPE=MyISAM;");

				$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."faqs");
				$result = dbquery("CREATE TABLE ".$fusion_prefix."faqs (
				faq_id smallint(5) unsigned NOT NULL auto_increment,
				faq_cat_id smallint(5) unsigned NOT NULL default '0',
				faq_question varchar(200) NOT NULL default '',
				faq_answer text NOT NULL,
				PRIMARY KEY(faq_id)
				) TYPE=MyISAM;");

				$res1=dbquery("SELECT news_id,news_name FROM ".$fusion_prefix."news");
				while($d1=dbarray($res1)) {
					$d2=dbarray(dbquery("SELECT user_id,user_name FROM ".$fusion_prefix."users WHERE user_name='".$d1['news_name']."'"));
					$res=dbquery("UPDATE ".$fusion_prefix."news SET news_name='".$d2['user_id']."' WHERE news_id='".$d1['news_id']."'");
				}
				
				$res1=dbquery("SELECT article_id,article_name FROM ".$fusion_prefix."articles");
				while($d1=dbarray($res1)) {
					$d2=dbarray(dbquery("SELECT user_id,user_name FROM ".$fusion_prefix."users WHERE user_name='".$d1['article_name']."'"));
					$res=dbquery("UPDATE ".$fusion_prefix."articles SET article_name='".$d2['user_id']."' WHERE article_id='".$d1['article_id']."'");
				}	
				
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."users ADD user_birthdate DATE DEFAULT '0000-00-00' NOT NULL AFTER user_location");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD album_image_w SMALLINT(3) UNSIGNED DEFAULT '80' NOT NULL AFTER attachtypes");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD album_image_h SMALLINT(3) UNSIGNED DEFAULT '60' NOT NULL AFTER album_image_w");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD thumb_image_w SMALLINT(3) UNSIGNED DEFAULT '120' NOT NULL AFTER album_image_h");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD thumb_image_h SMALLINT(3) UNSIGNED DEFAULT '100' NOT NULL AFTER thumb_image_w");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD album_comments TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL AFTER thumb_image_h");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD albums_per_row SMALLINT(2) UNSIGNED DEFAULT '4' NOT NULL AFTER album_comments");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD albums_per_page SMALLINT(2) UNSIGNED DEFAULT '16' NOT NULL AFTER albums_per_row");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD thumbs_per_row SMALLINT(2) UNSIGNED DEFAULT '4' NOT NULL AFTER albums_per_page");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD thumbs_per_page SMALLINT(2) UNSIGNED DEFAULT '12' NOT NULL AFTER thumbs_per_row");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD album_max_w SMALLINT(3) UNSIGNED DEFAULT '400' NOT NULL AFTER thumbs_per_page");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD album_max_h SMALLINT(3) UNSIGNED DEFAULT '300' NOT NULL AFTER album_max_w");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD album_max_b INT(10) UNSIGNED DEFAULT '150000' NOT NULL AFTER album_max_h");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."site_links ADD link_visibility SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL AFTER link_url");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."guestbook ADD guestbook_ip VARCHAR(20) DEFAULT '0.0.0.0' NOT NULL");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."articles DROP article_email");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."news DROP news_email");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."articles CHANGE article_name article_name SMALLINT(5) UNSIGNED DEFAULT '1' NOT NULL");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."news CHANGE news_name news_name SMALLINT(5) UNSIGNED DEFAULT '1' NOT NULL");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings CHANGE maintenance maintenance TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");
				$res1=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD maintenance_message TEXT NOT NULL");
	
				$res1=dbquery("SELECT comment_id,comment_name FROM ".$fusion_prefix."comments");
				while($d1=dbarray($res1)) {
					$comment_name = explode(".", $d1['comment_name']);
					if ($comment_name['0']!="0") { $new_name = $comment_name['0']; } else { $new_name = $comment_name['1']; }
					$res2=dbquery("UPDATE ".$fusion_prefix."comments SET comment_name='$new_name' WHERE comment_id='".$d1['comment_id']."'");
				}
				
				$res1=dbquery("SELECT shout_id,shout_name FROM ".$fusion_prefix."shoutbox");
				while($d1=dbarray($res1)) {
					$shout_name = explode(".", $d1['shout_name']);
					if ($shout_name['0']!="0") { $new_name = $shout_name['0']; } else { $new_name = $shout_name['1']; }
					$res2=dbquery("UPDATE ".$fusion_prefix."shoutbox SET shout_name='$new_name' WHERE shout_id='".$d1['shout_id']."'");
				}

				$res1=dbquery("UPDATE ".$fusion_prefix."users SET user_theme='Default'");
				$res1=dbquery("UPDATE ".$fusion_prefix."settings SET theme='Tropicano'");
				$res1=dbquery("UPDATE ".$fusion_prefix."settings SET version='401'");
				
				echo "<center><br>\nUpgrade Successful.<br><br>\n</center>";
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

require "../footer.php";
?>