<?php
/*
 * Plugin Name:       Virtual Cemetery
 * Description:       Simple plugin to do something 
 * Version:           1.0
 * Author:            MisterPit, MARVI2,Madness  
 */

if(!defined("ABSPATH")){
    die("NIGGER");
}

if(!class_exists('VirtualCemetery')){
    class VirtualCemetery{
        public function __construct()
        {
            define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__) );
        }

        public function initialize(){
            include_once(MY_PLUGIN_PATH.'/includes/database-setup.php');
            include_once(MY_PLUGIN_PATH. '/includes/restapi.php');
        }

    }

    $virtualCemetery = new VirtualCemetery;
    $virtualCemetery->initialize();

}
