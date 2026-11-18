<!--
This file is part of Moodle - https://moodle.org/

Moodle is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Moodle is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Moodle. If not, see <https://www.gnu.org/licenses/>.

@package    availability_permission
@copyright  2026 Andreas Giesen <andreas@108design.com>
@license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
-->

# Permission

Add **Permission** under **Restrict access** in an activity, resource or course section.
Choose whether learners must have the selected permission. Use Moodle's restriction sets to combine it with other conditions.
If both course and system permissions are available, choose the relevant group first.

The default selection includes permissions for course access, participants, groups, grades, competencies and reports.
Each option shows the Moodle label and capability ID where a label is available.
Moodle checks course permissions in the course, including role overrides. It checks system permissions at site level.

Site administrators can change the selection under **Site administration → Plugins → Availability restrictions → Restriction by permission**.
Each group has its own search and list of selected permissions. Search by Moodle label or capability ID to add permissions from Moodle and installed plugins.
Removing a permission from the selection hides it for new conditions. Moodle still checks existing conditions that use it.
For a course restored on another site, approve any additional capabilities in that site's selection.

Moodle’s permission to bypass availability restrictions still applies. Before disabling or uninstalling the plugin,
review the affected activities and sections. Moodle can skip an availability condition when its plugin is unavailable.

## Requirements

Moodle 4.5 to 5.3 with restricted access enabled. Install under `availability/condition/permission` in the Moodle web root.

## Licence

GPL v3 or later. See [COPYING.txt](COPYING.txt). The YUI build metadata and AMD source maps
carry the same copyright and licence as their sources.

The browser form uses the Moodle YUI build for `moodle-availability_permission-form`.
Keep the full GPL header at the top of each generated JavaScript variant when rebuilding the form.
