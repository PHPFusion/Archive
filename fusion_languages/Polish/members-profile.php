<?php
// Members List
define("LAN_400", "Lista U�ytkownik�w");
define("LAN_401", "Nick");
define("LAN_402", "Ranga");
define("LAN_403", "Nie ma u�ytkownik�w, kt�rych nicki rozpoczynaj� si� na ");
define("LAN_404", "Poka� wszystkich");
// User Profile
define("LAN_420", "Profil U�ytkownika ");
define("LAN_421", "Og�lne Informacje");
define("LAN_422", "Statystyki");
define("LAN_423", "Przynale�no��");
// Edit Profile
define("LAN_440", "Edytuj Profil");
define("LAN_441", "Tw�j profil zosta� uaktualniony");
define("LAN_442", "Profil nie m�g� zosta� uaktualniony:");
// Edit Profile Form
define("LAN_460", "Uaktualnij Profil");
// Update Profile Errors
define("LAN_480", "Musisz poda� nick oraz adres e-mailowy.");
define("LAN_481", "Nick zawiera niedozwolone znaki.");
define("LAN_482", "Nick ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." jest ju� przez kogo� u�ywany.");
define("LAN_483", "Niepoprawny adres e-mail.");
define("LAN_484", "Adres e-mailowy ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." jest ju� zarezerwowany.");
define("LAN_485", "Podane has�a nie s� identyczne.");
define("LAN_486", "Niew�a�ciwe has�o. Mo�esz u�ywa� jedynie liczb i liter, bez znak�w specjalnych.<br>
Has�o musi mie� d�ugo�� przynajmniej 6 znak�w.");
define("LAN_487", "<b>Uwaga:</b> nieoczekiwane wykonanie skryptu.");
?>
