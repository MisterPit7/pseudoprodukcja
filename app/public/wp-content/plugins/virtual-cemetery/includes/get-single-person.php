<?php


require_once MY_PLUGIN_PATH . "/includes/vendor/autoload.php";

use BcMath\Number;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;
use claviska\SimpleImage;

add_shortcode("single_person_page","show_single_person_page");

function get_qr_code($data){
    $params = $data->get_params();

    $url = $params['copyurl'];
    
    $actual_url = $url;
    $logo = MY_PLUGIN_PATH . "/assets/images/logo.jpg";

    $logoImageReader = new SimpleImage();
    $logoImageReader
        ->fromFile($logo)
        ->bestFit(100, 100);

    $logoImageBuilder = new SimpleImage();
    $logoImageBuilder
        ->fromNew(110, 110)
        ->roundedRectangle(0, 0, 110, 110, 10, 'white', 'filled')
        ->overlay($logoImageReader);

    $logoData = $logoImageBuilder->toDataUri('image/png', 100);

    $qrCode = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([])
        ->data($actual_url)
        ->logoPath($logoData)
        ->logoResizeToWidth(100)
        ->encoding(new Encoding('ISO-8859-1'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->build()
        ->getString();

    $base64 = base64_encode($qrCode);

    return new WP_REST_Response([
        'succes' => true,
        'data' => $base64
    ],200);

}

function redirect_update($data){
   
    $params = $data->get_params();

    if( isset($params['_wpnonce']) && !wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Niepoprawna wartość nonce',array('status'=>403));
    }

    $dead_person_id = $params['id'];
    $user_id = get_current_user_id();
    global $wpdb;
    $table_name = $wpdb->prefix . 'zmarli';

   $result = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE ID = %s", $dead_person_id)
   );

    if(!$result){
        return new WP_Error('invalid_value','Nieznaleziono takiej osoby',array('status'=>403));
    }

    if($result[0]->ID_Klienta != $user_id){
        return new WP_Error('invalid_id','Ta osoby nie nalzy do ciebie',array('status'=>403));
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
        return new WP_Error('invalid_value','Nie znaleziono takiej osoby',array('status'=>403));
    }

    $json_data = array(
        "Imie"=> $result[0]->Imie,
        "Nazwisko"=> $result[0]->Nazwisko,
        "Profilowe"=> base64_encode($result[0]->Profilowe),
        "Opis"=>$result[0]->Opis,
        "Geolokalizacja"=>$result[0]->Geolokalizacja,
        "Data_urodzenia"=>$result[0]->Data_urodzenia,
        "Data_smierci"=>$result[0]->Data_smierci,
        "Numer_grobu"=>$result[0]->Numer_grobu
    );

    return new WP_REST_Response([
        'succes'=>true,
        'data'=>json_encode($json_data)
    ],200);

}

function create_comment($data){

    $params = $data->get_params();

    if( isset($params['_wpnonce']) && !wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Niepoprawna wartość nonce',array('status'=>403));
    }

    foreach($params as $param){
        if(empty($param)){
            return new WP_Error('invalid_value','Nie wpisano jednej wartości',array('status'=>403));
            exit;
        }
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'komentarze';

    $wpdb->insert($table_name,[
        'Is_accepted'=>0,
        'ID_Klienta'=>get_current_user_id(),
        'ID_Zmarlego'=>$params['id'],
        'Tekst'=>$params['comment']
    ]);

    return new WP_REST_Response([
        'succes'=> true,
        'data' => home_url('single-person').'?id='.$params['id']
    ],200);    

}

function comment_accept($data){
    
    $params = $data->get_params();
    global $wpdb;
    $table_name = $wpdb->prefix. 'komentarze';

    if( isset($params['_wpnonce']) && !wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Niepoprawna wartość nonce',array('status'=>403));
    }

    foreach($params as $param){
        if(empty($param)){
            return new WP_Error('invalid_value','Nie wpisano jednej wartości',array('status'=>403));
        }
    }

    $wpdb->update($table_name,[
        'is_accepted' => 1,
    ],
    [
        'ID'=>$params['id']
    ]);

    return new WP_REST_Response([
        'succes'=> true,
        'data' => home_url('single-person').'?id='.$params['person_id']
    ],200);
    

}

function comment_delete($data){
    
    $params = $data->get_params();
    global $wpdb;
    $table_name = $wpdb->prefix. 'komentarze';

    if( isset($params['_wpnonce']) && !wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','Niepoprawna wartość nonce',array('status'=>403));
    }

    foreach($params as $param){
        if(empty($param)){
            return new WP_Error('invalid_value','Nie wpisano jednej wartości',array('status'=>403));
        }
    }

    $wpdb->delete($table_name,[
        'ID'=>$params['id']
    ]);

    return new WP_REST_Response([
        'succes'=> true,
        'data' => home_url('single-person').'?id='.$params['person_id']
    ],200);
    

}

function show_single_person_page(){
    
    if (!is_user_logged_in()) {
       echo "<script>window.location.href ='"  . home_url('/login/') . "'</script>";
       exit;
    }

    include_once(MY_PLUGIN_PATH.'includes/templates/single-person-page.php');
}