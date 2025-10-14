<?php

add_action('rest_api_init','create_rest_endpoint');

require_once(MY_PLUGIN_PATH."/includes/register-form.php");
require_once(MY_PLUGIN_PATH."/includes/login-form.php");
require_once(MY_PLUGIN_PATH.'/includes/dashboard.php');
require_once(MY_PLUGIN_PATH.'/includes/create-dead-person-form.php');

function create_rest_endpoint(){
    register_rest_route( "v1", "register",array(
        'methods' => 'POST',
        'callback' => 'register_user'
    ));

    register_rest_route( "v1", "login",array(
        'methods' => 'POST',
        'callback' => 'login_user'
    ));

    register_rest_route( "v1", "get-profiles",array(
        'methods' => 'GET',
        'callback' => 'get_profiles'
    ));

    register_rest_route( "v1", "create-dead-person",array(
        'methods' => 'POST',
        'callback' => 'create_dead_person'
    ));

}