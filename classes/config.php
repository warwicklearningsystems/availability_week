<?php

namespace availability_week;

class config{
    
    const EXPECTED_JSON_PROPERTIES = [ 'label','academic_year','date','show' ];

    const NAME = 'availability_condition_week';
    
    /**
     *
     * @var string
     */
    private $config;
    
    /**
     *
     * @var \stdClass
     */
    private $course;
    
    /**
     * 
     * @global type $CFG
     * @param \stdClass $course
     * @throws \moodle_exception
     */
    public function __construct( \stdClass $course ){
        global $CFG;

        $this->config = !empty($CFG->{self::NAME}) ? $CFG->{self::NAME} : null;
        $this->course = $course;
    }
    
    /**
     * 
     * @param string $config
     * @return boolean
     */
    public static function validate( $config ){
        $jsonMap = utils::JsonToArray( $config );
        $errorMap = [];

        foreach( $jsonMap as $key => $jsonConfig ){
            if( !is_array( $jsonConfig ) ){
                return get_string('textarea_invalid_json_format', 'availability_week');
            }

            foreach( $jsonConfig as $jsonProperty => $jsonValue ){
                if( 'date' == $jsonProperty ){
                    if( !preg_match("/^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$/", trim( $jsonValue ) ) ){
                        $errorMap[] = [ 'error' => get_string('textarea_invalid_property', 'availability_week', [ 'object' => $key, 'property' => 'date', 'value' => $jsonValue ] ) ];
                    }
                }

                if( 'academic_year' == $jsonProperty ){
                    if( !( 'none' == trim( $jsonValue ) ) && !preg_match("/^\d{2}\/\d{2}$/", trim( $jsonValue ) ) ){
                        $errorMap[] = [ 'error' => get_string('textarea_invalid_property', 'availability_week', [ 'object' => $key, 'property' => 'academic_year', 'value' => $jsonValue ] ) ];
                    }
                }

                if( !in_array( $jsonProperty, self::EXPECTED_JSON_PROPERTIES ) ){
                    $errorMap[] = [ 'error' => get_string('textarea_unexpected_property', 'availability_week', [ 'object' => $key, 'property' => $jsonProperty ] ) ];
                }
            }
        }

        return $errorMap ? $errorMap : true;
    }
    
    private function isCourseWithinAnAcademicYear(){
        $pattern = "/\d{2}\/\d{2}\)$/";
        if( preg_match( $pattern, trim( $this->course->fullname ) ) || preg_match( $pattern, trim ( $this->course->shortname ) ) ){
            return true;
        }

        return false;
    }

    public function getOptions(){
        try{
            $configMap = utils::JsonToArray( $this->config );
        }catch(\moodle_exception $e){
            return utils::arrayToObject( [] );
        }

        $filteredConfigMap = array_filter( $configMap, function( $map ) {
            if( $this->isCourseWithinAnAcademicYear() ){
                return ( stristr( $this->course->fullname, $map[ 'academic_year' ] ) || stristr( $this->course->shortname, $map[ 'academic_year' ] ) );
            }else{
                return stristr( 'none', $map[ 'academic_year' ] );
            }
        });

        if( $this->isCourseWithinAnAcademicYear() && !$filteredConfigMap ){ //no options found in config for 'academic year' course, fallback to defaults
            $filteredConfigMap = array_filter( $configMap, function( $map ){
                return stristr( 'none', $map[ 'academic_year' ] );
            });
        }

        return utils::arrayToObject( $filteredConfigMap );
    }

    /**
     * 
     * @global \availability_week\type $CFG
     * @param string $label
     * @param \stdClass $course
     */
    public function getDateByLabelForAcademicYear( string $label ){
        $configMap = $this->getOptions();
        
        foreach( $configMap as $map ){
            if( strtolower( trim( $label ) ) == strtolower( trim( $map->label ) ) ){
                return strtotime( $map->date );
            }
        }

        return false;
    }
}