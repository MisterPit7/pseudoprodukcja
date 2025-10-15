<?php

    function delete_single_person($data){
         $params = $data->get_params();

        if( isset($params['_wpnonce']) &&!wp_verify_nonce($params['_wpnonce'],'wp_rest')){
            return new WP_Error('invalid_nonce','Nonce value cannot be verified',array('status'=>403));
        }

        $user_id =  wp_get_current_user();
        $dead_person_id = $params['id'];

        global $wpdb;
        $table_name = $wpdb->prefix . 'zmarli';

        $result = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table_name WHERE ID = %s", $dead_person_id)
        );

        if(!$result){
            return new WP_Error('invalid_id','it is not your dead person',array('status'=>403));
        }

        if($result[0]->Nazwisko != $params['text']){
            return new WP_Error('invalid_text','could not delete that person',array('status'=>403));
        }

        $delete = $wpdb->delete($table_name , array('ID' => $dead_person_id) , array('%d'));

        if(!$delete){
            return new WP_Error('invalid_id','could not delete that person',array('status'=>403));
        }

         return new WP_REST_Response([
            'success' => true,
            'data' => home_url('/dashboard/')
        ],200);


    }
