<?php

use BcMath\Number;

add_shortcode("single_person_page","show_single_person_page");

function redirect_update($data){
   
    $params = $data->get_params();

    if( isset($params['_wpnonce']) && !wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Nonce value cannot be verified',array('status'=>403));
    }

    $dead_person_id = $params['id'];
    $user_id = get_current_user_id();
    global $wpdb;
    $table_name = $wpdb->prefix . 'zmarli';

   $result = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE ID = %s", $dead_person_id)
   );

    

    if(!$result){
        return new WP_Error('invalid_id','it is not your dead person',array('status'=>403));
    }

    if($result[0]->ID_Klienta != $user_id){
        return new WP_Error('invalid_id','it is not your dead person2',array('status'=>403));
    }
    
    return new WP_REST_Response([
        'succes'=> true,
        'data' => home_url('update-person-form')
    ],200);

}

function get_single_person(){

    $id = $_GET['id'];
    

    global $wpdb;
    $table_name = $wpdb->prefix . 'zmarli';

    $result = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE ID = %d", $id)
    );
    
    if(!$result){
        return new WP_Error('invalid_value','no account found',array('status'=>403));
    }

    $json_data = array(
        "Imie"=> $result[0]->Imie,
        "Nazwisko"=> $result[0]->Nazwisko,
        "Profilowe"=> base64_encode($result[0]->Profilowe),
        "Opis"=>$result[0]->Opis,
        "Geolokalizacja"=>$result[0]->Geolokalizacja,
        "Data_urodzenia"=>$result[0]->Data_urodzenia,
        "Data_smierci"=>$result[0]->Data_smierci,
    );

    return new WP_REST_Response([
        'succes'=>true,
        'data'=>json_encode($json_data)
    ],200);

}

function show_single_person_page(){
    

    include_once(MY_PLUGIN_PATH.'includes/templates/single-person-page.php');
}