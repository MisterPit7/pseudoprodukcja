<?php

add_shortcode("payment_form","show_payment_form");

function payment_form($data){
    $params = $data->get_params();
    return $params;
}


function show_payment_form(){
    include MY_PLUGIN_PATH.'/includes/templates/payment-form.php';
}