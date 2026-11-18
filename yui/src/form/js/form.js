/*!
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
 * @package    availability_permission
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Permission fields for the Moodle availability editor.
 *
 * @module moodle-availability_permission-form
 */
M.availability_permission = M.availability_permission || {};
M.availability_permission.form = Y.Object(M.core_availability.plugin);

M.availability_permission.form.initInner = function(choices, retired) {
    this.choices = choices;
    this.retired = retired;
};

M.availability_permission.form.getNode = function(json) {
    var component = 'availability_permission';
    var fields = Y.Node.create('<div class="d-flex flex-wrap align-items-center"></div>');
    var title = M.util.get_string('title', component);
    var scopeSelect = Y.Node.create('<select class="custom-select form-select availability-permission-scope"></select>');
    scopeSelect.setAttribute('aria-label', M.util.get_string('scope', component));
    var select = Y.Node.create('<select name="capability" class="custom-select form-select"></select>');
    var id = Y.guid('availability-permission-');
    select.setAttribute('id', id);
    fields.append(Y.Node.create('<label class="col-form-label mb-0"></label>')
        .setAttribute('for', id).set('text', title));
    var choices = this.choices;
    var saved = json.capability || '';
    var retired = Y.Object.owns(this.retired, saved) ? this.retired[saved] : null;
    var scopes = Object.keys(choices).filter(function(scope) {
        return choices[scope].length || (retired && retired.scope === scope);
    });
    var savedScope = retired ? retired.scope : scopes.find(function(scope) {
        return choices[scope].some(function(choice) {
            return choice.name === saved;
        });
    });
    var initialScope = savedScope || scopes[0] || 'course';
    var addOption = function(field, value, text) {
        field.append(Y.Node.create('<option></option>').set('value', value).set('text', text));
    };
    var updateTitle = function() {
        select.set('title', select.get('options').item(select.get('selectedIndex')).get('text'));
    };

    var showChoices = function(scope, current) {
        select.empty();
        addOption(select, '', M.util.get_string('choosedots', 'moodle'));
        choices[scope].forEach(function(choice) {
            addOption(select, choice.name, choice.label);
        });
        if (saved && scope === initialScope && !savedScope) {
            addOption(select, saved, M.util.get_string('requires_unavailable', component));
        } else if (retired && scope === retired.scope) {
            addOption(select, saved, retired.label);
        }
        select.set('value', current);
        updateTitle();
    };

    if (scopes.length > 1) {
        scopes.forEach(function(scope) {
            addOption(scopeSelect, scope, M.util.get_string('scope_' + scope, component));
        });
        scopeSelect.set('value', initialScope);
        scopeSelect.on('change', function() {
            showChoices(scopeSelect.get('value'), '');
            M.core_availability.form.update();
        });
        fields.append(scopeSelect);
    }
    showChoices(initialScope, saved);
    select.on('change', function() {
        updateTitle();
        M.core_availability.form.update();
    });

    fields.append(select);
    return fields;
};

M.availability_permission.form.fillValue = function(value, node) {
    value.capability = node.one('select[name=capability]').get('value');
};

M.availability_permission.form.fillErrors = function(errors, node) {
    if (!node.one('select[name=capability]').get('value')) {
        errors.push('availability_permission:error_selectcapability');
    }
};
