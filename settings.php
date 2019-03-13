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
 * Admin settings and defaults.
 *
 * @package auth_email
 * @copyright  2017 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
    
require_once("$CFG->dirroot/availability/condition/week/classes/textarea.php");


if ($ADMIN->fulltree) {    

    // Introductory explanation.
    $settings->add( 
        new admin_setting_heading(
            'availability_week/description',
            '',
            new lang_string( 'setting_form_description', 'availability_week' )
        )
    );

    $settings->add( 
        new availability_condition_week_textarea(
            'availability_condition_week', 
            new lang_string( 'config_label', 'availability_week' ), 
            new lang_string( 'textarea_description', 'availability_week' ),
            null
        )
    );
}
