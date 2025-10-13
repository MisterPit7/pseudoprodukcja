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
    $table_name = $wpdb->prefix . 'klienci';
    
  

     $options = [
        'cost' => 12,
        'salt' => 'b0x5e9ma6b0c36z9n6ql0f'
    ];

    $email = $params['email'];
    $password = password_hash($params['password'], PASSWORD_BCRYPT, $options);

    $result = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE Email = %s AND Haslo = %s", $email,$password)
    );

    print_r($result);

    if($result == ''){
        return new WP_Error('invalid_value','no account found',array('status'=>403));
    }


}

function show_login_form(){
    include MY_PLUGIN_PATH."/includes/templates/login-form.php";
}