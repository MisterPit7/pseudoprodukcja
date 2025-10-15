<?php

add_shortcode('login_form','show_login_form');

function login_user($data){
    
    $params = $data->get_params();

    if( isset($params['_wpnonce']) &&!wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Nonce value cannot be verified',array('status'=>403));
    }

    foreach($params as $param){
        if(!isset($param)){
            return new WP_Error('invalid_value','one value not set',array('status'=>403));
        }
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'users';

    $email = $params['email'];
    $password = $params['password'];

    $result = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE user_email = %s", $email)
    );
    

    if(!$result){
        return new WP_Error('invalid_value','no account found',array('status'=>403));
    }

    if(!password_verify($password, $result[0]->user_pass)){
        return new WP_Error('invalid_value','incorrect paswrd',array('status'=>403));
    }

    wp_set_current_user($result[0]->ID);
    wp_set_auth_cookie($result[0]->ID);

    return new WP_REST_Response([
        'success' => true,
        'redirect' => home_url('/dashboard/')
    ],200);
}

function show_login_form(){

    if (is_user_logged_in()) {
       echo "<script>window.location.href='" . esc_url( home_url('/dashboard/') ) . "';</script>";
       exit;
    }
    
    include MY_PLUGIN_PATH."/includes/templates/login-form.php";

}