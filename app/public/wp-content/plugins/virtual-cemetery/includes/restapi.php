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
require_once(MY_PLUGIN_PATH.'/includes/payment-form.php');
require_once(MY_PLUGIN_PATH.'/includes/make-order.php');
require_once(MY_PLUGIN_PATH.'/includes/update-localization.php');


require_once(MY_PLUGIN_PATH . '/includes/rate-limiter.php');


function create_rest_endpoint(){

    register_rest_route( "v1", "delete-person-photo",array(
        'methods' => 'POST',
        'callback' => 'delete_person_photo'
    ));

    register_rest_route( "v1", "update-photos",array(
        'methods' => 'POST',
        'callback' => 'update_photos'
    ));

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

    register_rest_route( "v1", "get-qr-code",array(
        'methods' => 'POST',
        'callback' => 'get_qr_code'
    ));

     register_rest_route( "v1", "make-order",array(
        'methods' => 'POST',
        'callback' => 'make_order'
    )); 

    register_rest_route( "v1", "payment-form",array(
        'methods' => 'POST',
        'callback' => 'payment_form'
    ));

    register_rest_route( "v1", "update-localization",array(
        'methods' => 'POST',
        'callback' => 'update_localization'
    ));



    add_filter('rest_pre_dispatch', function ($result, $server, $request) {

        $route = $request->get_route();
        $ip = $_SERVER['REMOTE_ADDR'];
        $method = $request->get_method();

       
        $limits = [
            '/v1/register'             => [5, 60],      
            '/v1/login'                => [10, 60],       
            '/v1/logout'               => [10, 60],              
            '/v1/create-dead-person'   => [10, 60],      
            '/v1/update-single-person' => [10, 60],     
            '/v1/delete-single-person' => [10, 60],     
            '/v1/redirect-update'      => [10, 60],     
            '/v1/update-photos'        => [10, 60],      
            '/v1/delete-person-photo'  => [10, 60],           
            '/v1/create-comment'       => [10, 60],     
            '/v1/comment-accept'       => [15, 60],      
            '/v1/comment-delete'       => [15, 60],      
            '/v1/search-persons'       => [10, 60],            
            '/v1/get-profiles'         => [15, 60],     
            '/v1/get-single-person'    => [15, 60],      
            '/v1/get-qr-code'          => [5, 15],
        ];

        foreach ($limits as $endpoint => $rule) {
            if (strpos($route, $endpoint) !== false) {
                [$max, $seconds] = $rule;
                $key = "{$ip}_{$endpoint}";
                
                if (!RateLimiter::check($key, $max, $seconds)) {
                    return new WP_Error(
                        'too_many_requests',
                        'Zbyt wiele żądań. Spróbuj ponownie za chwilę.',
                        [
                            'status' => 429,
                            'retry_after' => $seconds
                        ]
                    );
                }
            }
        }

        return $result;
    }, 10, 3);

}