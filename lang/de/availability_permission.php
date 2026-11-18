<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <https://www.gnu.org/licenses/>.

/**
 * German strings for the permission condition.
 *
 * @package    availability_permission
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['adminhelp'] = 'Hilfe zur Voraussetzung „Berechtigung“';
$string['adminhelp_catalogue'] = 'Berechtigungen für den Kurseditor auswählen';
$string['adminhelp_catalogue_article'] = '<p>Kurs- und Systemrechte haben je ein Suchfeld. Suche nach der Moodle-Bezeichnung oder der Capability-ID, etwa <code>moodle/course:viewparticipants</code>. Sind Rechte aus beiden Bereichen freigegeben, wählen Kursbearbeitende in der Voraussetzung zuerst den Bereich.</p><p>Bei Kursrechten prüft Moodle die Rollen und Überschreibungen im jeweiligen Kurs. Bei Systemrechten zählt die Berechtigung auf Website-Ebene. Wer die Rechte hat, legst Du in der Moodle-Rollenverwaltung fest.</p>';
$string['adminhelp_saved'] = 'Was mit gespeicherten Regeln passiert';
$string['adminhelp_saved_article'] = '<p>Wenn Du eine Berechtigung abwählst, gilt sie in bestehenden Voraussetzungen weiter. Beim erneuten Öffnen der Regel siehst Du, dass die Berechtigung für neue Regeln ausgeblendet ist.</p><p>Fällt die Berechtigung selbst weg, etwa durch Deinstallation des zugehörigen Plugins, bleibt die Bedingung unerfüllt. Prüfe vor der Deinstallation die betroffenen Kurse. Für wiederhergestellte Regeln auf einer anderen Moodle-Website muss dieselbe Berechtigung auch dort freigegeben sein.</p>';
$string['adminhelpmore'] = 'Mehr dazu im Hilfepanel';
$string['adminhelptopics'] = 'Hilfethemen';
$string['catalogue'] = 'Auswahl im Kurseditor';
$string['catalogue_help'] = 'Wähle, welche Berechtigungen Kursbearbeitende für neue Voraussetzungen auswählen können.';
$string['cataloguedescription'] = 'Abgewählte Berechtigungen verschwinden aus der Auswahl für neue Regeln. Gespeicherte Regeln gelten weiter.';
$string['catalogueretired'] = '{$a} (für neue Regeln ausgeblendet)';
$string['cataloguesearch_course'] = 'Recht suchen';
$string['cataloguesearch_system'] = 'Recht suchen';
$string['description'] = 'Prüft, ob die Person eine Berechtigung im Kurs oder systemweit hat.';
$string['error_selectcapability'] = 'Wähle eine Berechtigung aus.';
$string['invalidcatalogue'] = 'Wähle Berechtigungen, die Moodle für den Kurs- oder Systemkontext registriert hat.';
$string['pluginname'] = 'Voraussetzung nach Berechtigung';
$string['privacy:metadata'] = 'Die Voraussetzung „Berechtigung“ speichert keine personenbezogenen Daten.';
$string['requires'] = 'Du hast die Berechtigung „{$a}“.';
$string['requires_not'] = 'Dir fehlt die Berechtigung „{$a}“.';
$string['requires_unavailable'] = 'Die eingestellte Berechtigung ist nicht verfügbar.';
$string['scope'] = 'Bereich der Berechtigung';
$string['scope_course'] = 'Kursrechte';
$string['scope_system'] = 'Systemrechte';
$string['title'] = 'Berechtigung';
