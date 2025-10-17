<?php

function search_persons($data){
    $params = $data->get_params();

    global $wpdb;
    $table_name = $wpdb->prefix . 'zmarli';

    

    $enquiry="SELECT * FROM $table_name WHERE";

    if(isset($params['name'])){
        $enquiry+= "Imie = ". $params['name'];
    }

    if(!isset($params['name']) && !isset($params['surname'])){
        return new WP_Error('invalid data','No data send found', array('status'=>403));
    }
    
}