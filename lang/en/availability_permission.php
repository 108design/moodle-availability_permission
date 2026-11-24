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
 * English strings for the permission condition.
 *
 * @package    availability_permission
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['adminhelp'] = 'Permission restriction help';
$string['adminhelp_catalogue'] = 'Choose permissions for the course editor';
$string['adminhelp_catalogue_article'] = '<p>Course and system permissions each have their own search. Search by the Moodle label or capability ID, such as <code>moodle/course:viewparticipants</code>. When both groups have approved permissions, editors choose the group first in the condition.</p><p>Moodle checks course permissions in the course, including role overrides. It checks system permissions at site level. Use Moodle’s role settings to assign permissions.</p>';
$string['adminhelp_saved'] = 'What happens to saved conditions';
$string['adminhelp_saved_article'] = '<p>Removing a permission from the selection keeps it in any condition that already uses it. When you reopen a rule, you can see that the permission is hidden for new conditions.</p><p>If the capability itself disappears, for example after uninstalling the plugin that defines it, the condition stays unmet. Review affected courses before removing that plugin. On another site, approve the same capability there to use it in restored conditions.</p>';
$string['adminhelpmore'] = 'More in the help panel';
$string['adminhelptopics'] = 'Help topics';
$string['catalogue'] = 'Editor permissions';
$string['catalogue_help'] = 'Choose which permissions appear when course editors add a new condition.';
$string['cataloguedescription'] = 'Clearing a selection hides that permission for new conditions. Saved conditions continue to apply.';
$string['catalogueretired'] = '{$a} (hidden for new conditions)';
$string['cataloguesearch_course'] = 'Find a permission';
$string['cataloguesearch_system'] = 'Find a permission';
$string['description'] = 'Check whether a person has a permission in the course or at site level.';
$string['error_selectcapability'] = 'Select a permission.';
$string['invalidcatalogue'] = 'Choose capabilities registered for the course or system context.';
$string['pluginname'] = 'Restriction by permission';
$string['privacy:metadata'] = 'The permission condition stores no personal data.';
$string['requires'] = 'You have the permission “{$a}”.';
$string['requires_not'] = 'You are missing the permission “{$a}”.';
$string['requires_unavailable'] = 'The configured permission is unavailable.';
$string['scope'] = 'Permission scope';
$string['scope_course'] = 'Course permissions';
$string['scope_system'] = 'System permissions';
$string['title'] = 'Permission';
