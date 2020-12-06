<?php
// Members List
$locale['400'] = "Mitgliederliste";
$locale['401'] = "Benutzername";
$locale['402'] = "Status";
$locale['403'] = "Es gibt keine Mitglieder die beginnen mit ";
$locale['404'] = "Alle zeigen";
// User Profile
$locale['420'] = "Mitglieder-Profil: ";
$locale['421'] = "Allgemeine Informationen";
$locale['422'] = "Statistik";
$locale['423'] = "Benutzergruppen";
// Edit Profile
$locale['440'] = "Profil bearbeiten";
$locale['441'] = "Profil erfolgreich aktualisiert";
$locale['442'] = "Kann Profil nicht aktualisieren:";
// Edit Profile Form
$locale['460'] = "Profil Speichern";
// Update Profile Errors
$locale['480'] = "Du musst einen Mitgliedsnamen und E-Mail Adresse angeben.";
$locale['481'] = "Der Mitgliedsname beinhaltet ungültige Zeichen.";
$locale['482'] = "Der Mitgliedsname ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." wird bereits verwendet.";
$locale['483'] = "Ungültige E-Mail Adresse.";
$locale['484'] = "Die E-Mail Adresse ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." wird bereits verwendet.";
$locale['485'] = "Die neuen Passwörter stimmen nicht überein.";
$locale['486'] = "Ungültiges Passwort, bitte nur Buchstaben und Ziffern verwenden.<br>
Passwort muss mindestens 6 Zeichen lang sein.";
$locale['487'] = "<b>Warnung:</b> unberechtigte Script Ausführung.";
?>
