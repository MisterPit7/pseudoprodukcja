<?php

add_shortcode("update-person-form",'show_update_person_form');

function update_dead_person_data($data){
        
    $params = $data->get_params();

    if( empty($params['_wpnonce']) || !wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Nonce value cannot be verified',array('status'=>403));
    }

    if(!preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŻŹ]{2,50}$/", $params['name']) 
        || !preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŻŹ]{2,50}$/", $params['surname']) 
        || !preg_match("/^[0-9-]{10}$/", $params['birth-date']) 
        || !preg_match("/^[0-9-]{10}$/", $params['death-date'])
        || !preg_match("/^[a-zA-Z0-9.,-\/ ąćęłńóśźżĄĆĘŁŃÓŚŹŻ]{2,50}$/", $params['localization'])
    ){
    return new WP_Error('invalid_value','wrong value',['status'=>403]);
    }

    if(!empty($_FILES['photo']['tmp_name'])){
        $bytes = file_get_contents($_FILES['photo']['tmp_name']);
        $file_tmp  = $_FILES['photo']['tmp_name'];      
        $image_info = getimagesize($file_tmp);

        if ($image_info !== false) {
            $mime_type = $image_info['mime']; 

            $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
            if (in_array($mime_type, $allowed)) {
                
            } 
        } 
    }

    foreach($params as $param){
        if(!isset($param)){
            return new WP_Error('invalid_value','one value not set',array('status'=>403));
        }
    }

    $user_id =  get_current_user_id();
    $dead_person_id = $params['id'];

    global $wpdb;
    $table_name = $wpdb->prefix . 'zmarli';

    if(!empty($_FILES['photo']['tmp_name'])){
        $wpdb->update(
            $table_name,
            array(
                'Imie' => $params['name'],
                'Nazwisko' => $params['surname'],
                'Profilowe' => $bytes,
                'Data_urodzenia' => $params['birth-date'],
                'Data_smierci' => $params['death-date'],
                'Opis' => $params['description'],
                'Geolokalizacja' => $params['localization']
            ),
            array(
                'ID'=>$dead_person_id,
                'ID_Klienta' => $user_id
            ),

        );
    }else{
        $wpdb->update(
            $table_name,
            array(
                'Imie' => $params['name'],
                'Nazwisko' => $params['surname'],
                'Data_urodzenia' => $params['birth-date'],
                'Data_smierci' => $params['death-date'],
                'Opis' => $params['description'],
                'Geolokalizacja' => $params['localization']
            ),
            array(
                'ID'=>$dead_person_id,
                'ID_Klienta' => $user_id
            ),

        );
    }

    return new WP_REST_Response([
        'success' => true,
        'data' => home_url('/single-person').'?id='.$dead_person_id
    ],200);
   
}

function delete_person_photo($data){
    $params = $data->get_params();
    if( empty($params['_wpnonce']) || !wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Nonce value cannot be verified',array('status'=>403));
    }
    if(!isset($params['id'])){
        return new WP_Error('invalid_value','id not set',array('status'=>403));
    }

    if(!isset($params['photo-id'])){
        return new WP_Error('invalid_value','photo-id not set',array('status'=>403));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'zdjecia';
    $wpdb->delete(
        $table_name,
        array(
            'ID' => $params['photo-id']
        )
    );

    return new WP_REST_Response([
        'success' => true,
        'data' => home_url('/update-person-form').'?id='.$params['id']
    ],200);

}

function update_photos($data){
    $params = $data->get_params();
    
    if( empty($params['_wpnonce']) || !wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Nonce value cannot be verified',array('status'=>403));
    }

    if(!isset($params['id'])){
        return new WP_Error('invalid_value','id not set',array('status'=>403));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'zdjecia';
    $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE ID_Zmarlego = %d",
            $params['id']
        )
    );

    if($wpdb->num_rows >= 6){
        return new WP_Error('limit_exceeded','You can upload max 5 photos',array('status'=>403));
    }

    if(empty($_FILES['photo']['tmp_name'])){
        return new WP_Error('invalid_value','no file provided',array('status'=>403));
    }
    $bytes = file_get_contents($_FILES['photo']['tmp_name']);
    
    $wpdb->insert(
        $table_name,
        array(
            'ID_Zmarlego' => $params['id'],
            'Zdjecie' => $bytes
        )
    );
    return new WP_REST_Response([
        'success' => true,
        'data' => home_url('/update-person-form').'?id='.$params['id']
    ],200);

}

function show_update_person_form(){
    include_once (MY_PLUGIN_PATH.'includes/templates/update-person-form.php');
}