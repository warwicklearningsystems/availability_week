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
 * Front-end class.
 *
 * @package availability_week
 * @copyright 2014 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_week;

use availability_week\config;

defined('MOODLE_INTERNAL') || die();

/**
 * Front-end class.
 *
 * @package availability_week
 * @copyright 2014 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class frontend extends \core_availability\frontend {
    /**
     * The date selector popup is not currently supported because the date
     * selector is in a messy state (about to be replaced with a new YUI3
     * implementation) and MDL-44814 was rejected. I have left the code in
     * place, but disabled. When the date selector situation is finalised,
     * then this constant should be removed (either applying MDL-44814 if old
     * selector is still in use, or modifying the JavaScript code to support the
     * new date selector if it has landed).
     *
     * @var bool
     */
    const DATE_SELECTOR_SUPPORTED = false;

    protected function get_javascript_strings() {
        return array('ajaxerror', 'direction_before', 'direction_from', 'direction_until',
                'direction_label');
    }
    
    /**
     * 
     * @param \cm_info $cm
     * @param int $date
     * @return bool
     */
    private function findStoredAvailabilityWeekConditionByLabel(\cm_info $cm = null, $label){
        
        if(!$cm->availability)
            return false;

        $storedAvailabilityConditionMap = utils::arrayToObject(utils::JsonToArray($cm->availability))->c;

        foreach( $storedAvailabilityConditionMap as $key => $storedAvailabilityConditionObject){
            if('week' == $storedAvailabilityConditionObject->type && $storedAvailabilityConditionObject->label == $label){
                return true;
            }   
        }

        return false;
    }

    protected function get_javascript_init_params($course, \cm_info $cm = null,
            \section_info $section = null) {
        global $CFG, $OUTPUT;
        require_once($CFG->libdir . '/formslib.php');

        $availabilityConditionWeekConfig = new config( $course );
        $availabilityWeekConditionConfigObjectMap = $availabilityConditionWeekConfig->getOptions();

        $visibleAvailabilityWeekConditionConfigObjectMap = new \stdClass();

        $html = '<span class="availability-group">';
            $html .= \html_writer::start_tag('label');
            $html .= \html_writer::start_tag('select', array('name' => 'week-select', 'class' => 'custom-select'));
            
                foreach( $availabilityWeekConditionConfigObjectMap as $key => $availabilityWeekConditionConfigObject){
                    $attributes = ['value' => $availabilityWeekConditionConfigObject->label];
                    
                    if( $availabilityWeekConditionConfigObject->show || $this->findStoredAvailabilityWeekConditionByLabel($cm, $availabilityWeekConditionConfigObject->label ) ){
                        $visibleAvailabilityWeekConditionConfigObjectMap->$key = $availabilityWeekConditionConfigObject;
                        $html .= \html_writer::tag('option', $availabilityWeekConditionConfigObject->label, $attributes);
                    }
                }
            
            $html .= \html_writer::end_tag('select');
            $html .= \html_writer::end_tag('label');
            $html .= ' ';
       
        $html = rtrim($html) . '</span>';

        return array( 
            $html,
            current( $visibleAvailabilityWeekConditionConfigObjectMap )->label ? : null, //set default label to that of the first config option, as that'll appear in drop-down list first
            $course->id
        ); 
    }
}
