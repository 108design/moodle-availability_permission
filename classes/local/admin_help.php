<?php
/**
 * This file is part of Moodle - https://moodle.org/
 *
 * Moodle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Moodle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Moodle. If not, see <https://www.gnu.org/licenses/>.
 *
 * File documentation for classes/local/admin_help.php.
 *
 * @package   availability_permission
 * @copyright 2026 Andreas Giesen
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// Copyright (C) 2026 Andreas Giesen. SPDX-License-Identifier: GPL-3.0-or-later
namespace availability_permission\local;

defined('MOODLE_INTERNAL') || die();

/** Contextual settings help, following the standalone Frontpage panel contract. */
final class admin_help {
    /** Only site administrators can open the help for this plugin setting. */
    private static function relevant_page(): bool {
        global $CFG, $PAGE;
        $settings = new \moodle_url('/' . $CFG->admin . '/settings.php');
        return $PAGE->url->get_path() === $settings->get_path()
            && $PAGE->url->get_param('section') === 'availabilitysettingpermission'
            && has_capability('moodle/site:config', \context_system::instance());
    }

    /** @var string[] Supported panel topics with corresponding language strings. */
    private const TOPICS = ['catalogue', 'saved'];

    private static bool $prepared = false;

    /** Register only the permitted settings page before its header is rendered. */
    public static function prepare(\core\hook\output\before_http_headers $hook): void {
        global $CFG, $PAGE;
        if (self::$prepared || !self::relevant_page()) {
            return;
        }
        $PAGE->add_body_class('availability-permission-admin-help-page');
        self::$prepared = true;
        $PAGE->requires->css(new \moodle_url('/availability/condition/permission/styles/admin_help.css', [
            'rev' => get_config('availability_permission', 'version'),
        ]));
        $module = (int)$CFG->version < 2025041400 ? 'admin_help_legacy'
            : ((int)$CFG->version >= 2026100500 ? 'admin_help_53' : 'admin_help');
        $PAGE->requires->js_call_amd('availability_permission/admin_help_form', 'init');
        $PAGE->requires->js_call_amd('availability_permission/' . $module, 'init', [[
            'topic' => optional_param('availabilitypermissionhelp', '', PARAM_ALPHA),
        ]]);
    }

    /** Native popover with concise guidance and a direct link to the extended topic. */
    public static function icon(string $key): string {
        global $CFG, $PAGE;
        if (!in_array($key, self::TOPICS, true)) {
            throw new \coding_exception('Unknown Administration help topic.');
        }
        if (!self::relevant_page()) {
            return '';
        }
        $url = new \moodle_url('/' . $CFG->admin . '/settings.php', ['section' => 'availabilitysettingpermission']);
        $url->param('availabilitypermissionhelp', $key);
        $help = new \help_icon($key, 'availability_permission', $url->out(false));
        $renderer = $PAGE->get_renderer('core');
        $data = (array)$help->export_for_template($renderer);
        $data['completedoclink'] = \html_writer::link($url, get_string('adminhelpmore', 'availability_permission'));
        $data['legacy'] = (int)$CFG->version < 2025041400;
        $data['infoicon'] = (int)$CFG->version < 2026100500 ? 'i/info' : 'i/circleinfo';
        return $renderer->render_from_template('availability_permission/settings_help_icon', $data);
    }

    /** Always available in the native Topnav user area on the registered page. */
    public static function navbar(\renderer_base $renderer): string {
        global $CFG;
        if (!self::$prepared) {
            return '';
        }
        $label = get_string('adminhelp', 'availability_permission');
        return \html_writer::tag('button', $renderer->pix_icon(
            (int)$CFG->version < 2026100500 ? 'i/info' : 'i/circleinfo', ''), [
                'type' => 'button', 'class' => 'nav-link icon-no-margin availability-permission-help-navbar',
                'data-availabilitypermission-help-open' => '', 'aria-label' => $label, 'title' => $label,
                'aria-controls' => 'availability-permission-context-help', 'aria-expanded' => 'false',
            ]);
    }

    /** Same header, topic chooser and article layout as the Frontpage reference. */
    public static function render(): string {
        global $PAGE;
        if (!self::$prepared) {
            return '';
        }
        $title = get_string('adminhelp', 'availability_permission');
        $close = \html_writer::tag('button', '<svg class="design108-panel-close-icon" viewBox="0 0 16 16"'
            . ' width="16" height="16" aria-hidden="true" focusable="false"'
            . ' fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M3 3l10 10M13 3L3 13"/></svg>', [
            'type' => 'button', 'class' => 'btn btn-secondary btn-sm availability-permission-help-close',
            'aria-label' => get_string('closebuttontitle', 'moodle'), 'data-availabilitypermission-help-close' => '1',
        ]);
        $options = '';
        $articles = '';
        foreach (self::TOPICS as $topic) {
            $label = get_string('adminhelp_' . $topic, 'availability_permission');
            $options .= \html_writer::tag('option', $label, ['value' => $topic]);
            $articles .= \html_writer::tag('article', \html_writer::tag('h3', $label, ['class' => 'h5', 'tabindex' => '-1'])
                . get_string('adminhelp_' . $topic . '_article', 'availability_permission'), [
                    'data-availabilitypermission-help-article' => $topic, 'hidden' => 'hidden',
                ]);
        }
        $choice = \html_writer::tag('label', get_string('adminhelptopics', 'availability_permission'), [
            'for' => 'availability-permission-help-topic', 'class' => 'form-label',
        ]) . \html_writer::tag('select', $options, [
            'id' => 'availability-permission-help-topic', 'class' => 'form-select custom-select',
        ]);
        // Shared help-panel identity: no link or action; close remains beside the topic selector.
        $brand = \html_writer::tag('span', \html_writer::empty_tag('img', [
            'src' => $PAGE->get_renderer('core')->image_url('helpbrand', 'availability_permission')->out(false),
            'alt' => '', 'width' => 25, 'height' => 24,
        ]), [
            'class' => 'availability-permission-help-brand', 'role' => 'img', 'aria-label' => '108design',
            'title' => '108design', 'tabindex' => '0', 'data-toggle' => 'tooltip', 'data-bs-toggle' => 'tooltip',
        ]);
        $header = \html_writer::tag('header', \html_writer::tag('h2', $title, [
            'id' => 'availability-permission-context-help-title', 'class' => 'sr-only visually-hidden', 'tabindex' => '-1',
        ]) . \html_writer::div($choice, 'availability-permission-help-choices') . $brand . $close,
            ['class' => 'availability-permission-help-header availability-permission-help-header-branded']);
        return \html_writer::tag('aside', $header
            . \html_writer::div($articles, 'availability-permission-help-content', ['tabindex' => '0']), [
                'id' => 'availability-permission-context-help', 'class' => 'availability-permission-context-help',
                'aria-labelledby' => 'availability-permission-context-help-title', 'hidden' => 'hidden',
                'data-availabilitypermission-help-url' => $PAGE->url->out(false),
            ]);
    }

    /** Append the registered panel without changing the page's controls. */
    public static function footer(\core\hook\output\before_standard_footer_html_generation $hook): void {
        $hook->add_html(self::render());
    }
}
