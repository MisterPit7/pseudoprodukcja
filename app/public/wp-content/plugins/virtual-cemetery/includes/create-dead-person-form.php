<?
add_shortcode('create_dead_person_form','show_create_dead_person_form');


function create_dead_person($data){
    
    $params = $data->get_params();

    if( empty($params['_wpnonce']) || !wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Nonce value cannot be verified',array('status'=>403));
    }

    if(!preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŻŹ]{2,50}$/", $params['name']) 
        || !preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŻŹ]{2,50}$/", $params['surname']) 
        || !preg_match("/^[0-9-]{10}$/", $params['birth-date']) 
        || !preg_match("/^[0-9-]{10}$/", $params['death-date'])
        || !preg_match("/^[a-zA-Z0-9.,-\/ ąćęłńóśźżĄĆĘŁŃÓŚŹŻ]{2,50}$/", $params['localization'])
        || !preg_match("/^[a-zA-Z0-9.,-\/ ąćęłńóśźżĄĆĘŁŃÓŚŹŻ]{2,50}$/", $params['graveID'])
        || !preg_match("/^[a-zA-Z0-9.,-\/ ąćęłńóśźżĄĆĘŁŃÓŚŹŻ]{0,500}$/", $params['description'])
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
                
            } else {
                return new WP_Error('invalid_value','wrong extension',['status'=>403]);
            }

        } else{
            return new WP_Error('invalid_value','wrong extension2',['status'=>403]);
        }

    }else{
        return new WP_Error('invalid_value','wrong extension3',['status'=>403]);
    }

    foreach($params as $param){
        if(!isset($param)){
            return new WP_Error('invalid_value','one value not set',array('status'=>403));
        }
    }

   

    global $wpdb;
    $table_name = $wpdb->prefix . 'zmarli';


    $user = wp_get_current_user();

    $wpdb->insert(
        $table_name,
        array(
           'Imie' => $params['name'],
           'Nazwisko' => $params['surname'],
           'Profilowe' => $bytes,
           'Data_urodzenia' => $params['birth-date'],
           'Data_smierci' => $params['death-date'],
           'Opis' => $params['description'],
           'Geolokalizacja' => $params['localization'],
           'ID_Klienta' => $user->ID,
           'Numer_grobu' => $params['graveID']
        )
    );

    $dead_id = $wpdb->insert_id;
    $table_name = $wpdb->prefix . 'zdjecia';

    if(empty($_FILES['gallery-photo']['tmp_name'][0])){
        return new WP_REST_Response([
            'succes'=>true,
            'data'=> home_url('/dashboard/')
        ],200);
        exit;
    }

    $photos = $_FILES['gallery-photo']['tmp_name'];
    $counter = 0;
    foreach($photos as $photo){
        $bytes = file_get_contents($photo);
        $wpdb->insert(
            $table_name,
            array(
               'ID_Zmarlego' => $dead_id,
               'Zdjecie' => $bytes
            )
        );
        $counter++;
        if($counter == 6) break;
    }

    return new WP_REST_Response([
        'succes'=>true,
        'data'=> home_url('/dashboard/')
    ],200);

}


function show_create_dead_person_form(){
    include_once MY_PLUGIN_PATH . '/includes/templates/create-dead-person-form.php';
}