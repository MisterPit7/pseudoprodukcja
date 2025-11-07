<?php
/*
 * Plugin Name:       Virtual Cemetery
 * Description:       Simple plugin to do something 
 * Version:           1.0
 * Author:            MisterPit, MARVI2, Madness  
 */


//I am sorry 

if(!defined("ABSPATH")){
    die("you cannot be here");
}

if(!class_exists('VirtualCemetery')){

    class VirtualCemetery{
        public function __construct()
        {
            define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__) );
            define('CaptchaKey','6Lc2v_ArAAAAAHfeltN6JiN8AT06Y_hw3MfRPAhC');
            define('CaptchaSecretKey','6Lc2v_ArAAAAAOLAlYz2qNSM0TkJK1seRCZ5kVvw');
            define('LocationApiKey','AIzaSyAVhrX7Y5AUPp6EJ5RBXMK38cNPXNuseuk');
        }

        public function initialize(){
            include_once(MY_PLUGIN_PATH.'/includes/database-setup.php');
            include_once(MY_PLUGIN_PATH. '/includes/restapi.php');
        }

    }

    $virtualCemetery = new VirtualCemetery;
    $virtualCemetery->initialize();

    // add_action( 'woocommerce_thankyou', function( $order_id ) {
        
    //     if ( ! $order_id ) return;

    //     $order = wc_get_order( $order_id );

    //     if ( $order->is_paid() ) {

    //         $my_value = $order->get_meta( 'isProfileExisting', true );

    //         if($my_value){
    //         global $wpdb;
    //         $table_name = $wpdb->prefix . 'zmarli';
            
    //         $wpdb->insert(
    //             $table_name,
    //             array(
    //             'Imie' => 'Nowy',
    //             'Nazwisko' => 'Użytkownik',
    //             'Profilowe' => NULL,
    //             'Data_urodzenia' => '01-01-2025',
    //             'Data_smierci' => '01-01-2025',
    //             'Opis' => 'Opis',
    //             'Geolokalizacja' => '[Lokalizacja grobu]',
    //             'ID_Klienta' => $order->get_customer_id(),
    //             'Numer_grobu' => '[Number Grobu]'
    //             )
    //         );


    //         wp_safe_redirect( home_url( '/dashboard/' ) );
    //         exit;
    //         }
    //     }
    // });

    // add_action( 'template_redirect', function() {

    // if ( ! is_order_received_page() ) return;
  
    // $order_id  = absint( get_query_var( 'order-received' ) );
    // $order_key = wc_clean( $_GET['key'] ?? '' );

    // if ( ! $order_id || ! $order_key ) return;
  
    // $order = wc_get_order( $order_id );

    // // Verify order key matches (security)
    // if ( $order->get_order_key() !== $order_key ) return;

    // // Only redirect for paid orders
    // if ( $order->is_paid() ) {

    //  $my_value = $order->get_meta( 'isProfileExisting', true );
    //  $expireDate = $order->get_meta('expireDate',true);
    //     if ($my_value  == -1) {
    //         global $wpdb;
    //         $table_name = $wpdb->prefix . 'zmarli';

    //         $wpdb->insert(
    //             $table_name,
    //             array(
    //                 'Imie'             => 'Nowy',
    //                 'Nazwisko'         => 'Użytkownik',
    //                 'Profilowe'        => NULL,
    //                 'Data_urodzenia'   => '2025-01-01',
    //                 'Data_smierci'     => '2025-01-01',
    //                 'Opis'             => 'Opis',
    //                 'Geolokalizacja'   => '[Lokalizacja grobu]',
    //                 'ID_Klienta'       => $order->get_customer_id(),
    //                 'Numer_grobu'      => '[Number Grobu]',
    //                 'Is_payed'         => true,
    //                 'Data_wygasniecia' => $expireDate,

    //             )
    //         );
    //     }else{
    //         global $wpdb;
    //         $table_name = $wpdb->prefix . 'zmarli';
    //         $wpdb->update(
    //             $table_name,
    //             [
    //                 "Is_payed" => true,
    //                 'Data_wygasniecia' => $expireDate,
    //             ],
    //             [
    //                 "ID"=>$my_value,
    //             ]
    //             );

    //     }
    //     wp_safe_redirect( home_url( '/dashboard/' ) );
    //     exit;
    // }
    // });

}
