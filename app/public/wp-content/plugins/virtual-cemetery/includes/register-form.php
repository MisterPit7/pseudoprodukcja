<?php

add_shortcode('register_form','show_register_form');

function register_user($data){
    
    $params = $data->get_params();

    if( isset($params['_wpnonce']) &&!wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Niepoprawna wartość nonce',array('status'=>403));
    }

    if(!preg_match("/^[a-zA-Z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ]{5,50}$/", $params['nickname'])){
    return new WP_Error('invalid_value','Niepoprawna nazwa użytkownika',['status'=>403]);
    }

    if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/", $params['password'])){
        return new WP_Error('invalid_value','Hasło jest za słabe',['status'=>403]);
    }

    foreach($params as $param){
        if(!isset($param)){
            return new WP_Error('invalid_value','Niewpisano jednej wartości',array('status'=>403));
        }
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'users';

    $nickname = $params['nickname']; 
    $email = $params['email']; 
    $result = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE user_nicename = %s", $nickname)
    );

    $result2 = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE user_email = %s", $email)
    );

    if( count($result2) !== 0 ){
        return new WP_Error('invalid_value','Email jest już zajęty',array('status'=>403));
    }

    if( count($result) !== 0 ){
        return new WP_Error('invalid_value','Nazwa uzytkownika jest już zajęta',array('status'=>403));
    }


    date_default_timezone_set('Europe/Warsaw'); 

    $wpdb->insert(
        $table_name,    
        array(
           'user_login' => $nickname,
           'user_pass' => password_hash($params['password'], PASSWORD_BCRYPT),
           'user_nicename' => $nickname,
           'user_email' => $params['email'],
           'user_registered' => date("Y-m-d H:i:s"),
           'display_name' => $nickname
        )
    );

}

function show_register_form(){

    

    include MY_PLUGIN_PATH."/includes/templates/register-form.php";
}