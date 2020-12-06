PHP-Fusion v6.00.104
Released June 11th 2005
Copyright (C) 2002 - 2005 Nick Jones
Email: digitanium@php-fusion.co.uk
Web: http://www.php-fusion.co.uk

1. Overview
2. Installation
3. Upgrade from v4.01
4. Upgrade from v5.00/v5.01
5. Additional notes
6. Additional credits

---------------------------
1. OVERVIEW
---------------------------
PHP-Fusion is an all-in-one content management system (CMS) written in PHP.
It uses a mySQL database to store all of your site's content such as News,
Articles, Forum Posts, Shoutbox Posts and more.

PHP-Fusion is released under the terms and conditions of version 2 of the
GNU General Public License. For further information please visit www.gnu.org
or refer to the gpl.txt file included in the distribution package. You may
alter the code in any way you wish and redistribute it either as is or
containing your own modifications.

You must not remove the "Powered by PHP-Fusion" copyright notice nor remove
the copyright information from any of the included scripts. We feel this is
fair when you consider the hundreds of hours of hard work that has gone into
the development of this product.

---------------------------
2. INSTALLATION
---------------------------
1 - Upload the contents of the php-files folder to your server.
2 - CHMOD the following files & folders to 777:
	- administration/db_backups/
	- images/
	- images/articles/
	- images/avatars/
	- images/news/
	- images/photoalbum/
	- forum/attachments/
	- config.php
3 - Goto your website and run setup.php e.g. http://www.yourdomain.com/setup.php.
4 - Complete the setup process by following all on-screen prompts.
5 - CHMOD config.php back to 644 AND delete setup.php from your server.

---------------------------
3. UPGRADE FROM V4.01
---------------------------
Before upgrading we strongly recommend that you backup your site and your database.

1 - Disable any 3rd party panels via panels admin except for:
	- Navigation
	- Online Users
	- Forum Threads
	- Latest Articles
	- Welcome Message
	- Forum Threads List
	- User Info
	- Members Poll
	- Shoutbox
2 - Upload config.php from php-files folder to your server.
	- WARNING: YOU MUST DELETE CONFIG.PHP FROM THE PHP-FILES FOLDER.
3 - Upload upgrade.php from upgrade v4.01 folder to the admin folder on your server.
4 - CHMOD config.php to 777.
5 - Login to your site as the Super Administrator and click Upgrade via admin.
6 - Follow all on-screen prompts until you see "Database upgrade complete".
7 - Delete the following folders from your server:
	- fusion_admin
	- fusion_core
	- fusion_forum
	- fusion_languages
	- fusion_panels
	- fusion_themes
8 - Rename the following folders:
	- fusion_images to images
	- fusion_public to forum
9 - Move the avatars folder from the forum folder to the images folder
10 - Upload the contents of the php-files folder to your server.
11 - CHMOD config.php back to 644.
12 - Ensure the following folders are CHMODed 777:
	- administration/db_backups/
	- images/
	- images/articles/
	- images/avatars/
	- images/news/
	- images/photoalbum/
	- forum/attachments/

---------------------------
4. UPGRADE FROM V5.00/V5.01
---------------------------
Before upgrading we strongly recommend that you backup your site and your database.

1 - Uninstall all Infusions via infusions admin.
2 - Disable any 3rd party panels via panels admin except for:
	- Navigation
	- Online Users
	- Forum Threads
	- Latest Articles
	- Welcome Message
	- Forum Threads List
	- User Info
	- Members Poll
	- Shoutbox
3 - Upload config.php from php-files folder to your server.
	- WARNING: YOU MUST DELETE CONFIG.PHP FROM THE PHP-FILES FOLDER.
4 - Upload upgrade.php from upgrade v5.00/5.01 folder to the admin folder on your server.
5 - CHMOD config.php to 777.
6 - Login to your site as the Super Administrator and click Upgrade via admin.
7 - Follow all on-screen prompts until you see "Database upgrade complete".
8 - Delete the following folders from your server:
	- fusion_admin
	- fusion_includes
	- fusion_infusions
	- fusion_forum
	- fusion_languages
	- fusion_themes
9 - Rename the following folders:
	- fusion_images to images
	- fusion_public to forum
10 - Move the avatars folder from the forum folder to the images folder
11 - Upload the contents of the php-files folder to your server.
12 - CHMOD config.php back to 644.
13 - Ensure the following folders are CHMODed 777:
	- administration/db_backups/
	- images/
	- images/articles/
	- images/avatars/
	- images/news/
	- images/photoalbum/
	- forum/attachments/

---------------------------
5. ADDITIONAL NOTES
---------------------------
Please note that most of PHP-Fusion's infrastructure has been completey
overhauled. Whilst most definitions have been renamed, a number have been
dropped completely as they are no longer required. The following table
lists definitions used in v4.01, v5.00/v5.01 & v6.

v4.01			v5.00/v5.01		v6
fusion_root		FUSION_ROOT		-
fusion_base		FUSION_BASE		BASEDIR
-			FUSION_ADMIN		ADMIN
-			FUSION_IMAGES		IMAGES
-			FUSION_IMAGES_A		IMAGES_A
-			FUSION_IMAGES_N		IMAGES_N
-			FUSION_INCLUDES		INCLUDES
fusion_langdir		FUSION_LANGUAGES	LOCALE
-			FUSION_LAN		LOCALESET
-			FUSION_FORUM		FORUM
-			FUSION_INFUSIONS	INFUSIONS
-			FUSION_PHOTOS		PHOTOS
-			FUSION_PUBLIC		PUBLIC
fusion_themedir		FUSION_THEMES		THEMES
-			FUSION_THEME		THEME
$user_ip		FUSION_IP		USER_IP
-			FUSION_QUERY		FUSION_QUERY
$PHP_SELF		$PHP_SELF/FUSION_SELF	FUSION_SELF
-			FUSION_PREFIX		DB_PREFIX
$fusion_prefix		$fusion_prefix		$db_prefix
------------------------------------------------------------
USER DEFINITIONS
------------------------------------------------------------
Guest()			iGUEST			iGUEST
Member()		iMEMBER			iMEMBER
Moderator()		iMOD			-
Admin()			iADMIN			iADMIN
SuperAdmin()		iSUPERADMIN		iSUPERADMIN
UserLevel()		iUSER			iUSER
-			USER_RIGHTS		USER_RIGHTS
-			USER_GROUP		USER_GROUP

You must ensure that any modified code utilises the definitions under v6,
also note that infusions developed for v5x are not compatible with v6 due
to changes in the infusion system.

---------------------------
6. ADDITIONAL CREDITS
---------------------------
Thanks to the following for their additional work:
CrappoMan	-	Additional code and SQL routines
Shedrock	-	Official theme & graphic designer
Mave		-	For exclusive administration icon set
Janmol		-	Market research & additional design concepts
KEFF		-	For silly ideas that aren't actually silly!
Rayxen		-	Additional code & mods
Sheldon		-	Tech support & additional hosting
