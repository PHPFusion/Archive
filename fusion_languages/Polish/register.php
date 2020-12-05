<?php
define("LAN_400", "Rejestracja");
// Registration Errors
define("LAN_410", "Podaj email, nick i has³o");
define("LAN_411", "Nick zawiera niedozwolone znaki");
define("LAN_412", "Has³a nie pasuj±");
define("LAN_413", "Has³o zawiera niedozwolone znaki");
define("LAN_414", "Z³y adres email");
define("LAN_415", "Z³e ICQ");
define("LAN_416", "Z³y nr GG");
define("LAN_417", "Z³y id Yahoo");
define("LAN_418", "Nick zajêty");
define("LAN_419", "Nick ".$_POST['username']." jest ju¿ zajêty.");
define("LAN_420", "Adres Email ".$_POST['email']." jest ju¿ zajêty.");

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
define("LAN_440", "Rejestracja zakoñczona");
define("LAN_441", "Dosta³e¶ email z danymi");
define("LAN_442", "Rejestracja nie powiod³a siê");
define("LAN_443", "Nie mo¿na wys³aæ emaila");
define("LAN_444", "Rejestracja nie powiod³a siê");
define("LAN_445", "Spróbuj ponownie");
// Register Form
define("LAN_450", "By zarejestrowaæ siê wpisz dane.<br>
Pola oznaczone <font color=\"red\">*</font> s± wymagane.");
define("LAN_451", "Nick:");
define("LAN_452", "Has³o:");
define("LAN_453", "Powtórz has³o:");
define("LAN_454", "Email:");
define("LAN_455", "Ukryæ email?");
define("LAN_456", " Tak ");
define("LAN_457", " Nie");
define("LAN_458", "Kod rejestracji:");
define("LAN_459", "Powtórz kod rejestracji:");
define("LAN_460", "Miasto:");
define("LAN_461", "ICQ:");
define("LAN_462", "MSN ID:");
define("LAN_463", "Yahoo ID:");
define("LAN_464", "Strona www:");
define("LAN_465", "Sygnatura");
define("LAN_466", "Rejestruj");

?>