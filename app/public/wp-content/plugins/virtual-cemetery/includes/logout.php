<?php

function logout_user(){
    wp_logout();

    return new WP_REST_Response([
        'succes'=>true,
        'data'=> home_url('/login/')
    ],200);
}