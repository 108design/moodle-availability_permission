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
 * File documentation for amd/src/admin_help_form.js.
 *
 * @package   availability_permission
 * @copyright 2026 Andreas Giesen
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// Copyright (C) 2026 Andreas Giesen. SPDX-License-Identifier: GPL-3.0-or-later

import Autocomplete from 'core/form-autocomplete';

/** Initialise the permission search and position the settings help control. */
export const init = () => {
    document.querySelectorAll('.availability-permission-catalogue-group').forEach(group => {
        const select = group.querySelector('select:not(:disabled)');
        if (!select) {
            return;
        }
        void Autocomplete.enhanceField('#' + select.id, false, false, group.dataset.permissionSearch).then(() => {
            const search = group.querySelector('.form-autocomplete-input');
            const selection = group.querySelector('.form-autocomplete-selection');
            if (search && selection) {
                selection.before(search);
            }
        });
    });
    const navigation = document.querySelector('#usernavigation');
    const alignNavbarHelp = () => {
        if (!navigation) return;
        const help = navigation.querySelector('.availability-permission-help-navbar');
        const children = [...navigation.children];
        const anchor = children.find(node => node.classList.contains('divider')) ||
            children.find(node => node.matches('[data-region="usermenu"], .usermenu-container'));
        if (help && anchor && (help.parentElement !== navigation || help.nextElementSibling !== anchor)) {
            navigation.insertBefore(help, anchor);
        }
    };
    // Navbar callbacks can finish in any order; keep help immediately before the user-area divider.
    alignNavbarHelp();
    if (navigation) new MutationObserver(alignNavbarHelp).observe(navigation, {childList: true});
};
