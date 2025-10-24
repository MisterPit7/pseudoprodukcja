<?php

function update_localization($data){
    $params = $data->get_params();
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'zmarli';


    $wpdb->update($table_name, 
    ['Szerokosc_geograficzna'=>$params['lat'],
    'Wysokosc_geograficzna'=>$params['lng']] , 
    ['ID'=>$params['id']]);

    
    return $params;
}