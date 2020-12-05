<?php
define("LAN_200", "Bliv medlem");
// Registration Errors
define("LAN_210", "Du skal opgive et brugernavn, et password og en mailadresse");
define("LAN_211", "Brugernavnet indeholder karakterer, som ikke må bruges");
define("LAN_212", "De to passwords er ikke identiske");
define("LAN_213", "Dit password har ikke det rigtige format. Der må kun bruges alfanumeriske karakterer");
define("LAN_214", "Det ser ikke ud til, at den opgivne mailadresse eksisterer");
define("LAN_215", "Incorrect validation code");
define("LAN_216", "ICQ-nummer er i et forkert format. Der må kun bruges tal");
define("LAN_217", "Det ser ud til, at den opgivne MSN ID ikke findes");
define("LAN_218", "Din Yahoo ID indeholder forkerte karakterer");
define("LAN_219", "Beklager, men brugernavnet ".$_POST['username']." findes allerede.");
define("LAN_220", "Beklager, men mailadressen ".$_POST['email']." er allerede i brug.");
// Email Message
define("LAN_230", "Hej ".$_POST['username'].",<br>
<br>
Velkommen til ".$settings[sitename].", her har du de oplysninger, du skal bruge for at logge på:<br>
<br>
<span class=\"alt\">Brugernavn:</span> ".$_POST['username']."<br>
<span class=\"alt\">Password:</span> ".$_POST['password']."<br>
<br>
Venlig hilsen,<br>
<a href=\"mailto:".$settings[siteemail]."\">".$settings[siteusername]."</a>.\n");
// Registration Success/Fail
define("LAN_240", "Tilmelding er gennemført");
define("LAN_241", "Din tilmelding er gennemført og dine brugeroplysninger er sendt til dig pr. mail");
define("LAN_242", "Tilmelding lykkeds ikke");
define("LAN_243", "Der kunne ikke sendes en mail");
define("LAN_244", "Tilmelding lykkedes ikke af følgende årsage(r):");
define("LAN_245", "Prøv igen");
// Register Form
define("LAN_250", "For at melde dig til skal du udfylde felterne herunder.<br>
Alle felter markeret med <font color=\"rødt\">*</font> skal udfyldes. Vi skal bruge en
fungerende mailadresse til at verificere tilmeldingen. Du kan altid senere rette i dine oplysninger
ved at trykke på <b>Rediger profil</b> når du er logget på.");
define("LAN_251", "Brugernavn:");
define("LAN_252", "Password:");
define("LAN_253", "Gentag password:");
define("LAN_254", "Mailadresse:");
define("LAN_255", "Skal mailadressen skjules for andre?");
define("LAN_256", " Ja ");
define("LAN_257", " Nej");
define("LAN_258", "Sikkerhedskode:");
define("LAN_259", "Enter Validation Code:");
define("LAN_260", "Bopæl:");
define("LAN_261", "ICQ-nummer");
define("LAN_262", "MSN ID:");
define("LAN_263", "Yahoo ID:");
define("LAN_264", "Adresse på hjemmeside:");
define("LAN_265", "Signatur:");
define("LAN_266", "Tilmeld bruger");
?>
