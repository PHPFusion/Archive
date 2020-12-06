<?php
/*
English Language Fileset
Produced by Nick Jones (Digitanium)
Email: digitanium@php-fusion.co.uk
Web: http://www.php-fusion.co.uk
*/

// Locale Settings
setlocale(LC_TIME, "lt_LT"); // Linux Server (Windows may differ)
$locale['charset'] = "windows-1257";
$locale['tinymce'] = "lt";

// Full & Short Months
$locale['months'] = "&nbsp|Sausis|Vasaris|Kovas|Balandis|Gegu��|Bir�elis|Liepa|Rugpj�tis|Rugs�jis|Spalis|Lapkritis|Gruodis";
$locale['shortmonths'] = "&nbsp|Sau|Vas|Kov|Bal|Geg|Bir|Lie|Rug|Rgs|Spa|Lap|Gru";

// Standard User Levels
$locale['user0'] = "Sve�ias";
$locale['user1'] = "Narys";
$locale['user2'] = "Administratorius";
$locale['user3'] = "Super administratorius";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderatorius";
// Navigation
$locale['001'] = "Navigacija";
$locale['002'] = "N�ra apib�dint� nuorod�\n";
$locale['003'] = "Tik nariams";
$locale['004'] = "�iai panelei n�ra turinio";
// Users Online
$locale['010'] = "Vartotoj� tinkle";
$locale['011'] = "Sve�i� prisijungta: ";
$locale['012'] = "Nari� prisijungta: ";
$locale['013'] = "N�ra nari� prisijungusi�";
$locale['014'] = "Registruoti nariai: ";
$locale['015'] = "Neaktyv�s nariai: ";
$locale['016'] = "Naujausias narys: ";
// Sidebar
$locale['020'] = "Forumo prane�imai";
$locale['021'] = "Nauji prane�imai";
$locale['022'] = "Populiari� prane�im�";
$locale['023'] = "Paskutiniai straipsniai";
// Welcome Title & Forum List
$locale['024'] = "Pasveikinimas";
$locale['025'] = "Paskutiniai aktyv�s forumo prane�imai";
$locale['026'] = "Mano paskutinis prane�imas";
$locale['027'] = "Mano paskutinis �inut�";
$locale['035'] = "Tema";
$locale['036'] = "�inut�";
$locale['037'] = "Jus neturite joki� temu forume.";
$locale['038'] = "Jus neturite joki� prane�im� forume.";
$locale['030'] = "Forumas";
$locale['031'] = "Prane�imas";
$locale['032'] = "�i�r�ta";
$locale['033'] = "Atsakymai";
$locale['034'] = "Paskutinis prane�imas";
// News & Articles
$locale['040'] = "Para�� ";
$locale['041'] = "nuo ";
$locale['042'] = "Skaityti daugiau";
$locale['043'] = " Komentarai";
$locale['044'] = " Skaityta";
$locale['045'] = "Spausdinti";
$locale['046'] = "Naujienos";
$locale['047'] = "Kol kas naujien� n�ra";
$locale['048'] = "Redaguoti";
// Prev-Next Bar
$locale['050'] = "Ankst";
$locale['051'] = "Sek";
$locale['052'] = "Puslapis ";
$locale['053'] = " i� ";
// User Menu
$locale['060'] = "Registravimasis";
$locale['061'] = "Vartotojas";
$locale['062'] = "Slapta�odis";
$locale['063'] = "Atsiminti mane";
$locale['064'] = "Prisijungti";
$locale['065'] = "Dar ne narys?<br><a href='".BASEDIR."register.php' class='side'><b>Registruotis</b></a>";
$locale['066'] = "Pamir�ai slapta�od�?<a href='".BASEDIR."lostpassword.php' class='side'><br><b>U�klausk</b></a>";
//
$locale['080'] = "Redaguoti profil�";
$locale['081'] = "Asmenin�s �inut�s";
$locale['082'] = "Nari� s�ra�as";
$locale['083'] = "Administracijos panel�";
$locale['084'] = "Atsijungti";
$locale['085'] = "J�s turite %u naujas ";
$locale['086'] = "�inut�";
$locale['087'] = "�inutes";
// Poll
$locale['100'] = "Apklausa";
$locale['101'] = "�skai�iuoti bals�";
$locale['102'] = "Nor�damas balsuoti turite prisijungti.";
$locale['103'] = "Balsuoti";
$locale['104'] = "Balsai";
$locale['105'] = "Balsai: ";
$locale['106'] = "Prad�tas: ";
$locale['107'] = "Baigtas: ";
$locale['108'] = "Apklaus� archyvas";
$locale['109'] = "Pasirinkti apklaus� per�i�rai i� s�ra�o:";
$locale['110'] = "�i�r�ti";
$locale['111'] = "Per�i�r�ti apklaus�";
// Shoutbox
$locale['120'] = "Mini-�atas";
$locale['121'] = "Vardas:";
$locale['122'] = "�inut�:";
$locale['123'] = "Sakyti";
$locale['124'] = "Pagalba";
$locale['125'] = "Jei norite ra�yti �inutes, turite prisijungti.";
$locale['126'] = "Mini-�ato archyvas";
$locale['127'] = "N�ra nauj� �inu�i�.";
// Footer Counter
$locale['140'] = "Unikalus apsilankymas";
$locale['141'] = $settings['sitename']." Unikali� apsilankym�";
// Admin Navigation
$locale['150'] = "Administracija";
$locale['151'] = "Gr��ti � Sait�";
$locale['152'] = "Administracijos panel�s";
// Miscellaneous
$locale['190'] = "Palaikymo re�imas aktyvuotas";
$locale['191'] = "J�s� IP adresas juod�jame s�ra�e.";
$locale['192'] = "Atsijungti kaip ";
$locale['193'] = "Prisijungti kaip ";
$locale['194'] = "J�s� dalyvavimas sustabdytas.";
$locale['195'] = "�is prisijungimas neaktyvuotas.";
$locale['196'] = "Neteisingas vardas arba slapta�odis.";
$locale['197'] = "Palaukite, kol mes atidarin�jame...<br><br>
[ <a href='index.php'>Arba spauskite, jei nenorite laukti</a> ]";
$locale['198'] = "<b>D�mesio:</b> aptiktas setup.php, nedelsiant j� i�trinkite";
?>