<?php

function search_persons($data){
    $params = $data->get_params();

    global $wpdb;
    $table_name = $wpdb->prefix . 'zmarli';

    

    $name = trim($params['name']);
    $surname = trim($params['surname']);

    if (!empty($name) && !empty($surname)) {
    $result= $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE Imie = %s AND Nazwisko = %s", $name, $surname)
    );
    }


    else if (!empty($name)) {
        $result = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table_name WHERE Imie = %s", $name)
        );
    }


    else if (!empty($surname)) {
        $result = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table_name WHERE Nazwisko = %s", $surname)
        );
    }

    if(empty($result)){
        return new WP_Error('no_person_found','Nieznaleziono takiej osoby',array('status'=>403));
    }

    $json_data = array_map(function($row) {
            return array(
                "ID" => $row->ID,
                "Imie" => $row->Imie,
                "Nazwisko" => $row->Nazwisko,
                "Profilowe" => base64_encode($row->Profilowe)
            );
        }, $result);
    
    return new WP_REST_Response([
        'succes' => true,
        'data' => json_encode($json_data)
    ],200);

}