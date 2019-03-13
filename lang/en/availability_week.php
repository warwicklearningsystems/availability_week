<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language strings.
 *
 * @package availability_date
 * @copyright 2014 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['setting_form_description'] = '<p>Allows resource availability to be restricted by the academic week number, offset from the start date of the academic year.</p>';
$string['textarea_invalid_input']= 'JSON input required';
$string['textarea_invalid_json_format']= 'Invalid JSON format provided';
$string['textarea_description'] = 'JSON input required, i.e. [
{
        "date" : "2018-10-01",
        "label" : "Week 1",
        "show" : 1
},
{
        "date" : "2018-10-08",
        "label" : "Week 2",
        "show" : 1
}]';
$string['config_label'] = 'Config';
$string['ajaxerror'] = 'Error contacting server to convert times';
$string['direction_before'] = 'Week';
$string['direction_from'] = 'from';
$string['direction_label'] = 'Direction';
$string['direction_until'] = 'until';
$string['description'] = 'Prevent access until (or from) a specified week within the academic year.';
$string['full_from'] = 'It is after <strong>{$a}</strong>';
$string['full_from_date'] = 'It is on or after <strong>{$a}</strong>';
$string['full_until'] = 'It is before <strong>{$a}</strong>';
$string['full_until_date'] = 'It is before end of <strong>{$a}</strong>';
$string['pluginname'] = 'Restriction by week';
$string['short_from'] = 'Available from <strong>{$a}</strong>';
$string['short_from_date'] = 'Available from <strong>{$a}</strong>';
$string['short_until'] = 'Available until <strong>{$a}</strong>';
$string['short_until_date'] = 'Available until end of <strong>{$a}</strong>';
$string['title'] = 'Week';
$string['privacy:metadata'] = 'The Restriction by week plugin does not store any personal data.';
