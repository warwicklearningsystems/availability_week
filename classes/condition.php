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
 * Date condition.
 *
 * @package availability_week
 * @copyright 2014 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_week;
use availability_week\config;

defined('MOODLE_INTERNAL') || die();

/**
 * Date condition.
 *
 * @package availability_week
 * @copyright 2014 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class condition extends \core_availability\condition {
    /** @var string Availabile only from specified date. */
    const DIRECTION_FROM = '>=';

    /** @var string Availabile only until specified date. */
    const DIRECTION_UNTIL = '<';

    /** @var string One of the DIRECTION_xx constants. */
    private $direction;

    /** @var string Label used to retrieve the appropriate date from the config. */
    private $label;

    /** @var int Forced current time (for unit tests) or 0 for normal. */
    private static $forcecurrenttime = 0;

    /**
     * Constructor.
     *
     * @param \stdClass $structure Data structure from JSON decode
     * @throws \coding_exception If invalid data structure.
     */
    public function __construct($structure) {
        // Get direction.
        if (isset($structure->d) && in_array($structure->d,
                array(self::DIRECTION_FROM, self::DIRECTION_UNTIL))) {
            $this->direction = $structure->d;
        } else {
            throw new \coding_exception('Missing or invalid ->d for week condition');
        }

        // Get label.
        if ($structure->label || is_null($structure->label)) {
            $this->label = $structure->label;
        } else {
            throw new \coding_exception('Missing or invalid ->label for week condition');
        }
    }

    public function save() {
        return (object)array('type' => 'date',
                'd' => $this->direction, 'label' => $this->label);
    }

    /**
     * Returns a JSON object which corresponds to a condition of this type.
     *
     * Intended for unit testing, as normally the JSON values are constructed
     * by JavaScript code.
     *
     * @param string $direction DIRECTION_xx constant
     * @param int $time Time in epoch seconds
     * @return stdClass Object representing condition
     */
    public static function get_json($direction, $label) {
        return (object)array('type' => 'date', 'd' => $direction, 'label' => $label);
    }

    public function is_available($not, \core_availability\info $info, $grabthelot, $userid) {
        return $this->is_available_for_all($not);
    }

    public function is_available_for_all($not = false) {
        global $COURSE;

        $config = new config( get_course( $COURSE->id ) );
        $time = $config->getDateByLabelForAcademicYear( $this->label );

        if( !$time ) //it's likey that the label no longer exists in the config for this academic year (which should be avoided), so prevent access by default
            return true;

        // Check condition.
        $now = self::get_time();

        switch ($this->direction) {
            case self::DIRECTION_FROM:
                $allow = $now >= $time;
                break;
            case self::DIRECTION_UNTIL:
                $allow = $now < $time;
                break;
            default:
                throw new \coding_exception('Unexpected direction');
        }
        if ($not) {
            $allow = !$allow;
        }

        return $allow;
    }

    /**
     * Obtains the actual direction of checking based on the $not value.
     *
     * @param bool $not True if condition is negated
     * @return string Direction constant
     * @throws \coding_exception
     */
    protected function get_logical_direction($not) {
        switch ($this->direction) {
            case self::DIRECTION_FROM:
                return $not ? self::DIRECTION_UNTIL : self::DIRECTION_FROM;
            case self::DIRECTION_UNTIL:
                return $not ? self::DIRECTION_FROM : self::DIRECTION_UNTIL;
            default:
                throw new \coding_exception('Unexpected direction');
        }
    }

    public function get_description($full, $not, \core_availability\info $info) {
        //exit(var_dump($info));
        return $this->get_either_description($not, false);
    }

    public function get_standalone_description(
            $full, $not, \core_availability\info $info) {
        return $this->get_either_description($not, true);
    }

    /**
     * Shows the description using the different lang strings for the standalone
     * version or the full one.
     *
     * @param bool $not True if NOT is in force
     * @param bool $standalone True to use standalone lang strings
     */
    protected function get_either_description($not, $standalone) {
        $direction = $this->get_logical_direction($not);
        $satag = $standalone ? 'short_' : 'full_';
        switch ($direction) {
            case self::DIRECTION_FROM:                
                return get_string($satag . 'from', 'availability_week',
                        $this->label);
            case self::DIRECTION_UNTIL:
                return get_string($satag . 'until', 'availability_week',
                        $this->label);
        }
    }

    protected function get_debug_string() {
        return $this->direction . ' ' . gmdate('Y-m-d H:i:s', $this->time);
    }

    /**
     * Gets time. This function is implemented here rather than calling time()
     * so that it can be overridden in unit tests. (Would really be nice if
     * Moodle had a generic way of doing that, but it doesn't.)
     *
     * @return int Current time (seconds since epoch)
     */
    protected static function get_time() {
        if (self::$forcecurrenttime) {
            return self::$forcecurrenttime;
        } else {
            return time();
        }
    }

    /**
     * Forces the current time for unit tests.
     *
     * @param int $forcetime Time to return from the get_time function
     */
    public static function set_current_time_for_test($forcetime = 0) {
        self::$forcecurrenttime = $forcetime;
    }
}
