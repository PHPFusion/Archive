<?php
define("LAN_400", "Rejestracja");
define("LAN_401", "Aktywacja Konta");
// Registration Errors
define("LAN_402", "Musisz poda� sw�j nick, has�o i adres mailowy.");
define("LAN_403", "Tw�j nick zawiera niedozwolone znaki.");
define("LAN_404", "Podane przez Ciebie has�a r�ni� si� od siebie.");
define("LAN_405", "Niew�a�ciwe has�o. Mo�esz u�ywa� jedynie liczb i liter, bez znak�w specjalnych.<br>
Has�o musi mie� d�ugo�� przynajmniej 6 znak�w.");
define("LAN_406", "Podany przez Ciebie adres e-mailowy wydaje si� by� niepoprawny.");
define("LAN_407", "Nick ".(isset($_POST['username']) ? $_POST['username'] : "")." jest ju� przez kogo� u�ywany.");
define("LAN_408", "Adres e-mailowy ".(isset($_POST['email']) ? $_POST['email'] : "")." jest juz zarezerwowany.");
define("LAN_409", "Nieaktywne konto zosta�o zarejestrowane.");
define("LAN_410", "Niepoprawny Kod.");
// Email Message
define("LAN_450", "Witaj ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Witaj w gronie u�ytkownik�w witryny ".$settings['sitename'].". Oto dane niezb�dne do zalogowania si�:\n
Nick: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Has�o: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Aby aktywowa� swoje konto, kliknij w poni�szy link:\n");
// Registration Success/Fail
define("LAN_451", "Rejestracja zako�czona, mo�esz teraz zalogowa� si� u�ywaj�c podanych wcze�niej danych.");
define("LAN_452", "Rejestracja niemal dobieg�a ko�ca. Teraz musisz tylko aktywowa� konto poprzez link znajduj�cy si� w li�cie wys�anym na podany przez Ciebie adres e-mailowy.");
define("LAN_453", "Konto aktywne. Mo�esz ju� si� logowa�.");
define("LAN_454", "Rejestracja nie powiod�a si�");
define("LAN_455", "Wysy�anie maila nie zadzia�a�o, skontaktuj si� z <a href='mailto:".$settings['siteemail']."'>Administratorem Witryny</a>.");
define("LAN_456", "Rejestracja nie powiod�a sie z poni�szego powodu/powod�w:");
define("LAN_457", "Spr�buj ponownie");
// Register Form
define("LAN_500", "Wype�nij poni�sze pola. ");
define("LAN_501", "Mail zawieraj�cy link aktywacyjny zostanie dostarczony na wskazany przez Ciebie adres e-mail. ");
define("LAN_502", "Pola oznaczone <span style='color:#ff0000;'>*</span> s� obowi�zkowe.
Wielko�� liter w nicku i ha�le ma znaczenie.");
define("LAN_503", " Kiedy jeste� zalogowany, mo�esz w ka�dej chwili edytowa� sw�j profil.");
define("LAN_504", "Kod Potwierdzaj�cy:");
define("LAN_505", "Wpisz powy�szy kod:");
define("LAN_506", "Rejestruj");
define("LAN_507", "System rejestracji zosta� tymczasowo dezaktywowany przez Administratora witryny.");
// Validation Errors
define("LAN_550", "Musisz poda� sw�j nick.");
define("LAN_551", "Musisz poda� has�o.");
define("LAN_552", "Musisz poda� sw�j adres e-mail.");
?>
