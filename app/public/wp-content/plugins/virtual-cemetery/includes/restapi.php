<?php

add_action('rest_api_init','create_rest_endpoint');

require_once(MY_PLUGIN_PATH."/includes/get-single-person.php");
require_once(MY_PLUGIN_PATH."/includes/register-form.php");
require_once(MY_PLUGIN_PATH."/includes/login-form.php");
require_once(MY_PLUGIN_PATH."/includes/logout.php");
require_once(MY_PLUGIN_PATH.'/includes/dashboard.php');
require_once(MY_PLUGIN_PATH.'/includes/create-dead-person-form.php');
require_once(MY_PLUGIN_PATH.'/includes/delete-single-person.php');
require_once(MY_PLUGIN_PATH.'/includes/update-person-form.php');
require_once(MY_PLUGIN_PATH.'/includes/searchbar.php');
require_once(MY_PLUGIN_PATH.'/includes/search-persons.php');


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

    register_rest_route( "v1", "logout",array(
        'methods' => 'GET',
        'callback' => 'logout_user'
    ));

    register_rest_route('v1', 'get-single-person', array(
            'methods'  => 'GET',
            'callback' => 'get_single_person',
        )
    );
     
    register_rest_route( "v1", "delete-single-person",array(
        'methods' => 'POST',
        'callback' => 'delete_single_person'
    ));

    register_rest_route( "v1", "redirect-update",array(
        'methods' => 'POST',
        'callback' => 'redirect_update'
    ));

    register_rest_route( "v1", "update-single-person",array(
        'methods' => 'POST',
        'callback' => 'update_dead_person_data'
    ));

    register_rest_route( "v1", "create-comment",array(
        'methods' => 'POST',
        'callback' => 'create_comment'
    ));

    register_rest_route( "v1", "comment-accept",array(
        'methods' => 'POST',
        'callback' => 'comment_accept'
    ));

    register_rest_route( "v1", "comment-delete",array(
        'methods' => 'POST',
        'callback' => 'comment_delete'
    ));

     register_rest_route( "v1", "search-persons",array(
        'methods' => 'POST',
        'callback' => 'search_persons'
    ));

}