<?php
/*
Plugin Name: Advanced Custom Fields - Name Field
Plugin URI: http://www.advancedcustomfields.com/
Description: A name field for ACF
Version: 0.1
Author: Alexandros Sigalas
License: GPL
Copyright: Alexandros Sigalas
*/


function acf_name_register(){
  if( function_exists( 'register_field' ))
  {
     register_field('Name_Field', dirname(__FILE__) . '/name_field.php'); 
    
  }
}

add_action('plugins_loaded', 'acf_name_register');