<?php
// Members List
define("LAN_400", "Mitglieder Liste");
define("LAN_401", "Benutzername");
define("LAN_402", "Status");
define("LAN_403", "Es gibt keine Mitglieder die beginnen mit ");
define("LAN_404", "Alle zeigen");
// User Profile
define("LAN_420", "Mitglieds Profil: ");
define("LAN_421", "Allgemeine Informationen");
define("LAN_422", "Statistik");
define("LAN_423", "Benutzer Gruppen");
// Edit Profile
define("LAN_440", "Profil bearbeiten");
define("LAN_441", "Profil erfolgreich aktualisiert");
define("LAN_442", "Kann Profil nicht aktualisieren:");
// Edit Profile Form
define("LAN_460", "Profil aktualisiert");
// Update Profile Errors
define("LAN_480", "Du musst einen Mitgliedsnamen und E-Mail Adresse angeben.");
define("LAN_481", "Der Mitgliedsname beinhaltet ungültige Zeichen.");
define("LAN_482", "Der Mitgliedsname ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." wird bereits verwendet.");
define("LAN_483", "Ungültige E-Mail Adresse.");
define("LAN_484", "Die E-Mail Adresse ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." wird bereits verwendet.");
define("LAN_485", "Die neuen Passwörter stimmen nicht überein.");
define("LAN_486", "Ungültiges Passwort, bitte nur Buchstaben und Ziffern verwenden.<br>
Passwort muss mindestens 6 Zeichen lang sein.");
define("LAN_487", "<b>Warnung:</b> unberechtigte Script Ausführung.");
?>

