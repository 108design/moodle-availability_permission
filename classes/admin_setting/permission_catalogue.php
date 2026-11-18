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
 * Native site administration selector for course and system permissions.
 *
 * @package    availability_permission
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_permission\admin_setting;

use availability_permission\local\admin_help;
use availability_permission\local\catalogue;

/** Select active items in Moodle administration while preserving retired items. */
final class permission_catalogue extends \admin_setting_configmultiselect {
    /** Creates the site setting without loading the capability catalogue during tree construction. */
    public function __construct() {
        parent::__construct(
            'availability_permission/' . catalogue::SETTING,
            get_string('catalogue', 'availability_permission'),
            get_string('cataloguedescription', 'availability_permission'),
            catalogue::defaults(),
            null,
        );
    }

    public function load_choices(): bool {
        if ($this->choices === null) {
            $this->choices = [];
            foreach (array_keys(get_all_capabilities()) as $name) {
                if (catalogue::context_level($name) !== null) {
                    $this->choices[$name] = catalogue::choice_label($name);
                }
            }
            natcasesort($this->choices);
        }
        return true;
    }

    public function get_setting(): array {
        return array_keys(array_filter(catalogue::entries(), static fn(bool $active): bool => $active));
    }

    public function write_setting($data): string {
        global $DB;
        if (!is_array($data)) {
            return get_string('errorsetting', 'admin');
        }
        unset($data['xxxxx']);
        $active = [];
        foreach ($data as $name) {
            if (!is_string($name) || catalogue::context_level($name) === null) {
                return get_string('invalidcatalogue', 'availability_permission');
            }
            $active[$name] = true;
        }

        $lock = \core\lock\lock_config::get_lock_factory('availability_permission')->get_lock(catalogue::SETTING, 10);
        if (!$lock) {
            return get_string('errorsetting', 'admin');
        }

        try {
            // A fresh database read inside the lock preserves approvals added by another administrator.
            $previous = catalogue::decode($DB->get_field('config_plugins', 'value', [
                'plugin' => 'availability_permission', 'name' => catalogue::SETTING,
            ]));
            foreach ($previous as $name => $enabled) {
                // Keep the last selection of capabilities that are temporarily unavailable.
                if (catalogue::context_level($name) !== null) {
                    $previous[$name] = isset($active[$name]);
                }
            }
            $next = array_replace($previous, $active);
            return $this->config_write($this->name, json_encode($next, JSON_THROW_ON_ERROR))
                ? '' : get_string('errorsetting', 'admin');
        } finally {
            $lock->release();
        }
    }

    public function output_html($data, $query = ''): string {
        global $OUTPUT;

        $this->load_choices();
        $selected = array_fill_keys(array_filter((array) $data, 'is_string'), true);
        $groups = [CONTEXT_COURSE => [], CONTEXT_SYSTEM => []];
        foreach ($this->choices as $name => $label) {
            $groups[catalogue::context_level($name)][] = [
                'value' => $name, 'name' => $label, 'selected' => isset($selected[$name]),
            ];
        }
        $fields = '';
        foreach ([CONTEXT_COURSE => 'course', CONTEXT_SYSTEM => 'system'] as $level => $scope) {
            $id = $this->get_id() . '_' . $scope;
            $options = $groups[$level];
            $label = \html_writer::label(get_string('scope_' . $scope, 'availability_permission'), $id, false, [
                'class' => 'form-label',
            ]);
            $select = $OUTPUT->render_from_template('core_admin/setting_configmultiselect', [
                'name' => $this->get_full_name(),
                'id' => $id,
                'size' => max(1, min(10, count($options))),
                'options' => $options,
                'readonly' => $this->is_readonly(),
            ]);
            $fields .= \html_writer::div($label . $select, 'availability-permission-catalogue-group mb-3', [
                'data-permission-search' => get_string('cataloguesearch_' . $scope, 'availability_permission'),
            ]);
        }
        $title = $this->visiblename . admin_help::icon('catalogue');
        return format_admin_setting($this, $title, $fields, $this->description, false, '', null, $query);
    }
}
