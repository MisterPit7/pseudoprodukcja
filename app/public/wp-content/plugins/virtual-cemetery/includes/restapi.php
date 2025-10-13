<?php

add_action('rest_api_init','create_rest_endpoint');

require_once(MY_PLUGIN_PATH."/includes/register-form.php");

function create_rest_endpoint(){
    register_rest_route( "v1", "register",array(
        'methods' => 'POST',
        'callback' => 'register_user'
    ));
}