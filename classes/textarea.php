<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once($CFG->libdir.'/adminlib.php');     

use availability_week\config;

defined('MOODLE_INTERNAL') || die();

class availability_condition_week_textarea extends admin_setting_configtextarea{
    private $rows;
    private $cols;
    
    public function __construct($name, $visiblename, $description, $defaultsetting, $paramtype = PARAM_RAW, $cols = '60', $rows = '8') {
        $this->rows = $rows;
        $this->cols = $cols;
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
                return config::validate( $data );
            }catch( Exception $e ){
                return get_string('textarea_invalid_input', 'availability_week');
            }
            
        }
        return false;
    }

    /**
     * Returns an XHTML string for the editor
     *
     * @param string $data
     * @param string $query
     * @return string XHTML string for the editor
     */
    public function output_html($data, $query='') {
        global $OUTPUT;

        $default = $this->get_defaultsetting();
        $defaultinfo = $default;
        if (!is_null($default) and $default !== '') {
            $defaultinfo = "\n".$default;
        }

        $context = (object) [
            'cols' => $this->cols,
            'rows' => $this->rows,
            'id' => $this->get_id(),
            'name' => $this->get_full_name(),
            'value' => $data,
            'forceltr' => $this->get_force_ltr(),
        ];
        $element = $OUTPUT->render_from_template('core_admin/setting_configtextarea', $context);

        return $this->formatAdminSetting($this, $this->visiblename, $element, $this->description, true, '', $defaultinfo, $query);
    }

    /**
    * Format admin settings
    *
    * @param object $setting
    * @param string $title label element
    * @param string $form form fragment, html code - not highlighted automatically
    * @param string $description
    * @param mixed $label link label to id, true by default or string being the label to connect it to
    * @param string $warning warning text
    * @param sting $defaultinfo defaults info, null means nothing, '' is converted to "Empty" string, defaults to null
    * @param string $query search query to be highlighted
    * @return string XHTML
    */
   public function formatAdminSetting($setting, $title='', $form='', $description='', $label=true, $warning='', $defaultinfo=NULL, $query='') {
       global $CFG, $OUTPUT;

       $context = (object) [
           'name' => empty($setting->plugin) ? $setting->name : "$setting->plugin | $setting->name",
           'fullname' => $setting->get_full_name(),
       ];

       // Sometimes the id is not id_s_name, but id_s_name_m or something, and this does not validate.
       if ($label === true) {
           $context->labelfor = $setting->get_id();
       } else if ($label === false) {
           $context->labelfor = '';
       } else {
           $context->labelfor = $label;
       }

       $form .= $setting->output_setting_flags();

       $context->warning = $warning;
       $context->override = '';
       if (empty($setting->plugin)) {
           if (array_key_exists($setting->name, $CFG->config_php_settings)) {
               $context->override = get_string('configoverride', 'admin');
           }
       } else {
           if (array_key_exists($setting->plugin, $CFG->forced_plugin_settings) and array_key_exists($setting->name, $CFG->forced_plugin_settings[$setting->plugin])) {
               $context->override = get_string('configoverride', 'admin');
           }
       }

       $defaults = array();
       if (!is_null($defaultinfo)) {
           if ($defaultinfo === '') {
               $defaultinfo = get_string('emptysettingvalue', 'admin');
           }
           $defaults[] = $defaultinfo;
       }

       $context->default = null;
       $setting->get_setting_flag_defaults($defaults);
       if (!empty($defaults)) {
           $defaultinfo = implode(', ', $defaults);
           $defaultinfo = highlight($query, nl2br(s($defaultinfo)));
           $context->default = get_string('defaultsettinginfo', 'admin', $defaultinfo);
       }


       $context->error = '';
       $adminroot = admin_get_root();
       if (array_key_exists($context->fullname, $adminroot->errors)) {
           $context->errors = $adminroot->errors[$context->fullname]->error;
       }

       $context->id = 'admin-' . $setting->name;
       $context->title = highlightfast($query, $title);
       $context->name = highlightfast($query, $context->name);
       $context->description = highlight($query, markdown_to_html($description));
       $context->element = $form;
       $context->forceltr = $setting->get_force_ltr();

       return $OUTPUT->render_from_template('availability_week/setting', $context);
   }
}