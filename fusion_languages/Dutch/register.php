<?php
define("LAN_400", "Registreer");
// Registration Errors
define("LAN_410", "U moet een gebruikersnaam, wachtwoord & e-mail adres opgeven");
define("LAN_411", "Gebruikersnaam bevat ongeldige karakters");
define("LAN_412", "Uw twee wachtwoorden zijn niet hetzelfde");
define("LAN_413", "Ongeldig wachtwoord, gebruik alleen alfa numerieke karakters");
define("LAN_414", "Uw e-mail adres is niet geldig");
define("LAN_415", "Incorrecte validatie code");
define("LAN_416", "Ongeldig ICQ#, gebruik alleen numerieke karakters");
define("LAN_417", "MSN ID lijkt ongeldig te zijn");
define("LAN_418", "Yahoo ID bevat ongeldige karakters");
define("LAN_419", "Sorry, de gebruikersnaam ".$_POST['username']." is al in gebruik.");
define("LAN_420", "Sorry, het e-mailadres ".$_POST['email']." is al in gebruik.");
// Email Message
define("LAN_430", "Hallo ".$_POST['username'].",<br>
<br>
Welkom bij ".$settings[sitename].", hier zijn uw inloggegevens:<br>
<br>
<span class=\"alt\">Gebruikersnaam:</span> ".$_POST['username']."<br>
<span class=\"alt\">Wachtwoord:</span> ".$_POST['password']."<br>
<br>
Met vriendelijk groet,<br>
<a href=\"mailto:".$settings[siteemail]."\">".$settings[siteusername]."</a>.\n");
// Registration Success/Fail
define("LAN_440", "U bent succesvol geregistreerd");
define("LAN_441", "De registratie is succesvol verlopen, uw inloggegevens zijn toegemaild");
define("LAN_442", "Registratie mislukt");
define("LAN_443", "Kon geen e-mail versturen");
define("LAN_444", "Registratie is mislukt om de volgende reden(en):");
define("LAN_445", "Probeer het nogmaals");
// Register Form
define("LAN_450", "Om u te registreren dient u onderstaand formulier in te vullen.<br>
Velden gemarkeerd met een <font color=\"red\">*</font> zijn verplicht. Uw registratie zal worden gevalideerd aan de hand van het opgegeven e-mail adres. U kunt uw gegevens te allen tijde wijzigen door, wanneer u bent ingelogd, op profiel bewerken te klikken.");
define("LAN_451", "Gebruikersnaam:");
define("LAN_452", "Wachtwoord:");
define("LAN_453", "Herhaal wachtwoord:");
define("LAN_454", "E-mail adres:");
define("LAN_455", "Verberg e-mail?");
define("LAN_456", " Ja ");
define("LAN_457", " Nee");
define("LAN_458", "Validatie code:");
define("LAN_459", "Vul de validatie code in:");
define("LAN_460", "Lokatie:");
define("LAN_461", "ICQ#");
define("LAN_462", "MSN ID:");
define("LAN_463", "Yahoo ID:");
define("LAN_464", "Website URL:");
define("LAN_465", "Handtekening:");
define("LAN_466", "Registreer");
?>