PHP-Fusion v5.0
Copyright (C) 2003-2005 Nick Jones
Developed by Nick Jones (Digitanium)

---------------------------
OVERVIEW
---------------------------
PHP-Fusion is a light-weight open-source content management system (CMS) written in PHP4.
It uses a mySQL database to store your site's content and includes a simple comprehensive
secure adminstration system. PHP-Fusion includes the most common features you expect from
other CMS packages including news, articles, forums, polls, shoutbox, comments, ratings &
much more.

PHP-Fusion is free to use for non-profit and charity organisations under the terms and
conditions of version 2 of the GNU/GPL license. For details please visit www.gnu.org or
read the gpl.txt file bundled with this package. You may alter PHP-Fusion any way you
want and redistribute it, but if you do so please leave the original author details and
copyright intact and offer the source-code for download.

---------------------------
REQUIREMENTS:
---------------------------
FTP access to your webspace
PHP4 (v4.1.0 or higher)
GD Extension Library (for Photogallery)
1 mySQL Database

---------------------------
INSTALLATION:
---------------------------
Upload the contents of the html & install folders to your webserver via FTP in
ASCII mode.

1 - CHMOD the following folders to 777:
	fusion_admin
	fusion_admin/db_backups
	fusion_pages
	fusion_public
	fusion_public/attachments
	fusion_public/avatars
	fusion_images
	fusion_images/photoalbum
2 - CHMOD the file fusion_config.php to 777
3 - Run the install.php script from your web browser e.g.
    www.mydomain.com/install.php and follow each step.
4 - For security reasons you should delete install.php from your webserver after
    completing the above.
5 - CHMOD fusion_config.php back to 644.

---------------------------
UPGRADE FROM V4.01:
---------------------------
WARNING: It is highly recommended that you backup your PHP-Fusion site, including
your database, prior to upgrading to PHP-Fusion 5.

You can upgrade to PHP-Fusion 5 from v4.01 by following these steps:

1 - CHMOD your fusion_config.php on your webserver to 777.
2 - Upload the file upgrade.php from the upgrade folder to the fusion_admin
    folder on your webserver via FTP in ASCII mode.
3 - Login to your site as the Super Administrator and go to your Admin Panel,
    then click Upgrade and follow the on-screen prompts.
4 - If the the upgrade reports successful, delete the following folders:
	fusion_themes
	fusion_core
	fusion_panels
	fusion_languages
5 - CHMOD your fusion_config.php back to 644.
6 - Upload the contents of the html folder to your webserver via FTP. 

---------------------------
ADDITIONAL UPGRADE NOTES
---------------------------
PHP-Fusion 5 has a number of core alterations which may affect modified code,
therefor any existing mods must be updated in order to function correctly in
PHP-Fusion 5. The following changes are as follows:

The User Level functions have been replaced with definitions:
Guest()			=	iGUEST
Member() 		=	iMEMBER
Moderator()		=	iMOD
Admin()			=	iADMIN
SuperAdmin()		=	iSUPERADMIN
UserLevel()		=	iUSER

The fusion definations have been changed as follows:
fusion_basedir		=	FUSION_BASE
fusion_themedir		=	FUSION_THEME
fusion_langdir		=	FUSION_LANGUAGES.FUSION_LAN

New definitions:
FUSION_IP		=	Previously stored in $user_ip variable
FUSION_ADMIN		=	The path to the fusion_admin/ folder
FUSION_IMAGES		=	The path to the fusion_images/ folder
FUSION_INCLUDES		=	The path to the new fusion_includes/ folder
FUSION_LANGUAGES	=	The path to the fusion_languages/ folder
FUSION_LAN		=	The language folder as set in site settings
FUSION_FORUM		=	The path to the fusion_forum/ folder
FUSION_INFUSIONS	=	The path to the new fusion_infusions/ folder
FUSION_PHOTOS		=	The path to the fusion_photos/ folder
FUSION_PUBLIC		=	The path to the fusion_public/ folder
FUSION_THEMES		=	The path to the fusion_themes/ folder

---------------------------
GENERAL INFORMATION
---------------------------
If you encounter any problems with this package, please read the FAQ at
www.php-fusion.co.uk, if you are unable to resolve any particular problem please feel
free to post a message on our support forum.