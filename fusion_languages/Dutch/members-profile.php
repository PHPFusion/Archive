<?php
// Ledenlijst
define("LAN_400", "Ledenlijst");
define("LAN_401", "Lidnaam");
define("LAN_402", "Gebruikerstype");
define("LAN_403", "Er zijn geen leden met een gebruikersnaam die begint met ");
define("LAN_404", "Iedereen");
// Gebruikersprofiel
define("LAN_420", "Profiel van lid: ");
define("LAN_421", "Algemene informatie");
define("LAN_422", "Statistieken");
define("LAN_423", "Gebruikersgroepen");
// Profiel bewerken
define("LAN_440", "Profiel wijzigen");
define("LAN_441", "Profiel met succes gewijzigd");
define("LAN_442", "Wijzigen van het profiel mislukt:");
// Formulier profiel bewerken
define("LAN_460", "Profiel wijzigen");
// Fouten profiel bijwerken
define("LAN_480", "Lidnaam en emailadres zijn verplicht.");
define("LAN_481", "Lidnaam bevat ongeldige tekens.");
define("LAN_482", "De lidnaam ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." is al in gebruik.");
define("LAN_483", "Ongeldig emailadres.");
define("LAN_484", "Het emailadres ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." is al in gebruik.");
define("LAN_485", "Nieuwe wachtwoorden zijn niet gelijk.");
define("LAN_486", "Ongeldig wachtwoord, gebruik alleen alfanumerieke tekens.<br>
Wachtwoord moet minstens 6 tekens lang zijn.");
define("LAN_487", "<b>Waarschuwing:</b> Onverwachte script fout.");
?>