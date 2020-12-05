PHP-Fusion v4.01
Released August 8th 2004
Copyright (C) 2003-2004 Nick Jones
Written by Nick Jones (Digitanium)

---------------------------
OVERVIEW
---------------------------
PHP-Fusion is an all-in-one content management system (CMS) written in PHP4. It uses a mySQL database to
store all of it's content such as News, Articles, Forum Posts, Shoutbox Posts and more. PHP-Fusion is
free to use for non-profit and charity organisations under the terms and conditions of version 2 of the GNU
GPL license. For details please visit http://www.gnu.org or read the gpl.txt file bundled with this package.

You may alter PHP-Fusion any way you want and redistribute it, but if you do so please leave the original
author details and copyright intact and offer the source-code for download.

Thankyou for trying PHP-Fusion.

---------------------------
REQUIREMENTS:
---------------------------
FTP access to your webspace
PHP4 (v4.1.0 or higher)
1 mySQL Database

---------------------------
INSTALLATION:
---------------------------
Upload the contents of the html folder to your webserver via FTP in ASCII mode.

1 - CHMOD the following folders to 777:-
	fusion_admin
	fusion_admin/db_backups
	fusion_pages
	fusion_public
	fusion_public/attachments
	fusion_public/avatars
	fusion_images
	fusion_images/photoalbum
2 - CHMOD the file fusion_config.php to 777
3 - Run the install.php script from your web browser e.g. www.mydomain.com/install.php and follow each step.
4 - For security reasons you should delete install.php from your webserver after completing the above.
5 - CHMOD fusion_config.php back to 644.

If you encounter any problems with this package, please visit http://www.php-fusion.co.uk/
and post a message in the support forum.