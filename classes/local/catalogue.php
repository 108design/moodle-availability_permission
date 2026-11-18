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
 * Stores the enabled and previously approved permissions.
 *
 * @package    availability_permission
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_permission\local;

/** Site-wide catalogue for the editor and for evaluating saved conditions. */
final class catalogue {
    /** @var string Component setting that stores active and retired permissions atomically. */
    public const SETTING = 'permissioncatalogue';

    /**
     * Initial selections retained from the first development version.
     *
     * @return string[]
     */
    public static function defaults(): array {
        return [
            'moodle/course:view',
            'moodle/course:viewhiddencourses',
            'moodle/course:viewhiddensections',
            'moodle/course:viewoverview',
            'moodle/course:viewparticipants',
            'moodle/course:viewhiddengroups',
            'moodle/course:isincompletionreports',
            'moodle/course:reviewotherusers',
            'moodle/grade:view',
            'moodle/grade:viewall',
            'moodle/grade:viewhidden',
            'moodle/badges:viewbadges',
            'moodle/competency:coursecompetencyview',
            'moodle/contentbank:access',
            'moodle/notes:view',
            'moodle/site:viewreports',
            'report/completion:view',
            'report/outline:view',
            'report/progress:view',
        ];
    }

    /** Returns the selected and previously approved capability names and their active states. */
    public static function entries(): array {
        return self::decode(get_config('availability_permission', self::SETTING));
    }

    /**
     * Reads the stored map, with the initial selections on a new installation.
     *
     * @param string|false $stored Stored plugin configuration
     * @return array<string, bool> Active and retired permissions
     */
    public static function decode(string|false $stored): array {
        $entries = $stored === false ? null : json_decode($stored, true);
        if (!is_array($entries)) {
            return array_fill_keys(self::defaults(), true);
        }
        return array_filter($entries, 'is_bool');
    }

    /** Returns the supported context level of a registered capability. */
    public static function context_level(string $name): ?int {
        $capability = get_all_capabilities()[$name] ?? null;
        $level = (int) ($capability['contextlevel'] ?? 0);
        if (!in_array($level, [CONTEXT_COURSE, CONTEXT_SYSTEM], true)
                || !preg_match('~^[a-z][a-z0-9_]*(?:/[a-z][a-z0-9_]*)?:[a-z][a-z0-9_]*$~D', $name)) {
            return null;
        }
        return $level;
    }

    /** Both visible and retired items may be evaluated while their capability is available. */
    public static function approved_context_level(string $name): ?int {
        return array_key_exists($name, self::entries()) ? self::context_level($name) : null;
    }

    /**
     * Returns visible or retired choices with their Moodle labels and identifiers.
     *
     * @param bool $active Select visible items when true and retired items when false
     * @return array<string, string>
     */
    public static function options(bool $active = true): array {
        $choices = [];
        foreach (self::entries() as $name => $enabled) {
            if ($enabled === $active && self::context_level($name) !== null) {
                $choices[$name] = self::choice_label($name);
            }
        }
        return $choices;
    }

    /** Uses the capability identifier when an installed plugin supplies no language string. */
    public static function label(string $name): string {
        $parts = preg_split('~[/:]~', $name);
        if (count($parts) !== 3) {
            return $name;
        }
        [$type, $plugin, $action] = $parts;
        $component = match ($type) {
            'moodle' => 'core_role',
            'quizreport' => 'quiz_' . $plugin,
            default => $type . '_' . $plugin,
        };
        $key = $plugin . ':' . $action;
        return get_string_manager()->string_exists($key, $component) ? get_string($key, $component) : $name;
    }

    /** Adds the capability identifier to a translated label for use in a selector. */
    public static function choice_label(string $name): string {
        $label = self::label($name);
        return $label === $name ? $name : "$label ($name)";
    }
}
