<?php
// Members List
$locale['400'] = "Liste des membres";
$locale['401'] = "Pseudo";
$locale['402'] = "type d'utilisateur";
$locale['403'] = "Il n'y a pas de pseudo commen�ant par ";
$locale['404'] = "Montr� tout";
// User Profile
$locale['420'] = "Profil Membre: ";
$locale['421'] = "Information G�n�rale";
$locale['422'] = "Statistiques";
$locale['423'] = "Groupe utilisateurs";
// Edit Profile
$locale['440'] = "Editer Profil";
$locale['441'] = "Profil mis � jour avec succ�s";
$locale['442'] = "Impossible de mettre � jour le profil:";
// Edit Profile Form
$locale['460'] = "Mise � jour Profil";
// Update Profile Errors
$locale['480'] = "Vous devez sp�cifier un pseudo et une adresse Email.";
$locale['481'] = "Votre pseudo contient des caract�res invalides.";
$locale['482'] = "Le pseudo ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." est d�j� utilis�.";
$locale['483'] = "Adresse Email non valide.";
$locale['484'] = "L'adresse Email ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." est utilis�e.";
$locale['485'] = "Le nouveau mots de passe ne correspondent pas.";
$locale['486'] = "Mot de passe non valide, utilisez des caract�res alphanum�riques seulement.<br>
Le mot de passe doit avoir un  minimum de 6 caract�res.";
$locale['487'] = "<b>Attention:</b> Ex�cution d'un script innatendue.";
?>
