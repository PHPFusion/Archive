<?php
define("LAN_400", "Rejestracja");
// Registration Errors
define("LAN_410", "Podaj email, nick i has�o");
define("LAN_411", "Nick zawiera niedozwolone znaki");
define("LAN_412", "Has�a nie pasuj�");
define("LAN_413", "Has�o zawiera niedozwolone znaki");
define("LAN_414", "Z�y adres email");
define("LAN_415", "Z�e ICQ");
define("LAN_416", "Z�y nr GG");
define("LAN_417", "Z�y id Yahoo");
define("LAN_418", "Nick zaj�ty");
define("LAN_419", "Nick ".$_POST['username']." jest ju� zaj�ty.");
define("LAN_420", "Adres Email ".$_POST['email']." jest ju� zaj�ty.");

// Email Message
define("LAN_430", "Witaj ".$_POST['username'].",<br>
<br>
Witaj w ".$settings[sitename].", oto twoje dane do logowania:<br>
<br>
<span class=\"alt\">Login:</span> ".$_POST['username']."<br>
<span class=\"alt\">Haslo: </span> ".$_POST['password']."<br>
<br>
Pozdrawiam,<br>
<a href=\"mailto:".$settings[siteemail]."\">".$settings[siteusername]."</a>.\n");
// Registration Success/Fail
define("LAN_440", "Rejestracja zako�czona");
define("LAN_441", "Dosta�e� email z danymi");
define("LAN_442", "Rejestracja nie powiod�a si�");
define("LAN_443", "Nie mo�na wys�a� emaila");
define("LAN_444", "Rejestracja nie powiod�a si�");
define("LAN_445", "Spr�buj ponownie");
// Register Form
define("LAN_450", "By zarejestrowa� si� wpisz dane.<br>
Pola oznaczone <font color=\"red\">*</font> s� wymagane.");
define("LAN_451", "Nick:");
define("LAN_452", "Has�o:");
define("LAN_453", "Powt�rz has�o:");
define("LAN_454", "Email:");
define("LAN_455", "Ukry� email?");
define("LAN_456", " Tak ");
define("LAN_457", " Nie");
define("LAN_458", "Kod rejestracji:");
define("LAN_459", "Powt�rz kod rejestracji:");
define("LAN_460", "Miasto:");
define("LAN_461", "ICQ:");
define("LAN_462", "MSN ID:");
define("LAN_463", "Yahoo ID:");
define("LAN_464", "Strona www:");
define("LAN_465", "Sygnatura");
define("LAN_466", "Rejestruj");

?>