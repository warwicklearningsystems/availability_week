<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once($CFG->libdir.'/adminlib.php');     

use availability_week\utils;

defined('MOODLE_INTERNAL') || die();

class availability_condition_week_textarea extends admin_setting_configtextarea{
    
    const EXPECTED_JSON_PROPERTIES = [ 'date','label','show' ];
    
    public function __construct($name, $visiblename, $description, $defaultsetting, $paramtype = PARAM_RAW, $cols = '60', $rows = '8') {
        parent::__construct($name, $visiblename, $description, $defaultsetting, $paramtype, $cols, $rows);
    }

    /**
     * ensures that the input is valid JSON and if so checks that the format is as expected
     * 
     * @param string $data
     * @return mixed
     */
    public function validate( $data ) {

        if( parent::validate( $data ) ){
        
            try{
                $jsonMap = utils::JsonToArray( $data );

                foreach( $jsonMap as $key => $jsonConfig ){
                    if( !is_array( $jsonConfig ) )
                        return get_string('textarea_invalid_json_format', 'availability_week');

                    foreach(self::EXPECTED_JSON_PROPERTIES as $jsonProperty ){
                        if( !array_key_exists( $jsonProperty, $jsonConfig ) )
                            return get_string('textarea_invalid_json_format', 'availability_week');
                    }
                }

                return true;
            }catch( Exception $e ){
                return get_string('textarea_invalid_input', 'availability_week');
            }
            
        }
        return false;
    }
}