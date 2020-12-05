<?php
define("LAN_200", "Registreer");
// Registration Errors
define("LAN_210", "U moet een gebruikersnaam, wachtwoord & e-mail adres opgeven");
define("LAN_211", "Gebruikersnaam bevat ongeldige karakters");
define("LAN_212", "Uw twee wachtwoorden zijn niet hetzelfde");
define("LAN_213", "Ongeldig wachtwoord, gebruik alleen alfa numerieke karakters");
define("LAN_214", "Uw e-mail adres is niet geldig");
define("LAN_215", "Incorrect validation code");
define("LAN_216", "Ongeldig ICQ#, gebruik alleen numerieke karakters");
define("LAN_217", "MSN ID lijkt ongeldig te zijn");
define("LAN_218", "Yahoo ID bevat ongeldige karakters");
define("LAN_219", "Sorry, de gebruikersnaam ".$_POST['username']." is al in gebruik.");
define("LAN_220", "Sorry, het e-mailadres ".$_POST['email']." is al in gebruik.");
// Email Message
define("LAN_230", "Hallo ".$_POST['username'].",<br>
<br>
Welkom bij ".$settings[sitename].", hier zijn uw inloggegevens:<br>
<br>
<span class=\"alt\">Gebruikersnaam:</span> ".$_POST['username']."<br>
<span class=\"alt\">Wachtwoord:</span> ".$_POST['password']."<br>
<br>
Met vriendelijk groet,<br>
<a href=\"mailto:".$settings[siteemail]."\">".$settings[siteusername]."</a>.\n");
// Registration Success/Fail
define("LAN_240", "U bent succesvol geregistreerd");
define("LAN_241", "De registratie is succesvol verlopen, uw inloggegevens zijn toegemaild");
define("LAN_242", "Registratie mislukt");
define("LAN_243", "Kon geen e-mail versturen");
define("LAN_244", "Registratie is mislukt om de volgende reden(en):");
define("LAN_245", "Probeer het nogmaals");
// Register Form
define("LAN_250", "Om u te registreren dient u onderstaand formulier in te vullen.<br>
Velden gemarkeerd met een <font color=\"red\">*</font> zijn verplicht. Uw registratie zal worden gevalideerd aan de hand van het opgegeven e-mail adres. U kunt uw gegevens te allen tijde wijzigen door, wanneer u bent ingelogd, op profiel bewerken te klikken.");
define("LAN_251", "Gebruikersnaam:");
define("LAN_252", "Wachtwoord:");
define("LAN_253", "Herhaal wachtwoord:");
define("LAN_254", "E-mail adres:");
define("LAN_255", "Verberg e-mail?");
define("LAN_256", " Ja ");
define("LAN_257", " Nee");
define("LAN_258", "Validatie code:");
define("LAN_259", "Vul de validatie code in:");
define("LAN_260", "Lokatie:");
define("LAN_261", "ICQ#");
define("LAN_262", "MSN ID:");
define("LAN_263", "Yahoo ID:");
define("LAN_264", "Website URL:");
define("LAN_265", "Handtekening:");
define("LAN_266", "Registreer");
?>