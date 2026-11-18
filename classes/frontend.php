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
 * Registers the course permission field with Moodle's availability editor.
 *
 * @package    availability_permission
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_permission;

use availability_permission\local\catalogue;
use cm_info;
use section_info;

/**
 * Supplies the permission choices to the native editor.
 *
 * @package    availability_permission
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class frontend extends \core_availability\frontend {
    protected function get_javascript_strings(): array {
        return ['error_selectcapability', 'requires_unavailable', 'scope', 'scope_course', 'scope_system'];
    }

    protected function allow_add($course, ?cm_info $cm = null, ?section_info $section = null): bool {
        return (int) $course->id > SITEID && (bool) condition::options();
    }

    protected function get_javascript_init_params($course, ?cm_info $cm = null, ?section_info $section = null): array {
        $choices = ['course' => [], 'system' => []];
        $retired = [];
        if ((int) $course->id > SITEID) {
            foreach (condition::options() as $name => $label) {
                $scope = catalogue::context_level($name) === CONTEXT_SYSTEM ? 'system' : 'course';
                $choices[$scope][] = ['name' => $name, 'label' => $label];
            }
            foreach (catalogue::options(false) as $name => $label) {
                $retired[$name] = [
                    'scope' => catalogue::context_level($name) === CONTEXT_SYSTEM ? 'system' : 'course',
                    'label' => get_string('catalogueretired', 'availability_permission', $label),
                ];
            }
        }
        return [$choices, $retired];
    }
}
