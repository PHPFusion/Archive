<?php
define("LAN_400", "Anmelden");
define("LAN_401", "Account aktivieren");
// Registration Errors
define("LAN_402", "Du musst einen Benutzernamen und Deine Email Adresse angeben.");
define("LAN_403", "Der Benutzername beinhaltet ung&uuml;ltige Zeichen.");
define("LAN_404", "Deine beiden Passw&ouml;rter stimmen nicht &uuml;berein.");
define("LAN_405", "Ung&uuml;ltiges Passwort, bitte nur Buchstaben und Ziffern verwenden.<br>
Password muss mindestens 6 Zeichen lang sein.");
define("LAN_406", "Deine Emailadresse sieht ung&uuml;tig aus.");
define("LAN_407", "Leider ist der Benutzername  ".$_POST['username']." bereits vergeben.");
define("LAN_408", "Leider wird die Email Addresse ".$_POST['email']." bereits benutzt.");
define("LAN_409", "Ein gesperrter Account wurde auf dieser E-Mail Adresse angelegt.");
define("LAN_410", "Ung&uuml;ltiger Pr&uuml;fcode.");
// Email Message
define("LAN_450", "Hallo ".$_POST['username'].",\n
Willkommen auf ".$settings['sitename'].". Hier sind Deine Zugangsdaten:\n
Mitgliedsname: ".$_POST['username']."
Password: ".$_POST['password1']."\n
Bitte aktiviere Deine Account mit diesem Link:\n");
// Registration Success/Fail
define("LAN_451", "Registration abgeschlossen, Du kannst Dich nun einloggen.");
define("LAN_452", "Deine Registrierung ist abgeschlossen. Du erhälst eine E-Mail mit Deinen Logindaten und einen Link zum aktivieren des Accounts.");
define("LAN_453", "Dein Account wurde aktiviert, Du kannst Dich nun einloggen.");
define("LAN_454", "Registration fehlgeschlagen");
define("LAN_455", "Das senden der E-Mail ist fehlgeschlagen, setze Dich bitte mit dem <a href='mailto:".$settings['siteemail']."'>Technischen Administrator</a> in Verbindung.");
define("LAN_456", "Die Anmeldung ist aus folgenden Gr&uuml;nden fehlgeschlagen:");
define("LAN_457", "Bitte nochmals versuchen");
// Register Form
define("LAN_500", "Trage bitte unten Deine Details ein. ");
define("LAN_501", "Eine Überprüfungs E-Mail wird an die von Dir angegebene E-Mail Adresse gesendet. ");
define("LAN_502", "Felder mit <font color=\"red\">*</font> Markierung sind anzugeben, die anderen Felder sind wahlfrei.");
define("LAN_503", " Du kannst alle diese Details jederzeit &auml;ndern, indem Du auf Profil bearbeiten klickst, sobald Du angemeldet bist.");
define("LAN_504", "Pr&uuml;fcode:");
define("LAN_505", "Pr&uuml;fcode eingeben:");
define("LAN_506", "Registrieren");
define("LAN_507", "Die Registrierung ist zur Zeit deaktiviert.");
// Validation Errors
define("LAN_550", "Bitte gib einen Benutzernamen an.");
define("LAN_551", "Bitte gib ein Password an.");
define("LAN_552", "Bitte gib eine E-Mail Addresse an.");
?>
