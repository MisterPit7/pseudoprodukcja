<?php

add_shortcode('register_form','show_register_form');

function register_user($data){
    
    $params = $data->get_params();



    if( isset($params['_wpnonce']) &&!wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Nonce value cannot be verified',array('status'=>403));
    }

    foreach($params as $param){
        if(!isset($param)){
            return new WP_Error('invalid_value','one value not set',array('status'=>403));
        }
    }

    $options = [
        'cost' => 12,
        'salt' => 'b0x5e9ma6b0c36z9n6ql0f'
    ];

    global $wpdb;
    $table_name = $wpdb->prefix . 'klienci';

    $email = $params['email']; 
    $result = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE Email = %s", $email)
    );

    if(count($result) >0){
        return new WP_Error('invalid_value','email is already set',array('status'=>403));
    }

    $wpdb->insert(
        $table_name,
        array(
           'Imie' => $params['name'],
           'Nazwisko' => $params['surname'],
           'Email' => $params['email'],
           'Haslo' => password_hash($params['password'], PASSWORD_BCRYPT, $options) 
        )
    );

}

function show_register_form(){
    include MY_PLUGIN_PATH."/includes/templates/register-form.php";
}