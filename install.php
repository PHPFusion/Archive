<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
$basedir = substr($_SERVER['PHP_SELF'], 0, -11);
define("fusion_root", $basedir);
require "includes/classes.php";
require "themes/innovated/theme.php";

if ($_POST['stage'] > 1) {
	require "language/".$_POST['site_language']."/install.php";
}
if ($_POST['stage'] > 4) {
	require "includes/config.php";
}

echo "<html>
<head>
<title>PHP-Fusion Installation</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<link rel=\"stylesheet\" href=\"themes/innovated/styles.css\" type=\"text/css\">
</head>
<body bgcolor=\"#666666\" leftmargin=\"0\" topmargin=\"0\" text=\"#000000\">

<br><br><br><br>

<table align=\"center\" width=\"500\" cellspacing=\"0\" cellpadding=\"0\" style=\"border:1px #000 solid;\">
<tr>
<td>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td align=\"center\" class=\"full-header\"><img src=\"images/fusion_install.gif\"></td>
</tr>
</table>

<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"bodybg\">
<tr>
<td>\n";

tablebreak();

echo "<table align=\"center\" width=\"98%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td>\n";

if (!$_POST['stage']) {
	$handle=opendir("language");
	while ($file = readdir($handle)){
		if($file != "." && $file != ".." && $file != "/" && $file != "index.php") {
			if ($file == $settings[language]) { $sel = " selected"; } else { $sel = ""; }
			$langlist .= "<option$sel>$file</option>\n";
		}
	}
	closedir($handle);
	opentable("Select Language");
	echo "<form name=\"install\" method=\"post\" action=\"$PHP_SELF\">
<center><br>
Please choose the installation language you wish to use<br>
<br>
<select name=\"site_language\" class=\"textbox\" style=\"width:150px\">
$langlist</select>
<input type=\"hidden\" name=\"stage\" value=\"2\">
<input type=\"submit\" name=\"next\" value=\"Next\" class=\"button\" style=\"width:50px\"></center>
</form>\n";
	closetable();
}

if ($_POST['stage'] == "2") {
	opentable(LAN_210);
	echo "<form name=\"install\" method=\"post\" action=\"$PHP_SELF\">
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"forum-border\">
<tr>
<td>
<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
<tr>
<td width=\"33%\" class=\"forum1\">".LAN_211."</td>
<td align=\"center\" width=\"33%\" class=\"forum2\">".phpversion()."</td>
<td align=\"right\" width=\"33%\" class=\"forum1\">";
	$phpver = str_replace(".","",phpversion());
	if ($phpver < 410) { echo LAN_214; } else { echo LAN_215; }
	echo "</td>
</tr>
<tr>
<td colspan=\"3\" class=\"forum2\">".LAN_212."</td>
</tr>
<tr>
<td width=\"33%\" class=\"forum1\">attachments</td>
<td width=\"33%\" class=\"forum2\">&nbsp</td>
<td align=\"right\" width=\"33%\" class=\"forum1\">";
	if (!is_writable("attachments")) { echo LAN_214; } else { echo LAN_215; }
	echo "</td>
</tr>
<tr>
<td width=\"33%\" class=\"forum1\">avatars</td>
<td width=\"33%\" class=\"forum2\">&nbsp</td>
<td align=\"right\" width=\"33%\" class=\"forum1\">";
	if (!is_writable("avatars")) { echo LAN_214; } else { echo LAN_215; }
	echo "</td>
</tr>
<tr>
<td width=\"33%\" class=\"forum1\">images</td>
<td width=\"33%\" class=\"forum2\">&nbsp</td>
<td align=\"right\" width=\"33%\" class=\"forum1\">";
	if (!is_writable("images")) { echo LAN_214; } else { echo LAN_215; }
	echo "</td>
</tr>
<tr>
<td width=\"33%\" class=\"forum1\">includes</td>
<td width=\"33%\" class=\"forum2\">&nbsp</td>
<td align=\"right\" width=\"33%\" class=\"forum1\">";
	if (!is_writable("includes")) { echo LAN_214; } else { echo LAN_215; }
	echo "</td>
</tr>
<tr>
<td colspan=\"3\" class=\"forum2\">".LAN_213."</td>
</tr>
<tr>
<td width=\"33%\" class=\"forum1\">includes/config.php</td>
<td width=\"33%\" class=\"forum2\">&nbsp</td>
<td align=\"right\" width=\"33%\" class=\"forum1\">";
	if (!is_writable("includes/config.php")) { echo LAN_214; } else { echo LAN_215; }
	echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";
	echo "<center><br>\n";
	if ($phpver < 410) {
		echo LAN_216."<br><br>\n";
	}
	echo LAN_217."<br><br>
<input type=\"hidden\" name=\"site_language\" value=\"".$_POST['site_language']."\">
<input type=\"hidden\" name=\"stage\" value=\"3\">
<input type=\"submit\" name=\"next\" value=\"".LAN_200."\" class=\"button\" style=\"width:50px\">
</center>
</form>\n";
	closetable();
}

if ($_POST['stage'] == "3") {
	opentable(LAN_230);
	echo "<center>".LAN_231."</center><br>
<table width=\"100%\">
<form name=\"install\" method=\"post\" action=\"$PHP_SELF?stage=3\">
<tr>
<td width=\"130\">".LAN_232."</td>
<td><input type=\"text\" name=\"dbhost\" class=\"textbox\" value=\"localhost\" style=\"width: 150px\"></td>
</tr>
<tr>
<td width=\"130\">".LAN_233."</td>
<td><input type=\"text\" name=\"dbusername\" class=\"textbox\" style=\"width: 150px\"></td>
</tr>
<tr>
<td width=\"130\">".LAN_234."</td>
<td><input type=\"text\" name=\"dbpassword\" class=\"textbox\" style=\"width: 150px\"></td>
</tr>
<tr>
<td width=\"130\">".LAN_235."</td>
<td><input type=\"text\" name=\"dbname\" class=\"textbox\" style=\"width: 150px\"></td>
</tr>
<tr>
<td width=\"130\">".LAN_236."</td>
<td><input type=\"text\" name=\"fusion_prefix\" class=\"textbox\" value=\"fusion_\" maxlength=\"20\" style=\"width: 150px\"></td>
</tr>
</table>
<center><br>
<input type=\"hidden\" name=\"site_language\" value=\"".$_POST['site_language']."\">
<input type=\"hidden\" name=\"stage\" value=\"4\">
<input type=\"reset\" name=\"reset\" value=\"".LAN_201."\" class=\"button\" style=\"width:50px\">
<input type=\"submit\" name=\"next\" value=\"".LAN_200."\" class=\"button\" style=\"width:50px\">
</center>
</form>\n";
	closetable();
}

if ($_POST['stage'] == "4") {
	$config = chr(60)."?\n// database settings\n";
	$config .= chr(36)."dbhost=".chr(34).$dbhost.chr(34).";\n";
	$config .= chr(36)."dbusername=".chr(34).$dbusername.chr(34).";\n";
	$config .= chr(36)."dbpassword=".chr(34).$dbpassword.chr(34).";\n";
	$config .= chr(36)."dbname=".chr(34).$dbname.chr(34).";\n";
	$config .= chr(36)."fusion_prefix=".chr(34).$fusion_prefix.chr(34).";\n";
	$config .= "\n";
	$config .= "define(".chr(34)."fusion_root".chr(34).", ".chr(34).$basedir.chr(34).");\n";
	$config .= "?".chr(62);
	$fp = fopen("includes/config.php","w");
	if (fwrite($fp, $config)) {
		opentable(LAN_250);
		echo "<form name=\"install\" method=\"post\" action=\"$PHP_SELF?stage=4\">
<center><br>
".LAN_251."<br><br>
<input type=\"hidden\" name=\"site_language\" value=\"".$_POST['site_language']."\">
<input type=\"hidden\" name=\"stage\" value=\"5\">
<input type=\"submit\" name=\"next\" value=\"Next\" class=\"button\" style=\"width:50px\">
</center>
</form>\n";
		closetable();
	} else {
		opentable(LAN_252);
		echo "<center><br>
".LAN_251."
</center><br>\n";
		closetable();
	}
	fclose($fp);
}

if ($_POST['stage'] == "5") {
	if (mysql_connect($dbhost, $dbusername, $dbpassword)) {
		if (mysql_select_db($dbname)) {
			opentable(LAN_270);
			echo "<form name=\"install\" method=\"post\" action=\"$PHP_SELF?stage=5\">
<center>".LAN_271."<br><br>\n";
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."articles");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."articles (
			article_id smallint(5) unsigned NOT NULL auto_increment,
			article_cat smallint(5) unsigned NOT NULL default '0',
			article_subject varchar(200) NOT NULL default '',
			article_snippet text NOT NULL,
			article_article text NOT NULL,
			article_breaks char(1) NOT NULL default '',
			article_comments smallint(5) unsigned NOT NULL default '0',
			article_name varchar(30) NOT NULL default '',
			article_email varchar(100) NOT NULL default '',
			article_datestamp int(10) unsigned NOT NULL default '0',
			article_reads smallint(5) unsigned NOT NULL default '0',
			PRIMARY KEY (article_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."articles".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."articles<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."article_cats");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."article_cats (
			article_cat_id smallint(5) unsigned NOT NULL auto_increment,
			article_cat_name varchar(100) NOT NULL default '',
			article_cat_description varchar(200) NOT NULL default '',
			PRIMARY KEY (article_cat_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."article_cats".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."article_cats<br>\n";
			}

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."comments");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."comments (
			comment_id smallint(5) unsigned NOT NULL auto_increment,
			article_id smallint(5) unsigned NOT NULL default '0',
			news_id smallint(5) unsigned NOT NULL default '0',
			comment_name varchar(50) NOT NULL default '',
			comment_message text NOT NULL,
			comment_datestamp int(10) unsigned NOT NULL default '0',
			comment_ip varchar(20) NOT NULL default '0.0.0.0',
			PRIMARY KEY (comment_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."comments".LAN_273."<br>\n";
			} else {
				echo "Error: Unable to create table comments<br>\n";
			}

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."download_cats");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."download_cats (
			download_cat_id smallint(5) unsigned NOT NULL auto_increment,
			download_cat_name varchar(100) NOT NULL default '',
			download_cat_description varchar(250) NOT NULL default '',
			PRIMARY KEY (download_cat_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."download_cats".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."download_cats<br>\n";
			}

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."downloads");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."downloads (
			download_id smallint(5) unsigned NOT NULL auto_increment,
			download_title varchar(100) NOT NULL default '',
			download_description varchar(200) NOT NULL default '',
			download_url varchar(200) NOT NULL default '',
			download_cat smallint(5) unsigned NOT NULL default '0',
			download_license varchar(50) NOT NULL default '',
			download_os varchar(50) NOT NULL default '',
			download_version varchar(20) NOT NULL default '',
			download_filesize varchar(20) NOT NULL default '',
			download_datestamp int(10) unsigned NOT NULL default '0',
			download_count smallint(5) unsigned NOT NULL default '0',
			PRIMARY KEY (download_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."downloads".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."downloads<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."forums");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."forums (
			forum_id smallint(5) unsigned NOT NULL auto_increment,
			forum_cat smallint(5) unsigned NOT NULL default '0',
			forum_name varchar(100) NOT NULL default '',
			forum_order smallint(5) unsigned NOT NULL default '0',
			forum_description text NOT NULL,
			forum_threads smallint(5) unsigned NOT NULL default '0',
			forum_posts smallint(5) unsigned NOT NULL default '0',
			forum_lastpost int(10) unsigned NOT NULL default '0',
			forum_lastuser varchar(50) NOT NULL default '',
			PRIMARY KEY (forum_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."forums".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."forums<br>\n";
			}

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."forum_attachments");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."forum_attachments (
			attach_id smallint(5) unsigned NOT NULL auto_increment,
			thread_id smallint(5) unsigned NOT NULL default '0',
			post_id smallint(5) unsigned NOT NULL default '0',
			attach_name varchar(100) NOT NULL default '',
			attach_ext varchar(5) NOT NULL default '',
			attach_size int(20) unsigned NOT NULL default '0',
			PRIMARY KEY (attach_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."forum_attachments".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."forum_attachments<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."messages");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."messages (
			message_id smallint(5) unsigned NOT NULL auto_increment,
			message_to varchar(50) NOT NULL default '',
			message_from varchar(50) NOT NULL default '',
			message_subject varchar(100) NOT NULL default '',
			message_message text NOT NULL,
			message_read tinyint(1) unsigned NOT NULL default '0',
			message_datestamp int(10) unsigned NOT NULL default '0',
			PRIMARY KEY (message_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."messages".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."messages<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."news");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."news (
			news_id smallint(5) unsigned NOT NULL auto_increment,
			news_subject varchar(200) NOT NULL default '',
			news_news text NOT NULL,
			news_extended text NOT NULL,
			news_name varchar(30) NOT NULL default '',
			news_email varchar(100) NOT NULL default '',
			news_datestamp int(10) unsigned NOT NULL default '0',
			news_comments smallint(5) unsigned NOT NULL default '0',
			news_reads smallint(5) unsigned NOT NULL default '0',
			PRIMARY KEY (news_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."news".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."news<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."online");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."online (
			online_id smallint(5) unsigned NOT NULL auto_increment,
			online_user varchar(50) NOT NULL default '',
			online_ip varchar(20) NOT NULL default '',
			online_lastactive int(10) unsigned NOT NULL default '0',
			PRIMARY KEY (online_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."online".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."online<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."votes");
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."poll_votes");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."poll_votes (
			vote_id smallint(5) unsigned NOT NULL auto_increment,
			vote_user varchar(50) NOT NULL default '',
			vote_opt smallint(2) unsigned NOT NULL default '0',
			poll_id smallint(5) unsigned NOT NULL default '0',
			PRIMARY KEY (vote_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."poll_votes".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."poll_votes<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."polls");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."polls (
			poll_id smallint(5) unsigned NOT NULL auto_increment,
			poll_title varchar(200) NOT NULL default '',
			poll_optcount smallint(2) unsigned NOT NULL default '0',
			poll_opt_0 varchar(200) NOT NULL default '',
			poll_opt_1 varchar(200) NOT NULL default '',
			poll_opt_2 varchar(200) NOT NULL default '',
			poll_opt_3 varchar(200) NOT NULL default '',
			poll_opt_4 varchar(200) NOT NULL default '',
			poll_opt_5 varchar(200) NOT NULL default '',
			poll_opt_6 varchar(200) NOT NULL default '',
			poll_opt_7 varchar(200) NOT NULL default '',
			poll_opt_8 varchar(200) NOT NULL default '',
			poll_opt_9 varchar(200) NOT NULL default '',
			poll_votes smallint(6) unsigned NOT NULL default '0',
			poll_started int(10) unsigned NOT NULL default '',
			poll_ended int(10) unsigned NOT NULL default '0',
			PRIMARY KEY (poll_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."polls".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."polls<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."posts");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."posts (
			forum_id smallint(5) unsigned NOT NULL default '0',
			thread_id smallint(5) unsigned NOT NULL default '0',
			post_id smallint(5) unsigned NOT NULL auto_increment,
			post_subject varchar(100) NOT NULL default '',
			post_message text NOT NULL,
			post_showsig char(1) NOT NULL default '',
			post_smileys char(1) NOT NULL default '',
			post_author varchar(50) NOT NULL default '',
			post_datestamp int(10) unsigned NOT NULL default '0',
			post_edituser varchar(50) NOT NULL default '',
			post_edittime int(10) unsigned NOT NULL default '',
			PRIMARY KEY (post_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."posts".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."posts<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."settings");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."settings (
			sitename varchar(200) NOT NULL default '',
			siteurl varchar(200) NOT NULL default '',
			sitebanner varchar(200) NOT NULL default '',
			siteemail varchar(100) NOT NULL default '',
			siteusername varchar(30) NOT NULL default '',
			siteintro text NOT NULL,
			description text NOT NULL,
			keywords text NOT NULL,
			footer text NOT NULL,
			language varchar(20) NOT NULL default 'English',
			theme varchar(100) NOT NULL default '',
			shortdate varchar(50) NOT NULL default '',
			longdate varchar(50) NOT NULL default '',
			timeoffset varchar(3) NOT NULL default '0',
			forumdate varchar(50) NOT NULL default '',
			forumpanels char(1) NOT NULL default 'B',
			numofthreads smallint(2) unsigned NOT NULL default '5',
			attachments tinyint(1) unsigned NOT NULL default '0',
			attachmax int(12) unsigned NOT NULL default '150000',
			attachtypes varchar(150) NOT NULL default '.gif,.jpg,.png,.zip,.rar,.tar',
			guestposts tinyint(1) unsigned NOT NULL default '0',
			displaypoll tinyint(1) unsigned NOT NULL default '0',
			numofshouts tinyint(2) unsigned NOT NULL default '10',
			counter smallint(5) unsigned NOT NULL default '0',
			version smallint(5) unsigned NOT NULL default '300'
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."settings".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."settings<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."shoutbox");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."shoutbox (
			shout_id smallint(5) unsigned NOT NULL auto_increment,
			shout_name varchar(50) NOT NULL default '',
			shout_message varchar(200) NOT NULL default '',
			shout_datestamp int(10) unsigned NOT NULL default '0',
			shout_ip varchar(20) NOT NULL default '',
			PRIMARY KEY (shout_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."shoutbox".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."shoutbox<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."sitelinks");
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."site_links");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."site_links (
			link_id smallint(5) unsigned NOT NULL auto_increment,
			link_name varchar(100) NOT NULL default '',
			link_url varchar(200) NOT NULL default '',
			link_order smallint(2) unsigned NOT NULL default '0',
			PRIMARY KEY (link_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."online successfully site_links<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."site_links<br>\n";
			}

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."linksubmits");
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."submitted_links");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."submitted_links (
			sublink_id smallint(5) unsigned NOT NULL auto_increment,
			sublink_sitename varchar(100) NOT NULL default '',
			sublink_description varchar(200) NOT NULL default '',
			sublink_url varchar(200) NOT NULL default '',
			sublink_category varchar(100) NOT NULL default '',
			sublink_name varchar(30) NOT NULL default '',
			sublink_email varchar(100) NOT NULL default '',
			sublink_datestamp int(10) unsigned NOT NULL default '0',
			sublink_ip varchar(20) NOT NULL default '',
			PRIMARY KEY(sublink_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."submitted_links".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."submitted_links<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."newssubmits");
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."submitted_news");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."submitted_news (
			subnews_id smallint(5) unsigned NOT NULL auto_increment,
			subnews_name varchar(30) NOT NULL default '',
			subnews_email varchar(100) NOT NULL default '',
			subnews_subject varchar(200) NOT NULL default '',
			subnews_news text NOT NULL,
			subnews_extended text NOT NULL,
			subnews_datestamp int(10) unsigned NOT NULL default '0',
			subnews_ip varchar(20) NOT NULL default '',
			PRIMARY KEY (subnews_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."submitted_news".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."submitted_news<br>\n";
			}

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."threads");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."threads (
			forum_id smallint(5) unsigned NOT NULL default '0',
			thread_id smallint(5) unsigned NOT NULL auto_increment,
			thread_subject varchar(100) NOT NULL default '',
			thread_author varchar(50) NOT NULL default '',
			thread_views smallint(5) unsigned NOT NULL default '0',
			thread_replies smallint(5) unsigned NOT NULL default '0',
			thread_lastpost int(10) unsigned NOT NULL default '0',
			thread_lastuser varchar(50) NOT NULL default '',
			thread_sticky char(1) NOT NULL default '',
			thread_locked char(1) NOT NULL default '',
			PRIMARY KEY (thread_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."threads".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."threads<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."users");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."users (
			user_id smallint(5) unsigned NOT NULL auto_increment,
			user_name varchar(30) NOT NULL default '',
			user_password varchar(32) NOT NULL default '',
			user_email varchar(100) NOT NULL default '',
			user_hide_email tinyint(1) unsigned NOT NULL default '1',
			user_location varchar(50) NOT NULL default '',
			user_icq varchar(15) NOT NULL default '',
			user_msn varchar(100) NOT NULL default '',
			user_yahoo varchar(100) NOT NULL default '',
			user_web varchar(200) NOT NULL default '',
			user_avatar varchar(100) NOT NULL default '',
			user_sig text NOT NULL,
			user_posts smallint(5) unsigned NOT NULL default '0',
			user_joined int(10) unsigned NOT NULL default '0',
			user_lastvisit int(10) unsigned NOT NULL default '0',
			user_mod tinyint(1) unsigned NULL default '4',
			user_ban tinyint(1) unsigned default '0',
			PRIMARY KEY (user_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."users".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."users<br>\n";
			}

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."weblink_cats");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."weblink_cats (
			weblink_cat_id smallint(5) unsigned NOT NULL auto_increment,
			weblink_cat_name varchar(100) NOT NULL default '',
			weblink_cat_description varchar(200) NOT NULL default '',
			PRIMARY KEY(weblink_cat_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."weblink_cats".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."weblink_cats<br>\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."weblinks");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."weblinks (
			weblink_id smallint(5) unsigned NOT NULL auto_increment,
			weblink_name varchar(100) NOT NULL default '',
			weblink_description varchar(200) NOT NULL default '',
			weblink_url varchar(200) NOT NULL default '',
			weblink_cat smallint(5) unsigned NOT NULL default '0',
			weblink_datestamp int(10) unsigned NOT NULL default '0',
			weblink_count smallint(5) unsigned NOT NULL default '0',
			PRIMARY KEY(weblink_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_272.$fusion_prefix."weblinks".LAN_273."<br>\n";
			} else {
				echo LAN_274.$fusion_prefix."weblinks<br>\n";
			}
			
			echo "<br>
<input type=\"hidden\" name=\"site_language\" value=\"".$_POST['site_language']."\">
<input type=\"hidden\" name=\"stage\" value=\"6\">
<input type=\"submit\" name=\"Next\" value=\"".LAN_200."\" class=\"button\">
</center>
</form>";
			closetable();
		} else {
			opentable(LAN_270);
			echo LAN_275."\n";
			closetable();
		}
	} else {
		opentable(LAN_270);
		echo LAN_276."\n";
		closetable();
	}
}

if ($_POST['stage'] == "6") {
	opentable(LAN_290);
	echo "<form name=\"install\" method=\"post\" action=\"$PHP_SELF?stage=6\">
<center>".LAN_291."</center><br>
<table width=\"100%\">
<tr>
<td colspan=\"2\" height=\"10\"></td>
</tr>
<tr>
<td width=\"130\">".LAN_292."</td>
<td><input type=\"text\" name=\"username\" class=\"textbox\" maxlength=\"30\"></td>
</tr>
<tr>
<td width=\"130\">".LAN_293."</td>
<td><input type=\"password\" name=\"password1\" class=\"textbox\" maxlength=\"30\"></td>
</tr>
<tr>
<td width=\"130\">".LAN_294."</td>
<td><input type=\"password\" name=\"password2\" class=\"textbox\" maxlength=\"30\"></td>
</tr>
<tr>
<td width=\"130\">".LAN_295."</td>
<td><input type=\"text\" name=\"email\" class=\"textbox\" maxlength=\"100\"></td>
</tr>
</table>
<center><br>
<input type=\"hidden\" name=\"site_language\" value=\"".$_POST['site_language']."\">
<input type=\"hidden\" name=\"stage\" value=\"7\">
<input type=\"reset\" name=\"reset\" value=\"".LAN_201."\" class=\"button\">
<input type=\"submit\" name=\"next\" value=\"".LAN_200."\" class=\"button\"></center>
</form>\n";
	closetable();
}

if ($_POST['stage'] == "7") {
	$username = stripinput($_POST['username']);
	$password1 = stripinput($_POST['password1']);
	$password2 = stripinput($_POST['password2']);
	$email = stripinput($_POST['email']);
	if ($username == "") {
		$error = LAN_320."<br><br>\n";
	}
	if ($password1 != "") {
		if ($password1 != $password2) {
			$error .= LAN_321."<br><br>\n";
		}
	} else {
		$error .= LAN_322."<br><br>\n";
	}
	if ($email == "") {
		$error .= LAN_323."<br><br>\n";
	}
	if ($error == "") {
		mysql_connect($dbhost, $dbusername, $dbpassword);
		mysql_select_db($dbname);
		$result = dbquery("INSERT INTO ".$fusion_prefix."users VALUES('', '$username', md5('$password1'), '$email', '1', '', '', '',
		'', '', '', '', '0', '".time()."', '0', '3', '0')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."settings VALUES('PHP-Fusion Powered Website', 'http://yourdomain.co.uk',
		'images/banner.gif', 'you@yourdomain.co.uk', '$username', '<center>Welcome to your site</center>', '', '', 
		'<center>Copyright © 2003</center>', '".$_POST['site_language']."', 'innovated', '%d.%m.%y %H:%M', '%B %d %Y - %H:%M:%S', '0', '%d-%m-%y %H:%M',
		'B', '5', '0', '150000', '.gif,.jpg,.png,.zip,.rar,.tar', '0', '0', '10', '0', '304')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_340."', 'index.php', '1')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_341."', 'articles.php', '2')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_342."', 'downloads.php', '3')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_343."', 'forum/index.php', '4')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_344."', 'contact.php', '5')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_345."', 'weblinks.php', '6')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_346."', 'submitlink.php', '7')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_347."', 'submitnews.php', '8')");
		opentable(LAN_360);
		echo "<form name=\"install\" method=\"post\" action=\"index.php\">
<center><br>
".LAN_361."<br><br>
<input type=\"submit\" name=\"next\" value=\"".LAN_200."\" class=\"button\">
</center><br>
</form>\n";
		closetable();
	} else {
		opentable(LAN_362);
		echo "<center><br>
".LAN_363."<br><br>
$error<br>
".LAN_364."
</center><br>";
		closetable();
	}
}

tablebreak();
echo "</td>
</tr>
</table>
</td>
</tr>
</table>

<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td align=\"center\" class=\"full-header\">
<br>Powered by PHP-Fusion v3.04 Copyright © 2003-2004 <a href=\"http://www.digitaldominion.co.uk\" class=\"foot\">Digital Dominion</a><br><br>
</td>
</tr>
</table>

</body>
</html>\n";
?>