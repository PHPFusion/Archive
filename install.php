<?
echo "<html>
<head>
<title>PHP-Fusion Titanium Edition Installer</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<link rel=\"stylesheet\" href=\"themes/aquarium/styles.css\" type=\"text/css\">
</head>

<body bgcolor=\"#666666\" text=\"#DDDDDD\">
<table width=\"500\" align=\"center\">
<tr><td align=\"center\"><img src=\"images/phpfusion.gif\"></td></tr>
<tr><td>\n";
require "includes/classes.php";

if (empty($stage) || ($stage == "")) {
	$stage = 1;
}
if ($stage == 1) {
	opentable("Introduction");
	echo "<form name=\"install\" method=\"post\" action=\"$PHP_SELF?stage=2\">
PHP-Fusion is an all in one community website package designed to allow you to create and
maintain an interactive website with ease.  PHP-Fusion comes complete with features including
news delivery & articles with member comments option, fully customisable internal
site & external web links, unique hits web counter and users/members online, polls,
shoutbox and integrated forums.

<p>PHP-Fusion is easily maintained via the integrated admin panel, this provides you with
everything you need to add, remove and maintain your site content. Adding news is a
simple matter of selecting add news, filling in the form followed by clicking save, it's
as simple as that. The admin panel is designed for ease of use.

<p>PHP-Fusion can be installed easily by completing this process, you will be required
to enter your database settings and for your chosen administrator username and
password.  Before you proceed please ensure you have CHMODDED the folders <b>news</b>,
<b>articles</b> and <b>includes</b> to 777 and <b>includes/config.php</b> to 766.

<p><div align=\"center\">
<input type=\"submit\" name=\"next\" value=\"next\" class=\"button\"></div>
</form>\n";
	closetable();
}

if ($stage == "2") {
	opentable("Database Settings");
	echo "<table width=\"100%\" align=\"center\">
<form name=\"install\" method=\"post\" action=\"$PHP_SELF?stage=3\">
<tr><td width=\"130\">database host:</td>
<td><input type=\"text\" name=\"dbhost\" class=\"textbox\" maxlength=\"255\" value=\"localhost\" style=\"width: 150px\"></td></tr>
<tr><td width=\"130\">database username</td>
<td><input type=\"text\" name=\"dbusername\" class=\"textbox\" maxlength=\"255\" style=\"width: 150px\"></td></tr>
<tr><td width=\"130\">database password</td>
<td><input type=\"text\" name=\"dbpassword\" class=\"textbox\" maxlength=\"255\" style=\"width: 150px\"></td></tr>
<tr><td width=\"130\">database name</td>
<td><input type=\"text\" name=\"dbname\" class=\"textbox\" maxlength=\"255\" style=\"width: 150px\"></td></tr>
<tr><td colspan=\"2\"><div align=\"center\"><br><br>
<input type=\"reset\" name=\"reset\" value=\"reset\" class=\"button\">
<input type=\"submit\" name=\"next\" value=\"next\" class=\"button\"></div>
</td></tr>
</form>
</table>\n";
	closetable();
}

if ($stage == "3") {
	$config = chr(60)."?\n// database settings\n";
	$config .= chr(36)."dbhost=".chr(34).$dbhost.chr(34).";\n";
	$config .= chr(36)."dbusername=".chr(34).$dbusername.chr(34).";\n";
	$config .= chr(36)."dbpassword=".chr(34).$dbpassword.chr(34).";\n";
	$config .= chr(36)."dbname=".chr(34).$dbname.chr(34).";\n";
	$config .= "// cookie settings\n";
	$config .= chr(36)."cookiename=".chr(34)."userid".chr(34).";\n";
	$config .= chr(36)."cookieexpire=".chr(34)."time()".chr(34).";\n";
	$config .= chr(36)."cookiepath=".chr(34)."/".chr(34).";\n";
	$config .= chr(36)."cookiedomain=".chr(34).chr(34).";\n";
	$config .= chr(36)."cookiesecure=".chr(34)."0".chr(34).";\n?".chr(62);
	$fp = fopen("includes/config.php","w");
	if (fwrite($fp, $config)) {
		opentable("Config Settings Written");
		echo "<form name=\"install\" method=\"post\" action=\"$PHP_SELF?stage=4\">
PHP-Fusion has successfully written the config file. continue to the next step to create the
database tables.
<p><div align=\"center\"> 
<input type=\"submit\" name=\"next\" value=\"next\" class=\"button\"></div>
</form>\n";
		closetable();
	} else {
		opentable("Error: Config Settings");
		echo "PHP-Fusion was unable to write to the config.php file. please ensure you
have uploaded the file to the includes folder and CHMODDED the file to
766, then repeat the installation.\n";
		closetable();
	}
	fclose($fp);
}

if ($stage == "4") {
	require "includes/config.php";
	if (mysql_connect($dbhost, $dbusername, $dbpassword)) {
		if (mysql_select_db($dbname)) {
			opentable("Database Table Creation");
			echo "<form name=\"install\" method=\"post\" action=\"$PHP_SELF?stage=5\">
<p>PHP-Fusion successfully connected to the database server and has attempted to create the required
database tables. if any errors have occured please check your database settings and repeat the
installation.</p>\n";
			$result = dbquery("DROP TABLE IF EXISTS articlecomments");
			$result = dbquery("CREATE TABLE articlecomments (
			acid smallint(6) NOT NULL auto_increment,
			itemid smallint(6) NOT NULL default '0',
			name varchar(32) NOT NULL default '',
			message varchar(255) NOT NULL default '',
			posted varchar(10) NOT NULL default '',
			PRIMARY KEY (acid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table articlecomments successfully created<br>";
			} else {
				echo "error: unable to create table articlecomments<br>";
			}

			$result = dbquery("DROP TABLE IF EXISTS articles");
			$result = dbquery("CREATE TABLE articles (
			aid smallint(6) NOT NULL auto_increment,
			subject varchar(128) NOT NULL default '',
			filename varchar(32) NOT NULL default '',
			breaks varchar(1) NOT NULL default 'y',
			postname varchar(32) NOT NULL default '',
			postemail varchar(64) NOT NULL default '',
			posted varchar(10) NOT NULL default '',
			comments smallint(6) NOT NULL default '0',
			reads smallint(6) NOT NULL default '0',
			PRIMARY KEY (aid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table articles successfully created<br>";
			} else {
				echo "error: unable to create table articles<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS downloads");
			$result = dbquery("CREATE TABLE downloads (
			dlid smallint(6) NOT NULL auto_increment,
			dlcat smallint(6) NOT NULL default '0',
			dltype varchar(10) NOT NULL default '',
			title varchar(64) NOT NULL default '',
			details varchar(255) NOT NULL default '',
			opsys varchar(32) NOT NULL default '',
			version varchar(20) NOT NULL default '',
			filesize varchar(10) NOT NULL default '',
			url varchar(20) NOT NULL default '',
			posted varchar(10) NOT NULL default '',
			downloads smallint(6) NOT NULL default '0',
			PRIMARY KEY (dlid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table downloads successfully created<br>";
			} else {
				echo "error: unable to create table downloads<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS forums");
			$result = dbquery("CREATE TABLE forums (
			forumtype varchar(15) NOT NULL default '',
			fid smallint(6) NOT NULL auto_increment,
			fup smallint(6) NOT NULL default '0',
			forumname varchar(50) NOT NULL default '',
			forumorder smallint(6) NOT NULL default '0',
			forumdetails text NOT NULL,
			threads smallint(6) NOT NULL default '0',
			posts smallint(6) NOT NULL default '0',
			lastpost varchar(10) NOT NULL default '',
			lastuser varchar(32) NOT NULL default '',
			PRIMARY KEY (fid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table forums successfully created<br>";
			} else {
				echo "error: unable to create table forums<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS linksubmits");
			$result = dbquery("CREATE table linksubmits (
			slid smallint(6) NOT NULL auto_increment,
			sitename varchar(64) NOT NULL default '',
			siteurl varchar(255) NOT NULL default '',
			category varchar(32) NOT NULL default '',
			name varchar(32) NOT NULL default '',
			email varchar(64) NOT NULL default '',
			submittime varchar(10) NOT NULL default '',
			userip varchar(15) NOT NULL default '',
			PRIMARY KEY (slid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "linksubmits table successfully created<br>";
			} else {
				echo "error: unable to create table linksubmits<br>";
			}

			$result = dbquery("DROP TABLE IF EXISTS messages");
			$result = dbquery("CREATE table messages (
			mid smallint(6) NOT NULL auto_increment,
			touser varchar(32) NOT NULL default '',
			fromuser varchar(32) NOT NULL default '',
			subject varchar(32) NOT NULL default '',
			message text NOT NULL,
			isread tinyint(1) NOT NULL default '0',
			posted varchar(10) NOT NULL default '',
			PRIMARY KEY (mid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table messages successfully created<br>";
			} else {
				echo "error: unable to create table messages<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS news");
			$result = dbquery("CREATE table news (
			nid smallint(6) NOT NULL auto_increment,
			subject varchar(128) NOT NULL default '',
			news text NOT NULL,
			extendednews text NOT NULL,
			postname varchar(32) NOT NULL default '',
			postemail varchar(64) NOT NULL default '',
			posted varchar(10) NOT NULL default '',
			comments smallint(6) NOT NULL default '0',
			reads smallint(6) NOT NULL default '0',
			PRIMARY KEY (nid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table news successfully created<br>";
			} else {
				echo "error: unable to create table news<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS newscomments");
			$result = dbquery("CREATE TABLE newscomments (
			ncid smallint(6) NOT NULL auto_increment,
			itemid smallint(6) NOT NULL default '0',
			name varchar(32) NOT NULL default '',
			message varchar(255) NOT NULL default '',
			posted varchar(10) NOT NULL default '',
			PRIMARY KEY (ncid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table newscomments successfully created<br>";
			} else {
				echo "error: unable to create table newscomments<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS online");
			$result = dbquery("CREATE TABLE online (
			oid smallint(6) NOT NULL auto_increment,
			username varchar(32) NOT NULL default '',
			userid varchar(32) NOT NULL default '',
			userip varchar(15) NOT NULL default '',
			lastactive varchar(10) NOT NULL default '0',
			PRIMARY KEY (oid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table online successfully created<br>";
			} else {
				echo "error: unable to create table online<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS newssubmits");
			$result = dbquery("CREATE table newssubmits (
			snid smallint(6) NOT NULL auto_increment,
			name varchar(32) NOT NULL default '',
			email varchar(64) NOT NULL default '',
			subject varchar(255) NOT NULL default '',
			news text NOT NULL,
			extendednews text NOT NULL,
			submittime varchar(10) NOT NULL default '',
			userip varchar(15) NOT NULL default '',
			PRIMARY KEY (snid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "newssubmits table successfully created<br>";
			} else {
				echo "error: unable to create table newssubmits<br>";
			}

			$result = dbquery("DROP TABLE IF EXISTS polls");
			$result = dbquery("CREATE TABLE polls (
			pollid smallint(6) NOT NULL auto_increment,
			title varchar(255) NOT NULL default '',
			options text NOT NULL,
			votes smallint(6) NOT NULL default '0',
			started varchar(10) NOT NULL default '',
			ended varchar(10) NOT NULL default '0',
			PRIMARY KEY (pollid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table polls successfully created<br>";
			} else {
				echo "error: unable to create table polls<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS posts");
			$result = dbquery("CREATE TABLE posts (
			fid smallint(6) NOT NULL default '0',
			tid smallint(6) NOT NULL default '0',
			pid smallint(6) NOT NULL auto_increment,
			subject varchar(50) NOT NULL default '',
			message text NOT NULL,
			showsig char(1) NOT NULL default '',
			author varchar(32) NOT NULL default '',
			posted varchar(10) NOT NULL default '',
			edituser varchar(32) NOT NULL default '',
			edittime varchar(10) NOT NULL default '',
			PRIMARY KEY (pid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table posts successfully created<br>";
			} else {
				echo "error: unable to create table posts<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS settings");
			$result = dbquery("CREATE TABLE settings (
			sitename varchar(255) NOT NULL default '',
			siteurl varchar(255) NOT NULL default '',
			sitebanner varchar(255) NOT NULL default '',
			siteemail varchar(128) NOT NULL default '',
			siteusername varchar(32) NOT NULL default '',
			siteintro text NOT NULL,
			description text NOT NULL default '',
			keywords text NOT NULL,
			footer text NOT NULL default '',
			theme varchar(32) NOT NULL default '',
			visitorshoutbox varchar(3) NOT NULL default '',
			visitorcomments varchar(3) NOT NULL default '',
			articles smallint(6) NOT NULL default '0',
			counter smallint(6) NOT NULL default '0',
			version smallint(6) NOT NULL default '131'
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table settings successfully created<br>";
			} else {
				echo "error: unable to create table settings<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS shoutbox");
			$result = dbquery("CREATE TABLE shoutbox (
			shoutid smallint(6) NOT NULL auto_increment,
			name varchar(32) NOT NULL default '',
			message varchar(255) NOT NULL default '',
			posted varchar(10) NOT NULL default '',
			userip varchar(15) NOT NULL default '',
			PRIMARY KEY (shoutid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table shoutbox successfully created<br>";
			} else {
				echo "error: unable to create table shoutbox<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS sitelinks");
			$result = dbquery("CREATE TABLE sitelinks (
			lid smallint(6) NOT NULL auto_increment,
			linkname varchar(64) NOT NULL default '',
			linkurl varchar(255) NOT NULL default '',
			roworder smallint(2) NOT NULL default '0',
			PRIMARY KEY (lid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table online successfully sitelinks<br>";
			} else {
				echo "error: unable to create table sitelinks<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS threads");
			$result = dbquery("CREATE TABLE threads (
			fid smallint(6) NOT NULL default '0',
			tid smallint(6) NOT NULL auto_increment,
			subject varchar(50) NOT NULL default '',
			author varchar(32) NOT NULL default '',
			views smallint(6) NOT NULL default '0',
			replies smallint(6) NOT NULL default '0',
			lastpost varchar(10) NOT NULL default '',
			lastuser varchar(32) NOT NULL default '',
			sticky char(1) NOT NULL default '',
			locked char(1) NOT NULL default '',
			PRIMARY KEY (tid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table threads successfully created<br>";
			} else {
				echo "error: unable to create table threads<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS users");
			$result = dbquery("CREATE TABLE users (
			userid varchar(32) NOT NULL default '',
			username varchar(32) NOT NULL default '',
			password varchar(32) NOT NULL default '',
			email varchar(64) NOT NULL default '',
			location varchar(32) NOT NULL default '',
			icq varchar(12) NOT NULL default '',
			msn varchar(64) NOT NULL default '',
			yahoo varchar(64) NOT NULL default '',
			web varchar(128) NOT NULL default '',
			sig text,
			posts smallint(6) NOT NULL default '0',
			joined varchar(10) NOT NULL default '',
			lastvisit varchar(10) NOT NULL default '',
			mod varchar(32) NOT NULL default '',
			PRIMARY KEY (userid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table users successfully created<br>";
			} else {
				echo "error: unable to create table users<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS votes");
			$result = dbquery("CREATE TABLE votes (
			voteid varchar(32) NOT NULL default '',
			vote smallint(2) NOT NULL default '0',
			pollid smallint(6) NOT NULL default '0'
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table votes successfully created<br>";
			} else {
				echo "error: unable to create table votes<br>";
			}
			
			$result = dbquery("DROP TABLE IF EXISTS weblinks");
			$result = dbquery("CREATE TABLE weblinks (
			wlid smallint(6) NOT NULL auto_increment,
			parentlink smallint(2) NOT NULL default '0',
			linkname varchar(64) NOT NULL default '',
			linktype varchar(8) NOT NULL default '',
			linkurl varchar(255) NOT NULL default '',
			linkorder smallint(2) NOT NULL default '0',
			referals smallint(6) NOT NULL default '0',
			PRIMARY KEY (wlid)
			) TYPE=MyISAM;");
			
			if ($result) {
				echo "table weblinks successfully created<br>";
			} else {
				echo "error: unable to create table weblinks<br>";
			}
			
			echo "<p><div align=\"center\"> 
<input type=\"submit\" name=\"Next\" value=\"Next\" class=\"button\"></div>
</form>";
			closetable();
		} else {
			opentable("Database Table Creation");
			echo "PHP-Fusion was able to connect to your database server but failed
to select the named database, please ensure the database name is correct and repeat the 
installation process.\n";
			closetable();
		}
	} else {
		opentable("Database Table Creation");
		echo "PHP-Fusion was unable to connect to your database server. Please ensure the 
database settings are correct and repeat the process.\n";
		closetable();
	}
}

if ($stage == "5") {
	opentable("Administrator Settings");
	echo "<table width=\"100%\" align=\"center\">
<form name=\"install\" method=\"post\" action=\"$PHP_SELF?stage=6\">
<tr><td colspan=\"2\" height=\"10\"></td></tr>
<tr><td width=\"130\">username:</td>
<td><input type=\"text\" name=\"username\" class=\"textbox\" maxlength=\"32\"></td></tr>
<tr><td width=\"130\">password:</td>
<td><input type=\"password\" name=\"password1\" class=\"textbox\" maxlength=\"32\"></td></tr>
<tr><td width=\"130\">repeat password:</td>
<td><input type=\"password\" name=\"password2\" class=\"textbox\" maxlength=\"32\"></td></tr>
<tr><td width=\"130\">email address:</td>
<td><input type=\"text\" name=\"email\" class=\"textbox\" maxlength=\"64\"></td></tr>
<tr><td colspan=\"2\"><div align=\"center\"><br>
<input type=\"reset\" name=\"reset\" value=\"reset\" class=\"button\">
<input type=\"submit\" name=\"next\" value=\"next\" class=\"button\"></div>
</td></tr>
</form>";
}

if ($stage == "6") {
	$username = stripinput($username);
	$password1 = stripinput($password1);
	$password2 = stripinput($password2);
	$email = stripinput($email);
	if ($username == "") {
		$error .= "you did not specify a username.<br><br>";
	}
	if ($password1 != "") {
		if ($password1 != $password2) {
			$error .= "passwords do not match.<br><br>";
		}
	} else {
		$error .= "you did not specify a password.<br><br>";
	}
	if ($email == "") {
		$error .= "you did not specify your email address.<br><br>";
	}
	if ($error == "") {
		require "includes/config.php";
		$servertime = time();
		mysql_connect($dbhost, $dbusername, $dbpassword);
		mysql_select_db($dbname);
		$result = dbquery("INSERT INTO users VALUES(md5('$username'), '$username', 
		md5('$password1'), '$email', '', '', '', '', '', '', '0', '$servertime', '0', 'Administrator')");
		$result = dbquery("INSERT INTO settings VALUES('PHP-Fusion Powered Website', 'http://yourdomain.co.uk',
		'images/phpfusion.gif', 'you@yourdomain.co.uk', '$username', 'Welcome to your site', '', '', 'Copyright © 2003',
		'aquarium', 'no', 'no', '0', '0', '132')");
		$result = dbquery("INSERT INTO sitelinks VALUES('', 'home', 'index.php', '1')");
		$result = dbquery("INSERT INTO sitelinks VALUES('', 'articles', 'articles.php', '2')");
		$result = dbquery("INSERT INTO sitelinks VALUES('', 'downloads', 'downloads.php', '3')");
		$result = dbquery("INSERT INTO sitelinks VALUES('', 'discussion forum', 'forum/index.php', '4')");
		opentable("Installation Complete");
		echo "<form name=\"install\" method=\"post\" action=\"index.php\">
PHP-Fusion is now installed and ready for use.  if you encounter any problems
with this package please visit the PHP-Fusion forum at <a href=\"http:www.digitaldominion.co.uk//\">
www.digitaldominion.co.uk</a>. thankyou for choosing PHP-Fusion, I know you have a choice :)
<p><div align=\"center\"> 
<input type=\"submit\" name=\"next\" value=\"next\" class=\"button\"></div>
</form>\n";
		closetable();
	} else {
		opentable("Installation Incomplete");
		echo "<form name=\"install\" method=\"post\" action=\"index.php\">
PHP-Fusion was unable to complete the installation due to the following errors:<br><br>
$error
please ensure each of the fields are complete and repeat the process.
<p><div align=\"center\"> 
<input type=\"submit\" name=\"Next\" value=\"Next\" class=\"button\"></div>
</form>";
		closetable();
	}
}

echo "</td></tr>
</table>
</body>
</html>";
?>