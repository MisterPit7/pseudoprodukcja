<?php
/*
 * Plugin Name:       Virtual Cemetery
 * Description:       Simple plugin to do something 
 * Version:           1.0
 * Author:            MisterPit, MARVI2, Madness  
 */


//I am sorry 

if(!defined("ABSPATH")){
    die("you cannot be here");
}

if(!class_exists('VirtualCemetery')){
    class VirtualCemetery{
        public function __construct()
        {
            define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__) );
            define('CaptchaKey','6Lc2v_ArAAAAAHfeltN6JiN8AT06Y_hw3MfRPAhC');
            define('CaptchaSecretKey','6Lc2v_ArAAAAAOLAlYz2qNSM0TkJK1seRCZ5kVvw');
        }

        public function initialize(){
            include_once(MY_PLUGIN_PATH.'/includes/database-setup.php');
            include_once(MY_PLUGIN_PATH. '/includes/restapi.php');
        }

    }

    $virtualCemetery = new VirtualCemetery;
    $virtualCemetery->initialize();

}
