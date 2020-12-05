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
$basedir = substr($_SERVER['PHP_SELF'], 0, -11);
require "fusion_themes/Prometheus/theme.php";

// mySQL database functions
function dbquery($query) {
	if (!$query = mysql_query($query)) echo mysql_error();
	return $query;
}

function dbresult($query, $row) {
	if (!$query = mysql_result($query, $row)) echo mysql_error();
	return $query;
}

function dbrows($query) {
	if (!$query = mysql_num_rows($query)) echo mysql_error();
	return $query;
}

function dbarray($query) {
	if (!$query = mysql_fetch_assoc($query)) echo mysql_error();
	return $query;
}

function dbarraynum($query) {
	if (!$query = mysql_fetch_row($query)) echo mysql_error();
	return $query;
}

function dbconnect($dbhost, $dbusername, $dbpassword, $dbname) {
	mysql_connect($dbhost, $dbusername, $dbpassword) or die(LAN_476.$dbhost);
	mysql_select_db($dbname) or die(LAN_477.$dbname);
	return true;
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (ini_get('magic_quotes_gpc')) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

if ($_POST['stage'] > 1) {
	require "fusion_languages/".$_POST['site_language']."/install.php";
}
if ($_POST['stage'] > 4) {
	require "fusion_config.php";
	if (@dbconnect($dbhost, $dbusername, $dbpassword, $dbname)) { $dberror = ""; } else { $dberror = "1"; }
}

echo "<html>
<head>
<title>PHP-Fusion v5.00</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<link rel='stylesheet' href='fusion_themes/Prometheus/styles.css' type='text/css'>
</head>
<body bgcolor='#666666' text='#000000'>
<br><br><br>
<table align='center' width='500' cellspacing='0' cellpadding='0' style='border:1px #000 solid;'>
<tr>
<td>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td align='center' class='main-body' style='padding:5px;'><img src='fusion_images/banner.gif'></td>
</tr>
</table>

<table width='100%' cellspacing='0' cellpadding='0' class='main-body'>
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
<center><br>
Please choose the installation language you wish to use<br>
<br>
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
} else if ($_POST['stage'] == "2") {
	opentable(LAN_410);
	echo "<form name='install' method='post' action='$PHP_SELF'>
<table border='0' cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td width='33%' class='tbl1'>".LAN_411."</td>
<td align='center' width='33%' class='tbl2'>".phpversion()."</td>
<td align='right' width='33%' class='tbl1'>";
	$phpver = str_replace(".","",phpversion());
	if ($phpver < 410) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
<tr>
<td colspan='3' class='tbl2'>".LAN_412."</td>
</tr>
<tr>
<td width='33%' class='tbl1'>fusion_admin/db_backups</td>
<td width='33%' class='tbl2'>&nbsp</td>
<td align='right' width='33%' class='tbl1'>";
	if (!is_writable("fusion_admin/db_backups")) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
<tr>
<td width='33%' class='tbl1'>fusion_public/attachments</td>
<td width='33%' class='tbl2'>&nbsp</td>
<td align='right' width='33%' class='tbl1'>";
	if (!is_writable("fusion_public/attachments")) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
<tr>
<td width='33%' class='tbl1'>fusion_public/avatars</td>
<td width='33%' class='tbl2'>&nbsp</td>
<td align='right' width='33%' class='tbl1'>";
	if (!is_writable("fusion_public/avatars")) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
<tr>
<td width='33%' class='tbl1'>fusion_images</td>
<td width='33%' class='tbl2'>&nbsp</td>
<td align='right' width='33%' class='tbl1'>";
	if (!is_writable("fusion_images")) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
<tr>
<td width='33%' class='tbl1'>fusion_images/photoalbum</td>
<td width='33%' class='tbl2'>&nbsp</td>
<td align='right' width='33%' class='tbl1'>";
	if (!is_writable("fusion_images/photoalbum")) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
<tr>
<td width='33%' class='tbl1'>fusion_pages</td>
<td width='33%' class='tbl2'>&nbsp</td>
<td align='right' width='33%' class='tbl1'>";
	if (!is_writable("fusion_pages")) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
<tr>
<td colspan='3' class='tbl2'>".LAN_413."</td>
</tr>
<tr>
<td width='33%' class='tbl1'>fusion_config.php</td>
<td width='33%' class='tbl2'>&nbsp</td>
<td align='right' width='33%' class='tbl1'>";
	if (!is_writable("fusion_config.php")) { echo LAN_414; } else { echo LAN_415; }
	echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";
	echo "<center><br>\n";
	if ($phpver < 410) {
		echo LAN_416."<br><br>\n";
	}
	echo LAN_417."<br><br>
<input type='hidden' name='site_language' value='".$_POST['site_language']."'>
<input type='hidden' name='stage' value='3'>
<input type='submit' name='next' value='".LAN_400."' class='button' style='width:50px'>
</center>
</form>\n";
	closetable();
} else if ($_POST['stage'] == "3") {
	opentable(LAN_430);
	echo "<center>".LAN_431."</center><br>
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
<center><br>
<input type='hidden' name='site_language' value='".$_POST['site_language']."'>
<input type='hidden' name='stage' value='4'>
<input type='reset' name='reset' value='".LAN_401."' class='button' style='width:50px'>
<input type='submit' name='next' value='".LAN_400."' class='button' style='width:50px'>
</center>
</form>\n";
	closetable();
} else if ($_POST['stage'] == "4") {
	$config = chr(60)."?\n// database settings\n";
	$config .= chr(36)."dbhost=".chr(34).$_POST['dbhost'].chr(34).";\n";
	$config .= chr(36)."dbusername=".chr(34).$_POST['dbusername'].chr(34).";\n";
	$config .= chr(36)."dbpassword=".chr(34).$_POST['dbpassword'].chr(34).";\n";
	$config .= chr(36)."dbname=".chr(34).$_POST['dbname'].chr(34).";\n";
	$config .= chr(36)."fusion_prefix=".chr(34).$_POST['fusion_prefix'].chr(34).";\n\n";
	$config .= "define(".chr(34)."FUSION_PREFIX".chr(34).", ".chr(34).$_POST['fusion_prefix'].chr(34).");\n";
	$config .= "define(".chr(34)."FUSION_ROOT".chr(34).", ".chr(34).$basedir.chr(34).");\n";
	$config .= "?".chr(62);
	$fp = fopen("fusion_config.php","w");
	if (fwrite($fp, $config)) {
		opentable(LAN_450);
		echo "<form name='install' method='post' action='$PHP_SELF'>
<center><br>
".LAN_451."<br><br>
<input type='hidden' name='site_language' value='".$_POST['site_language']."'>
<input type='hidden' name='stage' value='5'>
<input type='submit' name='next' value='".LAN_400."' class='button' style='width:50px'>
</center>
</form>\n";
		closetable();
	} else {
		opentable(LAN_452);
		echo "<center><br>
".LAN_453."
</center><br>\n";
		closetable();
	}
	fclose($fp);
} else if ($_POST['stage'] == "5") {
	if (!$dberror) {
		opentable(LAN_470);
		echo "<form name='install' method='post' action='$PHP_SELF'>
<center>".LAN_471."<br><br>\n";
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."articles");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."articles (
		article_id smallint(5) unsigned NOT NULL auto_increment,
		article_cat smallint(5) unsigned NOT NULL default '0',
		article_subject varchar(200) NOT NULL default '',
		article_snippet text NOT NULL,
		article_article text NOT NULL,
		article_breaks char(1) NOT NULL default '',
		article_name smallint(5) unsigned NOT NULL default '1',
		article_datestamp int(10) unsigned NOT NULL default '0',
		article_reads smallint(5) unsigned NOT NULL default '0',
		PRIMARY KEY (article_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."articles".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."articles<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."article_cats");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."article_cats (
		article_cat_id smallint(5) unsigned NOT NULL auto_increment,
		article_cat_name varchar(100) NOT NULL default '',
		article_cat_description varchar(200) NOT NULL default '',
		PRIMARY KEY (article_cat_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."article_cats".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."article_cats<br>\n";
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
			echo LAN_472.$fusion_prefix."comments".LAN_473."<br>\n";
		} else {
			echo "Error: Unable to create table comments<br>\n";
		}

		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."custom_pages");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."custom_pages (
		page_id smallint(5) NOT NULL auto_increment,
		page_title varchar(200) NOT NULL default '',
		page_access tinyint(1) unsigned NOT NULL default '0',
		PRIMARY KEY (page_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."custom_pages".LAN_473."<br>\n";
		} else {
			echo "Error: Unable to create table custom_pages<br>\n";
		}

		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."download_cats");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."download_cats (
		download_cat_id smallint(5) unsigned NOT NULL auto_increment,
		download_cat_name varchar(100) NOT NULL default '',
		download_cat_description varchar(250) NOT NULL default '',
		PRIMARY KEY (download_cat_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."download_cats".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."download_cats<br>\n";
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
			echo LAN_472.$fusion_prefix."downloads".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."downloads<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."faq_cats");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."faq_cats (
		faq_cat_id smallint(5) unsigned NOT NULL auto_increment,
		faq_cat_name varchar(200) NOT NULL default '',
		PRIMARY KEY(faq_cat_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."faq_cats".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."faq_cats<br>\n";
		}

		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."faqs");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."faqs (
		faq_id smallint(5) unsigned NOT NULL auto_increment,
		faq_cat_id smallint(5) unsigned NOT NULL default '0',
		faq_question varchar(200) NOT NULL default '',
		faq_answer text NOT NULL,
		PRIMARY KEY(faq_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."faqs".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."faqs<br>\n";
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
			echo LAN_472.$fusion_prefix."forum_attachments".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."forum_attachments<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."forums");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."forums (
		forum_id smallint(5) unsigned NOT NULL auto_increment,
		forum_cat smallint(5) unsigned NOT NULL default '0',
		forum_name varchar(100) NOT NULL default '',
		forum_order smallint(5) unsigned NOT NULL default '0',
		forum_description text NOT NULL,
		forum_access tinyint(1) unsigned NOT NULL default '0',
		forum_lastpost int(10) unsigned NOT NULL default '0',
		forum_lastuser smallint(5) unsigned NOT NULL default '0',
		PRIMARY KEY (forum_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."forums".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."forums<br>\n";
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
		guestbook_ip varchar(20) NOT NULL default '0.0.0.0',
		PRIMARY KEY (guestbook_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."guestbook".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."guestbook<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."infusions");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."infusions (
		inf_id smallint(5) unsigned NOT NULL auto_increment,
		inf_name varchar(100) NOT NULL default '',
		inf_folder varchar(100) NOT NULL default '',
		inf_admin varchar(100) NOT NULL default '',
		inf_version smallint(5) unsigned NOT NULL default '0',
		inf_installed tinyint(1) unsigned NOT NULL default '0',
		PRIMARY KEY (inf_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."infusions".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."infusions<br>\n";
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
			echo LAN_472.$fusion_prefix."messages".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."messages<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."news");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."news (
		news_id smallint(5) unsigned NOT NULL auto_increment,
		news_subject varchar(200) NOT NULL default '',
		news_news text NOT NULL,
		news_extended text NOT NULL,
		news_breaks char(1) NOT NULL default '',
		news_name smallint(5) unsigned NOT NULL default '1',
		news_datestamp int(10) unsigned NOT NULL default '0',
		news_start int(10) unsigned NOT NULL default '0',
		news_end int(10) unsigned NOT NULL default '0',
		news_reads smallint(5) unsigned NOT NULL default '0',
		PRIMARY KEY (news_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."news".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."news<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."new_users");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."new_users (
		user_code VARCHAR(32) NOT NULL,
		user_email VARCHAR(100) NOT NULL,
		user_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
		user_info TEXT NOT NULL
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."new_users".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."new_users<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."ratings");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."ratings (
		rating_id smallint(5) unsigned NOT NULL auto_increment,
		rating_item_id smallint(5) unsigned NOT NULL default '0',
		rating_type char(1) NOT NULL default '',
		rating_user smallint(5) unsigned NOT NULL default '0',
		rating_vote tinyint(1) unsigned NOT NULL default '0',
		rating_datestamp int(10) unsigned NOT NULL default '0',
		rating_ip varchar(20) NOT NULL default '0.0.0.0',
		PRIMARY KEY (rating_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."ratings".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."ratings<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."online");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."online (
		online_user varchar(50) NOT NULL default '',
		online_ip varchar(20) NOT NULL default '',
		online_lastactive int(10) unsigned NOT NULL default '0'
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."online".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."online<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."panels");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."panels (
		panel_id smallint(5) unsigned NOT NULL auto_increment,
		panel_name varchar(100) NOT NULL default '',
		panel_filename varchar(100) NOT NULL default '',
		panel_content text NOT NULL,
		panel_side tinyint(1) unsigned NOT NULL default 'l',
		panel_order smallint(5) unsigned NOT NULL default '0',
		panel_type varchar(20) NOT NULL default '',
		panel_access tinyint(1) unsigned NOT NULL default '0',
		panel_status tinyint(1) unsigned NOT NULL default '0',
		PRIMARY KEY (panel_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."panels".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."panels<br>\n";
		}
					
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."photo_albums");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."photo_albums (
		album_id smallint(5) unsigned NOT NULL auto_increment,
		album_title varchar(50) NOT NULL default '',
		album_info varchar(200) default '',
		album_order smallint(5) unsigned NOT NULL default '0',
		PRIMARY KEY (album_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."photo_albums".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."photo_albums<br>\n";
		}
					
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
		
		if ($result) {
			echo LAN_472.$fusion_prefix."photos".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."photos<br>\n";
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
			echo LAN_472.$fusion_prefix."poll_votes".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."poll_votes<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."polls");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."polls (
		poll_id smallint(5) unsigned NOT NULL auto_increment,
		poll_title varchar(200) NOT NULL default '',
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
		poll_started int(10) unsigned NOT NULL default '',
		poll_ended int(10) unsigned NOT NULL default '0',
		PRIMARY KEY (poll_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."polls".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."polls<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."posts");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."posts (
		forum_id smallint(5) unsigned NOT NULL default '0',
		thread_id smallint(5) unsigned NOT NULL default '0',
		post_id smallint(5) unsigned NOT NULL auto_increment,
		post_subject varchar(100) NOT NULL default '',
		post_message text NOT NULL,
		post_showsig tinyint(1) unsigned NOT NULL default '0',
		post_smileys tinyint(1) unsigned NOT NULL default '1',
		post_author smallint(5) unsigned NOT NULL default '0',
		post_datestamp int(10) unsigned NOT NULL default '0',
		post_edituser smallint(5) unsigned NOT NULL default '0',
		post_edittime int(10) unsigned NOT NULL default '',
		PRIMARY KEY (post_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."posts".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."posts<br>\n";
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
		start_page varchar(50) NOT NULL default 'news.php',
		other_page varchar(100) NOT NULL default '',
		language varchar(20) NOT NULL default 'English',
		theme varchar(100) NOT NULL default '',
		shortdate varchar(50) NOT NULL default '',
		longdate varchar(50) NOT NULL default '',
		timeoffset varchar(3) NOT NULL default '0',
		forumdate varchar(50) NOT NULL default '',
		numofthreads smallint(2) unsigned NOT NULL default '5',
		attachments tinyint(1) unsigned NOT NULL default '0',
		attachmax int(12) unsigned NOT NULL default '150000',
		attachtypes varchar(150) NOT NULL default '.gif,.jpg,.png,.zip,.rar,.tar',
		enable_registration TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
		email_verification TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
		display_validation TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
		validation_method VARCHAR(5) DEFAULT 'image' NOT NULL,
		album_image_w smallint(3) unsigned NOT NULL default '80',
		album_image_h smallint(3) unsigned NOT NULL default '60',
		thumb_image_w smallint(3) unsigned NOT NULL default '120',
		thumb_image_h smallint(3) unsigned NOT NULL default '100',
		thumb_compression CHAR(3) DEFAULT 'gd2' NOT NULL,
		album_comments tinyint(1) unsigned NOT NULL default '1',
		albums_per_row smallint(2) unsigned NOT NULL default '4',
		albums_per_page smallint(2) unsigned NOT NULL default '16',
		thumbs_per_row smallint(2) unsigned NOT NULL default '4',
		thumbs_per_page smallint(2) unsigned NOT NULL default '12',
		album_max_w smallint(4) unsigned NOT NULL default '400',
		album_max_h smallint(4) unsigned NOT NULL default '300',
		album_max_b int(10) unsigned NOT NULL default '150000',
		bad_words TEXT NOT NULL,
		bad_word_replace VARCHAR(20) DEFAULT '[censored]' NOT NULL,
		guestposts tinyint(1) unsigned NOT NULL default '0',
		numofshouts tinyint(2) unsigned NOT NULL default '10',
		counter bigint(20) unsigned NOT NULL default '0',
		version smallint(5) unsigned NOT NULL default '400',
		maintenance tinyint(1) unsigned NOT NULL default '0',
		maintenance_message text NOT NULL
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."settings".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."settings<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."shoutbox");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."shoutbox (
		shout_id smallint(5) unsigned NOT NULL auto_increment,
		shout_name varchar(50) NOT NULL default '',
		shout_message varchar(200) NOT NULL default '',
		shout_datestamp int(10) unsigned NOT NULL default '0',
		shout_ip varchar(20) NOT NULL default '0.0.0.0',
		PRIMARY KEY (shout_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."shoutbox".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."shoutbox<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."site_links");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."site_links (
		link_id smallint(5) unsigned NOT NULL auto_increment,
		link_name varchar(100) NOT NULL default '',
		link_url varchar(200) NOT NULL default '',
		link_visibility smallint(5) unsigned NOT NULL default '0',
		link_position tinyint(1) unsigned NOT NULL default '1',
		link_order smallint(2) unsigned NOT NULL default '0',
		PRIMARY KEY (link_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."site_links".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."site_links<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."submissions");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."submissions (
		submit_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
		submit_type CHAR(1) NOT NULL,
		submit_user SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
		submit_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
		submit_criteria TEXT NOT NULL,
		PRIMARY KEY (submit_id) 
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."submissions".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."submissions<br>\n";
		}

		if ($result) {
			echo LAN_472.$fusion_prefix."submitted_news".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."submitted_news<br>\n";
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
			echo LAN_472.$fusion_prefix."temp".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."temp<br>\n";
		}

		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."threads");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."threads (
		forum_id smallint(5) unsigned NOT NULL default '0',
		thread_id smallint(5) unsigned NOT NULL auto_increment,
		thread_subject varchar(100) NOT NULL default '',
		thread_author smallint(5) unsigned NOT NULL default '0',
		thread_views smallint(5) unsigned NOT NULL default '0',
		thread_lastpost int(10) unsigned NOT NULL default '0',
		thread_lastuser smallint(5) unsigned NOT NULL default '0',
		thread_sticky tinyint(1) unsigned NOT NULL default '0',
		thread_locked tinyint(1) unsigned NOT NULL default '0',
		PRIMARY KEY (thread_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."threads".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."threads<br>\n";
		}
		
		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."users");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."users (
		user_id smallint(5) unsigned NOT NULL auto_increment,
		user_name varchar(30) NOT NULL default '',
		user_password varchar(32) NOT NULL default '',
		user_email varchar(100) NOT NULL default '',
		user_hide_email tinyint(1) unsigned NOT NULL default '1',
		user_location varchar(50) NOT NULL default '',
		user_birthdate date NOT NULL default '0000-00-00',
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
			echo LAN_472.$fusion_prefix."users".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."users<br>\n";
		}

		$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."weblink_cats");
		$result = dbquery("CREATE TABLE ".$fusion_prefix."weblink_cats (
		weblink_cat_id smallint(5) unsigned NOT NULL auto_increment,
		weblink_cat_name varchar(100) NOT NULL default '',
		weblink_cat_description varchar(200) NOT NULL default '',
		PRIMARY KEY(weblink_cat_id)
		) TYPE=MyISAM;");
		
		if ($result) {
			echo LAN_472.$fusion_prefix."weblink_cats".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."weblink_cats<br>\n";
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
			echo LAN_472.$fusion_prefix."weblinks".LAN_473."<br>\n";
		} else {
			echo LAN_474.$fusion_prefix."weblinks<br>\n";
		}
		
		echo "<br>
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
} else if ($_POST['stage'] == "6") {
	opentable(LAN_490);
	echo "<form name='install' method='post' action='$PHP_SELF'>
<center>".LAN_491."</center><br>
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
<center><br>
<input type='hidden' name='site_language' value='".$_POST['site_language']."'>
<input type='hidden' name='stage' value='7'>
<input type='reset' name='reset' value='".LAN_401."' class='button'>
<input type='submit' name='next' value='".LAN_400."' class='button'></center>
</form>\n";
	closetable();
} else if ($_POST['stage'] == "7") {
	$username = stripinput($_POST['username']);
	$password1 = stripinput($_POST['password1']);
	$password2 = stripinput($_POST['password2']);
	$email = stripinput($_POST['email']);
	if ($username == "") {
		$error = LAN_520."<br><br>\n";
	}
	if ($password1 != "") {
		if ($password1 != $password2) {
			$error .= LAN_521."<br><br>\n";
		}
	} else {
		$error .= LAN_522."<br><br>\n";
	}
	if ($email == "") {
		$error .= LAN_523."<br><br>\n";
	}
	if ($error == "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."users VALUES('', '$username', md5('$password1'), '$email', '1', '',
		'0000-00-00', '', '', '', '', 'Default', '0', '', '', '0', '".time()."', '0', '4', '0')");
		
		$result = dbquery(
			"INSERT INTO ".$fusion_prefix."settings VALUES('PHP-Fusion Powered Website', 'http://www.yourdomain.com/',
			'fusion_images/banner.gif', 'you@yourdomain.com', '$username', '<center>Welcome to your site</center>', '', '', 
			'<center>Copyright © 2004</center>', 'news.php', '', '".$_POST['site_language']."', 'Prometheus',
			'%d %b : %H:%M', '%B %d %Y - %H:%M:%S', '0', '%d-%m-%y %H:%M',
			'5', '0', '150000', '.gif,.jpg,.png,.zip,.rar,.tar',
			'1', '1', '1', 'image',
			'80', '60', '120', '100', 'gd2', '1', '4', '16', '4', '12', '400', '300', '150000',
			'', '[censored]', '0', '10',
			'0', '500', '0', '')"
		);
		
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_540."', 'index.php', '0', '2', '1')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_541."', 'articles.php', '0', '2', '2')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_542."', 'downloads.php', '0', '2', '3')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_543."', 'faq.php', '0', '1', '4')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_544."', 'fusion_forum/index.php', '0', '2', '5')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_545."', 'contact.php', '0', '1', '6')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_546."', 'guestbook.php', '0', '1', '7')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_547."', 'weblinks.php', '0', '2', '8')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_548."', 'photogallery.php', '0', '1', '9')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_549."', 'search.php', '0', '1', '10')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '---', '---', '1', '1', '11')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_550."', 'submit.php?stype=l', '1', '1', '12')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_551."', 'submit.php?stype=n', '1', '1', '13')");
		$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '".LAN_552."', 'submit.php?stype=a', '1', '1', '14')");
		
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', '".LAN_560."', 'navigation_panel', '', '1', 1, 'file', 0, 1)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', '".LAN_561."', 'online_users_panel', '', '1', 2, 'file', 0, 1)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', '".LAN_562."', 'forum_threads_panel', '', '1', 3, 'file', 0, 0)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', '".LAN_563."', 'latest_articles_panel', '', '1', 4, 'file', 0, 0)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', '".LAN_564."', 'welcome_message_panel', '', '2', 1, 'file', 0, 1)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', '".LAN_565."', 'forum_threads_list_panel', '', '2', 2, 'file', 0, 0)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', '".LAN_566."', 'user_info_panel', '', '3', 1, 'file', 0, 1)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', '".LAN_567."', 'member_poll_panel', '', '3', 2, 'file', 0, 0)");
		$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', '".LAN_568."', 'shoutbox_panel', '', '3', 3, 'file', 0, 1)");
		opentable(LAN_600);
		echo "<form name='install' method='post' action='index.php'>
<center><br>
".LAN_601."<br><br>
<input type='submit' name='next' value='".LAN_400."' class='button'>
</center><br>
</form>\n";
		closetable();
	} else {
		opentable(LAN_602);
		echo "<center><br>
".LAN_603."<br><br>
$error<br>
".LAN_604."
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

<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td align='center' class='main-body'>
<br>Powered by <a href='http://www.php-fusion.co.uk/'>PHP-Fusion</a> v5.00 © 2003-2004<br><br>
</td>
</tr>
</table>

</body>
</html>\n";
?>