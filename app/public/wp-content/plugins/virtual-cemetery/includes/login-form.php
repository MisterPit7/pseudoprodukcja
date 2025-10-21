<?php

add_shortcode('login_form','show_login_form');

function login_user($data){
    
    $params = $data->get_params();

    // $secretKey = CaptchaSecretKey;
    // $token = $params['g-recaptcha-response'] ?? '';

    // if (!$token) {
    //     return new WP_Error('captcha','Brak tokena captcha',array('status'=>403));
    // }

    

    // // Weryfikacja w Google
    // $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    // $data = [
    //     'secret' => $secretKey,
    //     'response' => $token,
    //     'remoteip' => $_SERVER['REMOTE_ADDR']
    // ];

    //     $options = [
    //     'http' => [
    //         'method' => 'POST',
    //         'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
    //         'content' => http_build_query($data)
    //     ]
    // ];

    // $context = stream_context_create($options);
    // $response = file_get_contents($verifyUrl, false, $context);
    // $result = json_decode($response, true);

    // // Sprawdzenie sukcesu
    // if (!$result['success']) {
    //    new WP_Error('captcha','Niepoprawna wartość captchy',array('status'=>403));
    // } 


    if( isset($params['_wpnonce']) &&!wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Niepoprawna wartość nonce',array('status'=>403));
    }

    foreach($params as $param){
        if(!isset($param)){
            return new WP_Error('invalid_value','Nie wpisano którejś wartości',array('status'=>403));
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
        return new WP_Error('invalid_value','Nie znaleziono takiej osoby',array('status'=>403));
    }

    if(!password_verify($password, $result[0]->user_pass)){
        return new WP_Error('invalid_value','Niepoprawne hasło',array('status'=>403));
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