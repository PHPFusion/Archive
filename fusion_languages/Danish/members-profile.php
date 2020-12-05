<?php
// Members List
define("LAN_400", "Medlemsliste");
define("LAN_401", "Brugernavn");
define("LAN_402", "Brugerstatus");
define("LAN_403", "Der er ikke fundet brugere hvis navne begynder med ");
define("LAN_404", "Vis alle");
// User Profile
define("LAN_420", "Brugerprofil: ");
define("LAN_421", "Generel information");
define("LAN_422", "Statistik");
define("LAN_423", "Brugergrupper");
// Edit Profile
define("LAN_440", "Rediger profil");
define("LAN_441", "Profilen er opdateret");
define("LAN_442", "Var ikke i stand til at opdatere profil:");
// Edit Profile Form
define("LAN_460", "Opdater profil");
// Update Profile Errors
define("LAN_480", "Du skal angive brugernavn og mailadresse.");
define("LAN_481", "Brugernavnet indeholder ugyldige karakterer.");
define("LAN_482", "Brugernavnet ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." bruges allerede.");
define("LAN_483", "Mailadressen er forkert.");
define("LAN_484", "Mailadressen ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." bruges allerede.");
define("LAN_485", "Dine kodeord er ikke identiske.");
define("LAN_486", "Ugyldigt password, anvend kun alfanumeriske karakterer.<br>
Password skal være på mindst 6 karakterer.");
define("LAN_487", "<b>Advarsel:</b> uventet script afvikling.");
?>
