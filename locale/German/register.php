<?php
$locale['400'] = "Registrieren";
$locale['401'] = "Account Aktivieren";
// Registration Errors
$locale['402'] = "Du must einen Benutzernamen, Passwort und E-Mail Adresse angeben.";
$locale['403'] = "Benutzername enthält ungültige Zeichen.";
$locale['404'] = "Deine beiden Passwörter stimmen nicht überein.";
$locale['405'] = "Ung&uuml;ltiges Passwort, bitte nur Buchstaben und Ziffern verwenden.<br>
Password muss mindestens 6 Zeichen lang sein.";
$locale['406'] = "Deine Emailadresse sieht ung&uuml;tig aus.";
$locale['407'] = "Leider ist der Benutzername  ".(isset($_POST['username']) ? $_POST['username']:"")." bereits vergeben.";
$locale['408'] = "Leider wird die Email Addresse ".(isset($_POST['email']) ? $_POST['email'] : "")." bereits benutzt.";
$locale['409'] = "Ein gesperrter Account wurde auf dieser E-Mail Adresse angelegt.";
$locale['410'] = "Ung&uuml;ltiger Pr&uuml;fcode.";
$locale['411'] = "Deine E-Mail Adresse oder Domain steht auf der Blacklist.";
// Email Message
$locale['450'] = "Hallo ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Willkommen auf ".$settings['sitename'].". Hier sind deine Zugangsdaten:\n
Benutzername: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Passwort: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Bitte aktiviere deinen Account mit diesen Link:\n";
// Registration Success/Fail
$locale['451'] = "Registration Abgeschlossen";
$locale['452'] = "Du kannst dich nun einloggem.";
$locale['453'] = "Ein Administrator wird deinen Account in kürze aktivieren.";
$locale['454'] = "Deine Registrierung ist abgeschlossen. Du erhälst eine E-Mail mit Deinen Logindaten und einen Link zum aktivieren des Accounts";
$locale['455'] = "Dein Account wurde angenommen.";
$locale['456'] = "Registration Fehlgeschlagen";
$locale['457'] = "Das senden der E-Mail ist fehlgeschlagen, setze Dich bitte mit dem <a href='mailto:".$settings['siteemail']."'>Technischen Administrator</a> in Verbindung.";
$locale['458'] = "Die Anmeldung ist aus folgenden Gr&uuml;nden fehlgeschlagen:";
$locale['459'] = "Bitte probiere es nochmal";
// Register Form
$locale['500'] = "Trage bitte unten Deine Details ein. ";
$locale['501'] = "Eine Überprüfungs E-Mail wird an die von Dir angegebene E-Mail Adresse gesendet. ";
$locale['502'] = "Felder, die mit einem <span style='color:#ff0000;'>*</span> makiert sind, müssen ausgefüllt werden.
Merke dir deinen Benutzernamen und Passwort gut.";
$locale['503'] = " Du kannst jederzeit dein Profil ändern wenn du auf Profil Bearbeiten klickst, wenn du angemeldet bist.";
$locale['504'] = "Prüfungscode:";
$locale['505'] = "Prüfcode eingeben:";
$locale['506'] = "Registrieren";
$locale['507'] = "Die Registration ist zurzeit deaktiviert.";
// Validation Errors
$locale['550'] = "Bitte gebe einen Benutzernamen an.";
$locale['551'] = "Bitte gebe ein Passwort an.";
$locale['552'] = "Bitte gebe eine E-Mail Adresse an.";
?>
