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
 * Availability condition based on an effective Moodle permission.
 *
 * @package    availability_permission
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_permission;

use availability_permission\local\catalogue;
use context_course;
use context_system;
use core_availability\info;
use stdClass;

/**
 * Checks an approved capability in the owning course or system context.
 *
 * @package    availability_permission
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class condition extends \core_availability\condition {
    /** @var string Saved capability name. */
    private readonly string $capability;

    /**
     * Loads a saved condition, including conditions for capabilities that are no longer supported.
     *
     * @param stdClass $structure Saved condition data
     */
    public function __construct($structure) {
        $name = $structure->capability ?? null;
        $this->capability = is_string($name) && preg_match('~^[a-z][a-z0-9_]*(?:/[a-z][a-z0-9_]*)?:[a-z][a-z0-9_]*$~D', $name)
            ? $name : '';
    }

    /**
     * Returns available choices with their Moodle labels and capability identifiers.
     *
     * @return array<string, string> Capability name to label
     */
    public static function options(): array {
        return catalogue::options();
    }

    public function is_available($not, info $info, $grabthelot, $userid): bool {
        $courseid = (int) $info->get_course()->id;
        $level = catalogue::approved_context_level($this->capability);
        if ($courseid <= SITEID || $userid <= 0 || $level === null) {
            return false;
        }
        $context = $level === CONTEXT_SYSTEM ? context_system::instance() : context_course::instance($courseid);
        $allowed = has_capability($this->capability, $context, $userid);
        return $not ? !$allowed : $allowed;
    }

    public function get_description($full, $not, info $info): string {
        if (catalogue::approved_context_level($this->capability) === null) {
            return get_string('requires_unavailable', 'availability_permission');
        }
        $label = s(catalogue::label($this->capability));
        return get_string($not ? 'requires_not' : 'requires', 'availability_permission', $label);
    }

    public function save(): stdClass {
        return (object) ['type' => 'permission', 'capability' => $this->capability];
    }

    protected function get_debug_string(): string {
        return $this->capability;
    }
}
