<?php
/*
Plugin Name: ACF Extras
Plugin URI: http://www.advancedcustomfields.com/
Description: Extra fields for ACF
Version: 0.1
Author: Alexandros Sigalas
License: GPL
Copyright: Alexandros Sigalas
*/


function acf_extras_register()
{
  if( function_exists( 'register_field' ))
  {
     register_field('Name_Field', dirname(__FILE__) . '/fields/name_field.php'); 
     register_field('YouTube_Field', dirname(__FILE__) . '/fields/youtube_field.php'); 
  }
}

add_action('plugins_loaded', 'acf_extras_register');