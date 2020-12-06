<?php
// Member Management Options
$locale['400'] = "Mitglieder";
$locale['401'] = "Benutzer";
$locale['402'] = "Hinzuf�gen";
$locale['403'] = "Benutzer Typ";
$locale['404'] = "Optionen";
$locale['405'] = "Ansehen";
$locale['406'] = "Editieren";
$locale['407'] = "Entsperren";
$locale['408'] = "Sperren";
$locale['409'] = "L�schen";
$locale['410'] = "Es gibt keine Benutzer die anfangen mit ";
$locale['411'] = "Zeige alle";
$locale['412'] = "Aktivieren";
// Ban/Unban/Delete Member
$locale['420'] = "Sperrung aktiviert";
$locale['421'] = "Sperrung aufgehoben";
$locale['422'] = "Benutzer gel�scht";
$locale['423'] = "Bist Du sicher das Du dieses Mitglied l�schen m�chtest?";
$locale['424'] = "Mitglied aktiviert";
$locale['425'] = "Account aktiviert am ";
$locale['426'] = "Hallo [USER_NAME],\n
Your account at ".$settings['sitename']." has been activated.\n
You can now login using your chosen username and password.\n
Regards,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Mitglied editieren";
$locale['431'] = "Mitglied Details aktualisiert";
$locale['432'] = "Zur�ck zum Benutzer Admin";
$locale['433'] = "Zur�ck zum Admin Index";
$locale['434'] = "Kann Mitglied Details nicht aktualisieren:";
// Extra Edit Member Details form options
$locale['440'] = "�nderungen speichern";
// Update Profile Errors
$locale['450'] = "Kann prim�ren Administrator nicht editieren.";
$locale['451'] = "Du musst einen Benutzernamen und eine E-Mail adresse angeben";
$locale['452'] = "Der Benutzername enth�lt ung�ltige Zeichen.";
$locale['453'] = "Der Benutzername ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." wird bereits verwendet.";
$locale['454'] = "Ung�ltige E-Mail Adresse";
$locale['455'] = "Die E-Mail Adresse ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." wird bereits verwendet.";
$locale['456'] = "Neue Passworte stimmen nicht �berein.";
$locale['457'] = "Ung�ltiges Passwort, benutze nur alphanumerische Zeichen.<br>
Password must be a minimum of 6 characters long.";
$locale['458'] = "<b>Warnung:</b> unerwartete Script Ausf�hrung.";
// View Member Profile
$locale['470'] = "Mitglieds Profil: ";
$locale['471'] = "Generelle Informationen";
$locale['472'] = "Statistiken";
$locale['473'] = "Benutzer Gruppen";
// Add Member Errors
$locale['480'] = "Mitglied hinzuf�gen";
$locale['481'] = "Der Mitglieds-Account wurde erstellt.";
$locale['482'] = "Der Mitglieds-Account konnte nicht erstellt werden.";
?>