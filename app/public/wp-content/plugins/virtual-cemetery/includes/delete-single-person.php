<?php

    function delete_single_person($data){
         $params = $data->get_params();

        if( isset($params['_wpnonce']) &&!wp_verify_nonce($params['_wpnonce'],'wp_rest')){
            return new WP_Error('invalid_nonce','wartość nonce jest niepoprawna',array('status'=>403));
        }

        $user_id =  get_current_user_id();
        $dead_person_id = $params['id'];

        global $wpdb;
        $table_name = $wpdb->prefix . 'zmarli';

        $result = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table_name WHERE ID = %s", $dead_person_id)
        );

        if($result[0]->Is_payed == 0){
            return new WP_Error('invalid_payment','Nie opłacono osoby',array('status'=>403));
        }

        if(!$result){
            return new WP_Error('invalid_value','Nie znaleziono takiej osoby',array('status'=>403));
        }

        if($result[0]->ID_Klienta != $user_id){
            return new WP_Error('invalid_value','Ta osoba nie należy do ciebie',array('status'=>403));
        }

        if (empty($params['text']) || strtolower(trim($result->Nazwisko)) !== strtolower(trim($params['text']))) {
            return new WP_Error('invalid_value', 'Niepoprawne text potwierdzający', ['status' => 403]);
        }

        $delete = $wpdb->delete($table_name , array('ID' => $dead_person_id) , array('%d'));

        if(!$delete){
            return new WP_Error('db_error','Nie udało się usunąć osoby',array('status'=>403));
        }

        $table_name = $wpdb->prefix . "komentarze";
        $delete = $wpdb->delete($table_name , array('ID_Zmarlego' => $dead_person_id) , array('%d'));

        $table_name = $wpdb->prefix . "zdjecia";
        $delete = $wpdb->delete($table_name , array('ID_Zmarlego' => $dead_person_id) , array('%d'));

         return new WP_REST_Response([
            'success' => true,
            'data' => home_url('/dashboard/')
        ],200);


    }
