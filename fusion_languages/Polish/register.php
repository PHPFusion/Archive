<?php
define("LAN_400", "Rejestracja");
define("LAN_401", "Aktywacja konta");
// Registration Errors
define("LAN_402", "Podaj login i email.");
define("LAN_403", "Login zawiera niedozwolone znaki.");
define("LAN_404", "Has�a nie pasuj�.");
define("LAN_405", "Niedozwolone znaki w ha�le. U�ywaj liter i cyfr.<BR>Minimalna d�ugo�� 6 znak�w.");
define("LAN_406", "Z�y email.");
define("LAN_407", "Login ".$_POST['username']." zaj�ty.");
define("LAN_408", "Email ".$_POST['email']." zaj�ty.");
define("LAN_409", "Nieaktywne konto zosta�o zarejestrowane");
define("LAN_410", "Z�y kod potwierdzaj�cy");
// Email Message
define("LAN_450", "Witaj ".$_POST['username'].",\n
Witaj na stronie ".$settings['sitename'].". Szczeg�y konta:\n
Login: ".$_POST['username']."
Has�o: ".$_POST['password1']."\n
Aktywuj konto poprzez link:\n");
// Registration Success/Fail
define("LAN_451", "Rejestracja zako�czona");
define("LAN_452", "Dosta�e� email z danymi");
define("LAN_453", "Konto aktywowane.");
define("LAN_454", "Rejestracja nie powiod�a si�");
define("LAN_455", "Wys�anie emaila nie powiod�o si�. Skontaktuj si� z <a href='mailto:".$settings['siteemail']."'>Adminem</a>.");
define("LAN_456", "Rejestracja nie powiod�a si�:");
define("LAN_457", "Spr�buj ponownie");
// Register Form
define("LAN_500", "Podaj dane. ");
define("LAN_501", "Email zostanie wys�any na podany email. ");
define("LAN_502", "Pola oznacznone <span style='color:#ff0000;'>*</span> musz� by� wype�nione.
Wielko�� liter ma znaczenie.");
define("LAN_503", " Dodatkowe dane mo�esz wprowadzi� edytuj�c profil po zalogowaniu.");
define("LAN_504", "Kod potwierdzaj�cy:");
define("LAN_505", "Podaj kod potwierdzaj�cy:");
define("LAN_506", "Rejestruj");
define("LAN_507", "Rejestracja wy��czona.");
// Validation Errors
define("LAN_550", "Podaj login.");
define("LAN_551", "Podaj has�o.");
define("LAN_552", "Podaj email.");
?>