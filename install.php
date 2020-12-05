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
$basedir = substr($_SERVER['PHP_SELF'], 0, -11);
define("fusion_root", $basedir);
require "fusion_core/classes.php";
require "fusion_themes/x3/theme.php";

if ($_POST['stage'] > 1) {
	require "fusion_languages/".$_POST['site_language']."/install.php";
}
if ($_POST['stage'] > 4) {
	require "fusion_config.php";
}

echo "<html>
<head>
<title>PHP-Fusion v4.00</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<link rel='stylesheet' href='fusion_themes/x3/styles.css' type='text/css'>
</head>
<body bgcolor='#666666' leftmargin='0' topmargin='0' text='#000000'>

<table height='100%' width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td>

<table align='center' width='500' cellspacing='0' cellpadding='0' style='border:1px #000 solid;'>
<tr>
<td>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td align='center' class='full-header'><img src='fusion_images/banner.gif'></td>
</tr>
</table>

<table width='100%' cellspacing='0' cellpadding='0' class='bodybg'>
<tr>
<td>\n";

tablebreak();

echo "<table align='center' width='98%' cellspacing='0' cellpadding='0'>
<tr>
<td>\n";

if (!$_POST['stage']) {
	$handle=opendir("fusion_languages");
	while ($folder = readdir($handle)){
		if ($folder != "." && $folder != ".." && $folder != "/" && $folder != "index.php") {
			$lang_list[] = $folder;
		}
	}
	closedir($handle);
	sort($lang_list);
	opentable("Select Language");
	echo "<form name='install' method='post' action='$PHP_SELF'>
<center><br />
Please choose the installation language you wish to use<br />
<br />
<select name=\"site_language\" class=\"textbox\" style=\"width:150px;\">\n";
	for ($count=0;$lang_list[$count]!="";$count++) {
		if ($lang_list[$count] == "English") { $sel = " selected"; } else { $sel = ""; }
		echo "<option$sel>$lang_list[$count]</option>\n";
	}
echo "</select>
<input type='hidden' name='stage' value='2'>
<input type='submit' name='next' value='Next' class='button' style='width:50px'></center>
</form>\n";
	closetable();
}

if ($_POST['stage'] == "2") {
	opentable(LAN_410);
	echo "<form name='install' method='post' action='$PHP_SELF'>
<table border='0' cellpadding='0' cellspacing='0' width='100%' class='forum-border'>
<tr>
<td>
<table border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td width='33%' class='forum1'>".LAN_411."</td>
<td align='center' width='33%' class='forum2'>".phpversion()."</td>
<td align='right' width='33%' class='forum1'>";
	$phpver = str_replace(".","",phpversion());
	if ($phpver < 410) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
<tr>
<td colspan='3' class='forum2'>".LAN_412."</td>
</tr>
<tr>
<td width='33%' class='forum1'>fusion_public/attachments</td>
<td width='33%' class='forum2'>&nbsp</td>
<td align='right' width='33%' class='forum1'>";
	if (!is_writable("fusion_public/attachments")) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
<tr>
<td width='33%' class='forum1'>fusion_public/avatars</td>
<td width='33%' class='forum2'>&nbsp</td>
<td align='right' width='33%' class='forum1'>";
	if (!is_writable("fusion_public/avatars")) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
<tr>
<td width='33%' class='forum1'>fusion_images</td>
<td width='33%' class='forum2'>&nbsp</td>
<td align='right' width='33%' class='forum1'>";
	if (!is_writable("fusion_images")) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
<tr>
<td width='33%' class='forum1'>fusion_pages</td>
<td width='33%' class='forum2'>&nbsp</td>
<td align='right' width='33%' class='forum1'>";
	if (!is_writable("fusion_pages")) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
<tr>
<td colspan='3' class='forum2'>".LAN_413."</td>
</tr>
<tr>
<td width='33%' class='forum1'>fusion_config.php</td>
<td width='33%' class='forum2'>&nbsp</td>
<td align='right' width='33%' class='forum1'>";
	if (!is_writable("fusion_config.php")) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";
	echo "<center><br />\n";
	if ($phpver < 410) {
		echo LAN_416."<br /><br />\n";
	}
	echo LAN_417."<br /><br />
<input type='hidden' name='site_language' value='".$_POST['site_language']."'>
<input type='hidden' name='stage' value='3'>
<input type='submit' name='next' value='".LAN_400."' class='button' style='width:50px'>
</center>
</form>\n";
	closetable();
}

if ($_POST['stage'] == "3") {
	opentable(LAN_430);
	echo "<center>".LAN_431."</center><br />
<table width='100%'>
<form name='install' method='post' action='$PHP_SELF?stage=3'>
<tr>
<td width='130'>".LAN_432."</td>
<td><input type='text' name='dbhost' class='textbox' value='localhost' style='width: 150px'></td>
</tr>
<tr>
<td width='130'>".LAN_433."</td>
<td><input type='text' name='dbusername' class='textbox' style='width: 150px'></td>
</tr>
<tr>
<td width='130'>".LAN_434."</td>
<td><input type='text' name='dbpassword' class='textbox' style='width: 150px'></td>
</tr>
<tr>
<td width='130'>".LAN_435."</td>
<td><input type='text' name='dbname' class='textbox' style='width: 150px'></td>
</tr>
<tr>
<td width='130'>".LAN_436."</td>
<td><input type='text' name='fusion_prefix' class='textbox' value='fusion_' maxlength='20' style='width: 150px'></td>
</tr>
</table>
<center><br />
<input type='hidden' name='site_language' value='".$_POST['site_language']."'>
<input type='hidden' name='stage' value='4'>
<input type='reset' name='reset' value='".LAN_401."' class='button' style='width:50px'>
<input type='submit' name='next' value='".LAN_400."' class='button' style='width:50px'>
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
	$fp = fopen("fusion_config.php","w");
	if (fwrite($fp, $config)) {
		opentable(LAN_450);
		echo "<form name='install' method='post' action='$PHP_SELF?stage=4'>
<center><br />
".LAN_451."<br /><br />
<input type='hidden' name='site_language' value='".$_POST['site_language']."'>
<input type='hidden' name='stage' value='5'>
<input type='submit' name='next' value='Next' class='button' style='width:50px'>
</center>
</form>\n";
		closetable();
	} else {
		opentable(LAN_452);
		echo "<center><br />
".LAN_453."
</center><br />\n";
		closetable();
	}
	fclose($fp);
}

if ($_POST['stage'] == "5") {
	if (mysql_connect($dbhost, $dbusername, $dbpassword)) {
		if (mysql_select_db($dbname)) {
			opentable(LAN_470);
			echo "<form name='install' method='post' action='$PHP_SELF?stage=5'>
<center>".LAN_471."<br /><br />\n";
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
				echo LAN_472.$fusion_prefix."articles".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."articles<br />\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."article_cats");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."article_cats (
			article_cat_id smallint(5) unsigned NOT NULL auto_increment,
			article_cat_name varchar(100) NOT NULL default '',
			article_cat_description varchar(200) NOT NULL default '',
			PRIMARY KEY (article_cat_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."article_cats".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."article_cats<br />\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."comments");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."comments (
			comment_id smallint(5) unsigned NOT NULL auto_increment,
			comment_item_id smallint(5) unsigned NOT NULL default '0',
			comment_type char(1) NOT NULL default '',
			comment_name varchar(50) NOT NULL default '',
			comment_message text NOT NULL,
			comment_datestamp int(10) unsigned NOT NULL default '0',
			comment_ip varchar(20) NOT NULL default '0.0.0.0',
			PRIMARY KEY (comment_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."comments".LAN_473."<br />\n";
			} else {
				echo "Error: Unable to create table comments<br />\n";
			}

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."custom_pages");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."custom_pages (
			page_id smallint(5) NOT NULL auto_increment,
			page_title varchar(200) NOT NULL default '',
			page_access tinyint(1) unsigned NOT NULL default '0',
			PRIMARY KEY (page_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."custom_pages".LAN_473."<br />\n";
			} else {
				echo "Error: Unable to create table custom_pages<br />\n";
			}

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."download_cats");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."download_cats (
			download_cat_id smallint(5) unsigned NOT NULL auto_increment,
			download_cat_name varchar(100) NOT NULL default '',
			download_cat_description varchar(250) NOT NULL default '',
			PRIMARY KEY (download_cat_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."download_cats".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."download_cats<br />\n";
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
				echo LAN_472.$fusion_prefix."downloads".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."downloads<br />\n";
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
				echo LAN_472.$fusion_prefix."forum_attachments".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."forum_attachments<br />\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."forums");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."forums (
			forum_id smallint(5) unsigned NOT NULL auto_increment,
			forum_cat smallint(5) unsigned NOT NULL default '0',
			forum_name varchar(100) NOT NULL default '',
			forum_order smallint(5) unsigned NOT NULL default '0',
			forum_description text NOT NULL,
			forum_access tinyint(1) unsigned NOT NULL default '0',
			forum_threads smallint(5) unsigned NOT NULL default '0',
			forum_posts smallint(5) unsigned NOT NULL default '0',
			forum_lastpost int(10) unsigned NOT NULL default '0',
			forum_lastuser smallint(5) unsigned NOT NULL default '0',
			PRIMARY KEY (forum_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."forums".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."forums<br />\n";
			}
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."guestbook");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."guestbook (
			guestbook_id smallint(5) unsigned NOT NULL auto_increment,
			guestbook_name varchar(50) NOT NULL default '',
			guestbook_email varchar(100) NOT NULL default '',
			guestbook_weburl varchar(200) NOT NULL default '',
			guestbook_webtitle varchar(50) NOT NULL default '',
			guestbook_message text NOT NULL,
			guestbook_datestamp int(10) unsigned NOT NULL default '0',
			PRIMARY KEY (guestbook_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."guestbook".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."guestbook<br />\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."messages");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."messages (
			message_id smallint(5) unsigned NOT NULL auto_increment,
			message_to smallint(5) unsigned NOT NULL default '0',
			message_from smallint(5) unsigned NOT NULL default '0',
			message_subject varchar(100) NOT NULL default '',
			message_message text NOT NULL,
			message_smileys char(1) NOT NULL default '',
			message_read tinyint(1) unsigned NOT NULL default '0',
			message_datestamp int(10) unsigned NOT NULL default '0',
			PRIMARY KEY (message_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."messages".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."messages<br />\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."news");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."news (
			news_id smallint(5) unsigned NOT NULL auto_increment,
			news_subject varchar(200) NOT NULL default '',
			news_news text NOT NULL,
			news_extended text NOT NULL,
			news_breaks char(1) NOT NULL default '',
			news_name varchar(30) NOT NULL default '',
			news_email varchar(100) NOT NULL default '',
			news_datestamp int(10) unsigned NOT NULL default '0',
			news_comments smallint(5) unsigned NOT NULL default '0',
			news_reads smallint(5) unsigned NOT NULL default '0',
			PRIMARY KEY (news_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."news".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."news<br />\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."online");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."online (
			online_user varchar(50) NOT NULL default '',
			online_ip varchar(20) NOT NULL default '',
			online_lastactive int(10) unsigned NOT NULL default '0'
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."online".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."online<br />\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."panels");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."panels (
			panel_id smallint(5) unsigned NOT NULL auto_increment,
			panel_name varchar(100) NOT NULL default '',
			panel_filename varchar(100) NOT NULL default '',
			panel_content text NOT NULL,
			panel_side char(1) NOT NULL default 'l',
			panel_order smallint(5) unsigned NOT NULL default '0',
			panel_type varchar(20) NOT NULL default '',
			panel_access tinyint(1) unsigned NOT NULL default '0',
			panel_status tinyint(1) unsigned NOT NULL default '0',
			PRIMARY KEY (panel_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."panels".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."panels<br />\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."votes");
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."poll_votes");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."poll_votes (
			vote_id smallint(5) unsigned NOT NULL auto_increment,
			vote_user smallint(5) unsigned NOT NULL default '0',
			vote_opt smallint(2) unsigned NOT NULL default '0',
			poll_id smallint(5) unsigned NOT NULL default '0',
			PRIMARY KEY (vote_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."poll_votes".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."poll_votes<br />\n";
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
				echo LAN_472.$fusion_prefix."polls".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."polls<br />\n";
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
			post_author smallint(5) unsigned NOT NULL default '0',
			post_datestamp int(10) unsigned NOT NULL default '0',
			post_edituser smallint(5) unsigned NOT NULL default '0',
			post_edittime int(10) unsigned NOT NULL default '',
			PRIMARY KEY (post_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."posts".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."posts<br />\n";
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
			forumpanel tinyint(1) unsigned NOT NULL default '1',
			numofthreads smallint(2) unsigned NOT NULL default '5',
			attachments tinyint(1) unsigned NOT NULL default '0',
			attachmax int(12) unsigned NOT NULL default '150000',
			attachtypes varchar(150) NOT NULL default '.gif,.jpg,.png,.zip,.rar,.tar',
			guestposts tinyint(1) unsigned NOT NULL default '0',
			numofshouts tinyint(2) unsigned NOT NULL default '10',
			counter smallint(5) unsigned NOT NULL default '0',
			version smallint(5) unsigned NOT NULL default '400',
			maintenance char(3) NOT NULL default 'off'
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."settings".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."settings<br />\n";
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
				echo LAN_472.$fusion_prefix."shoutbox".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."shoutbox<br />\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."site_links");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."site_links (
			link_id smallint(5) unsigned NOT NULL auto_increment,
			link_name varchar(100) NOT NULL default '',
			link_url varchar(200) NOT NULL default '',
			link_order smallint(2) unsigned NOT NULL default '0',
			PRIMARY KEY (link_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."online successfully site_links<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."site_links<br />\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."submitted_articles");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."submitted_articles (
			sub_article_id smallint(5) unsigned NOT NULL auto_increment,
			sub_article_name varchar(30) NOT NULL default '',
			sub_article_email varchar(100) NOT NULL default '',
			sub_article_cat smallint(5) unsigned NOT NULL default '0',
			sub_article_subject varchar(200) NOT NULL default '',
			sub_article_description text NOT NULL,
			sub_article_body text NOT NULL,
			sub_article_breaks char(1) NOT NULL default '',
			sub_article_datestamp int(10) unsigned NOT NULL default '0',
			sub_article_ip varchar(20) NOT NULL default '',
			PRIMARY KEY (sub_article_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."submitted_articles".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."submitted_articles<br />\n";
			}

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
				echo LAN_472.$fusion_prefix."submitted_links".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."submitted_links<br />\n";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."submitted_news");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."submitted_news (
			subnews_id smallint(5) unsigned NOT NULL auto_increment,
			subnews_name varchar(30) NOT NULL default '',
			subnews_email varchar(100) NOT NULL default '',
			subnews_subject varchar(200) NOT NULL default '',
			subnews_news text NOT NULL,
			subnews_extended text NOT NULL,
			subnews_breaks char(1) NOT NULL default '',
			subnews_datestamp int(10) unsigned NOT NULL default '0',
			subnews_ip varchar(20) NOT NULL default '',
			PRIMARY KEY (subnews_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."submitted_news".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."submitted_news<br />\n";
			}

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."temp");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."temp (
			temp_id smallint(5) unsigned NOT NULL auto_increment,
			temp_time int(10) unsigned NOT NULL default '0',
			temp_enc varchar(32) NOT NULL default '',
			temp_dec int(10) unsigned NOT NULL default '0',
			PRIMARY KEY (temp_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."temp".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."temp<br />\n";
			}

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."threads");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."threads (
			forum_id smallint(5) unsigned NOT NULL default '0',
			thread_id smallint(5) unsigned NOT NULL auto_increment,
			thread_subject varchar(100) NOT NULL default '',
			thread_author smallint(5) unsigned NOT NULL default '0',
			thread_views smallint(5) unsigned NOT NULL default '0',
			thread_replies smallint(5) unsigned NOT NULL default '0',
			thread_lastpost int(10) unsigned NOT NULL default '0',
			thread_lastuser smallint(5) unsigned NOT NULL default '0',
			thread_sticky char(1) NOT NULL default '',
			thread_locked char(1) NOT NULL default '',
			PRIMARY KEY (thread_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."threads".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."threads<br />\n";
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
			user_theme varchar(100) NOT NULL default 'Default',
			user_offset char(3) NOT NULL default '0',
			user_avatar varchar(100) NOT NULL default '',
			user_sig text NOT NULL,
			user_posts smallint(5) unsigned NOT NULL default '0',
			user_joined int(10) unsigned NOT NULL default '0',
			user_lastvisit int(10) unsigned NOT NULL default '0',
			user_mod tinyint(1) unsigned NOT NULL default '0',
			user_ban tinyint(1) unsigned NOT NULL default '0',
			PRIMARY KEY (user_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."users".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."users<br />\n";
			}

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."weblink_cats");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."weblink_cats (
			weblink_cat_id smallint(5) unsigned NOT NULL auto_increment,
			weblink_cat_name varchar(100) NOT NULL default '',
			weblink_cat_description varchar(200) NOT NULL default '',
			PRIMARY KEY(weblink_cat_id)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo LAN_472.$fusion_prefix."weblink_cats".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."weblink_cats<br />\n";
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
				echo LAN_472.$fusion_prefix."weblinks".LAN_473."<br />\n";
			} else {
				echo LAN_474.$fusion_prefix."weblinks<br />\n";
			}
			
			echo "<br />
<input type='hidden' name='site_language' value='".$_POST['site_language']."'>
<input type='hidden' name='stage' value='6'>
<input type='submit' name='Next' value='".LAN_400."' class='button'>
</center>
</form>";
			closetable();
		} else {
			opentable(LAN_470);
			echo LAN_475."\n";
			closetable();
		}
	} else {
		opentable(LAN_470);
		echo LAN_476."\n";
		closetable();
	}
}

if ($_POST['stage'] == "6") {
	opentable(LAN_490);
	echo "<form name='install' method='post' action='$PHP_SELF?stage=6'>
<center>".LAN_491."</center><br />
<table width='100%'>
<tr>
<td colspan='2' height='10'></td>
</tr>
<tr>
<td width='130'>".LAN_492."</td>
<td><input type='text' name='username' class='textbox' maxlength='30'></td>
</tr>
<tr>
<td width='130'>".LAN_493."</td>
<td><input type='password' name='password1' class='textbox' maxlength='30'></td>
</tr>
<tr>
<td width='130'>".LAN_494."</td>
<td><input type='password' name='password2' class='textbox' maxlength='30'></td>
</tr>
<tr>
<td width='130'>".LAN_495."</td>
<td><input type='text' name='email' class='textbox' maxlength='100'></td>
</tr>
</table>
<center><br />
<input type='hidden' name='site_language' value='".$_POST['site_language']."'>
<input type='hidden' name='stage' value='7'>
<input type='reset' name='reset' value='".LAN_401."' class='button'>
<input type='submit' name='next' value='".LAN_400."' class='button'></center>
</form>\n";
	closetable();
}

if ($_POST['stage'] == "7") {
	$username = stripinput($_POST['username']);
	$password1 = stripinput($_POST['password1']);
	$password2 = stripinput($_POST['password2']);
	$email = stripinput($_POST['email']);
	if ($username == "") {
		$error = LAN_520."<br /><br />\n";
	}
	if ($password1 != "") {
		if ($password1 != $password2) {
			$error .= LAN_521."<br /><br />\n";
		}
	} else {
		$error .= LAN_522."<br /><br />\n";
	}
	if ($email == "") {
		$error .= LAN_523."<br /><br />\n";
	}
	if ($error == "") {
		mysql_connect($dbhost, $dbusername, $dbpassword);
		mysql_select_db($dbname);
		$result = dbquery("INSERT INTO ".$fusion_prefix."users VALUES('', '$username', md5('$password1'), '$email', '1', '', '', '',
		'', '', 'Default', '0', '', '', '0', '".time()."', '0', '4', '0')");
		
		$result = dbquery("INSERT INTO ".$fusion_prefix."settings VALUES('PHP-Fusion Powered Website', 'http://yourdomain',
		'fusion_images/banner.gif', 'you@yourdomain', '$username', '<center>Welcome to your site</center>', '', '', 
		'<center>Copyright © 2004</center>', '".$_POST['site_language']."', 'x3', '%d.%m.%y %H:%M', '%B %d %Y - %H:%M:%S',
		'0', '%d-%m-%y %H:%M', 'B', '5', '0', '150000', '.gif,.jpg,.png,.zip,.rar,.tar', '0', '10', '0', '400', 'off')");
		
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_540."', 'index.php', '1')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_541."', 'articles.php', '2')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_542."', 'downloads.php', '3')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_543."', 'fusion_forum/index.php', '4')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_544."', 'contact.php', '5')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_548."', 'guestbook.php', '6')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_545."', 'weblinks.php', '7')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_546."', 'submit_news.php', '8')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_549."', 'submit_article.php', '9')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_547."', 'submit_link.php', '10')");
		
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES (1, '".LAN_560."', 'navigation.php', '', 'l', 1, 'file', 0, 1)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES (2, '".LAN_561."', 'users_online.php', '', 'l', 2, 'file', 0, 1)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES (3, '".LAN_562."', 'hottest_threads.php', '', 'l', 4, 'file', 0, 1)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES (4, '".LAN_563."', 'newest_threads.php', '', 'l', 3, 'file', 0, 1)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES (5, '".LAN_564."', 'latest_articles.php', '', 'l', 5, 'file', 0, 1)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES (6, '".LAN_565."', 'user_info.php', '', 'r', 1, 'file', 0, 1)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES (7, '".LAN_566."', 'member_poll.php', '', 'r', 2, 'file', 0, 1)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES (8, '".LAN_567."', 'shoutbox.php', '', 'r', 3, 'file', 0, 1)");
		opentable(LAN_600);
		echo "<form name='install' method='post' action='index.php'>
<center><br />
".LAN_601."<br /><br />
<input type='submit' name='next' value='".LAN_400."' class='button'>
</center><br />
</form>\n";
		closetable();
	} else {
		opentable(LAN_602);
		echo "<center><br />
".LAN_603."<br /><br />
$error<br />
".LAN_604."
</center><br />";
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

<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td align='center' class='full-header'>
<br />Powered by <a href='http://www.php-fusion.co.uk' class='foot'>PHP-Fusion</a> v4.00
© 2003-2004 <a href='mailto:nick@php-fusion.co.uk' class='foot'>Nick Jones</a><br /><br />
</td>
</tr>
</table>

</td>
</tr>
</table>

</body>
</html>\n";
?>