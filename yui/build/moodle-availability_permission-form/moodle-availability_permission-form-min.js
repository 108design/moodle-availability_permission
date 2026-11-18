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
YUI.add("moodle-availability_permission-form",function(e){M.availability_permission=M.availability_permission||{},M.availability_permission.form=e.Object(M.core_availability.plugin),M.availability_permission.form.initInner=function(e,i){this.choices=e,this.retired=i},M.availability_permission.form.getNode=function(i){var t="availability_permission",a=e.Node.create('<div class="d-flex flex-wrap align-items-center"></div>'),l=M.util.get_string("title",t),s=e.Node.create('<select class="custom-select form-select availability-permission-scope"></select>');s.setAttribute("aria-label",M.util.get_string("scope",t));var o=e.Node.create('<select name="capability" class="custom-select form-select"></select>'),n=e.guid("availability-permission-");o.setAttribute("id",n),a.append(e.Node.create('<label class="col-form-label mb-0"></label>').setAttribute("for",n).set("text",l));var r=this.choices,c=i.capability||"",p=e.Object.owns(this.retired,c)?this.retired[c]:null,u=Object.keys(r).filter(function(e){return r[e].length||p&&p.scope===e}),m=p?p.scope:u.find(function(e){return r[e].some(function(e){return e.name===c})}),b=m||u[0]||"course",f=function(i,t,a){i.append(e.Node.create("<option></option>").set("value",t).set("text",a))},d=function(){o.set("title",o.get("options").item(o.get("selectedIndex")).get("text"))},v=function(e,i){o.empty(),f(o,"",M.util.get_string("choosedots","moodle")),r[e].forEach(function(e){f(o,e.name,e.label)}),c&&e===b&&!m?f(o,c,M.util.get_string("requires_unavailable",t)):p&&e===p.scope&&f(o,c,p.label),o.set("value",i),d()};return u.length>1?(u.forEach(function(e){f(s,e,M.util.get_string("scope_"+e,t))}),s.set("value",b),s.on("change",function(){v(s.get("value"),""),M.core_availability.form.update()}),a.append(s)):1===u.length&&a.append(e.Node.create('<span class="col-form-label availability-permission-scope"></span>').set("text",M.util.get_string("scope_"+b,t))),v(b,c),o.on("change",function(){d(),M.core_availability.form.update()}),a.append(o),a},M.availability_permission.form.fillValue=function(e,i){e.capability=i.one("select[name=capability]").get("value")},M.availability_permission.form.fillErrors=function(e,i){i.one("select[name=capability]").get("value")||e.push("availability_permission:error_selectcapability")}},"@VERSION@",{requires:["node","moodle-core_availability-form"]});
