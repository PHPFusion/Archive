<?
/*
-------------------------------------------------------
PHP Fusion X3
-----------------------------------------------
© Nick Jones 2002-2004
http://www.digitaldominion.co.uk
nick@digitaldominion.co.uk
-----------------------------------------------
Released under the terms and conditions of the
GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require "side_left.php";

echo "<td valign=\"top\" class=\"bodybg\">\n";

opentable("Gry Arcade");

echo "<table align=\"center\">
<tr>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/pacman.php\"><IMG SRC=\"fusion_games/images/pacman.gif\" BORDER=\"0\" ALT=\"PacMan\">
</A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/aliensattack.php\"><IMG SRC=\"fusion_games/images/aliensattck.gif\" BORDER=\"0\" ALT=
\"Aliens Attack\"></A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/alien_attack.php\"><IMG SRC=\"fusion_games/images/alien_attack.gif\" BORDER=\"0\" ALT=
\"Alien Attack\"></A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/balance.php\"><IMG SRC=\"fusion_games/images/balance.gif\" BORDER=\"0\" ALT=
\"Balance\"></A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/breakout.php\"><IMG SRC=\"fusion_games/images/breakout.gif\" BORDER=\"0\" ALT=
\"Breakout\"></A></tr>
<tr>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/eat.php\"><IMG SRC=\"fusion_games/images/eat.gif\" BORDER=\"0\" ALT=\"Eat\">
</A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/helicopter.php\"><IMG SRC=\"fusion_games/images/helicopter.gif\" BORDER=\"0\" ALT=
\"helicopter\"></A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/hexxagon.php\"><IMG SRC=\"fusion_games/images/hexxagon.gif\" BORDER=\"0\" ALT=
\"Hexxagon\"></A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/homingmissile.php\"><IMG SRC=\"fusion_games/images/homingmissle.gif\" BORDER=\"0\" ALT=
\"Homing Missile\"></A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/invaders.php\"><IMG SRC=\"fusion_games/images/invaders.gif\" BORDER=\"0\" ALT=
\"Invaders\"></A></tr>
<tr>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/jewels.php\"><IMG SRC=\"fusion_games/images/jewels.gif\" BORDER=\"0\" ALT=\"Jewels\">
</A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/kart_race.php\"><IMG SRC=\"fusion_games/images/kartrace.gif\" BORDER=\"0\" ALT=
\"Kart Race\"></A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/penguin.php\"><IMG SRC=\"fusion_games/images/penguin.gif\" BORDER=\"0\" ALT=
\"Penguin\"></A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/Koopa-Co.php\"><IMG SRC=\"fusion_games/images/koopa-co.gif\" BORDER=\"0\" ALT=
\"Koopa-Collect\"></A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/paratroopers.php\"><IMG SRC=\"fusion_games/images/paratroopers.gif\" BORDER=\"0\" ALT=
\"Paratroopers\"></A></tr>
<tr>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/wargame.php\"><IMG SRC=\"fusion_games/images/wargame.gif\" BORDER=\"0\" ALT=\"Wargame\">
</A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/Roulette.php\"><IMG SRC=\"fusion_games/images/Roulette.gif\" BORDER=\"0\" ALT=
\"Roulette\"></A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/Pingpong.php\"><IMG SRC=\"fusion_games/images/Pingpong.gif\" BORDER=\"0\" ALT=
\"Pingpong\"></A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/shooting.php\"><IMG SRC=\"fusion_games/images/shooting.gif\" BORDER=\"0\" ALT=
\"shooting\"></A>
<TD ALIGN=\"left\">
<A HREF=\"fusion_games/tetris.php\"><IMG SRC=\"fusion_games/images/tetris.gif\" BORDER=\"0\" ALT=
\"tetris\"></A></tr>
</table>";

closetable();

echo "</td>
<td width=\"20\" valign=\"top\" class=\"full-header\">\n";

require "side_right.php";
require "footer.php";
?>
