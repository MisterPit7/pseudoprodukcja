<?php
    function make_order($data){

        $params = $data->get_params();
        $user_id = $params['user_id'];

    if( empty($params['_wpnonce']) || !wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Error('invalid_nonce','wartość nonce jest niepoprawna',array('status'=>403));
    }

    if(!preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŻŹ]{2,50}$/", $params['name'])){
        return new WP_Error('invalid_value','Niepoprawna wartość imienia',['status'=>403]);
    }

    if(!preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŻŹ]{2,50}$/", $params['surname']) ){
        return new WP_Error('invalid_value','Niepoprawna wartość nazwiska',['status'=>403]);
    }
     
    if(!preg_match("/^[0-9]{9}$/", $params['phone']) ){
        return new WP_Error('invalid_value','Niepoprawna wartość numeru telefonu',['status'=>403]);
    }

    if(!preg_match("/^[a-zA-Z0-9, ąćęłńóśźżĄĆĘŁŃÓŚŹŻ]{2,50}$/", $params['address_1'])){
        return new WP_Error('invalid_value','Niepoprawna wartość lokalizacji grobu',['status'=>403]);
    }

    if(!preg_match('/^\d{2}-\d{3}$/', $params['post_code'])){
        return new WP_Error('invalid_value','Niepoprawna wartość numeru grobu',['status'=>403]);
    }   

    if(!preg_match('/^[A-ZĄĆĘŁŃÓŚŹŻ][a-ząćęłńóśźż]+(?:[-\s][A-ZĄĆĘŁŃÓŚŹŻ][a-ząćęłńóśźż]+)*$/u', $params['city'])){
        return new WP_Error('invalid_value','Niepoprawna wartość miasta',['status'=>403]);
    }





        $order = wc_create_order([
            'customer_id' => $user_id 
        ]);
        $isProfileExisting = -1;
        if(isset($params['dead_id'])) {
            $isProfileExisting = $params['dead_id'];
        }

        $numberOfDays = $params['howLong'] * 365;
        $date = date("Y-m-d",strtotime("+" . $numberOfDays ."days"));

        $order->update_meta_data("expireDate",$date);        
        $order->update_meta_data( 'isProfileExisting', $isProfileExisting);
        $order->save();
    
        switch($params['howLong']){
            case '5':
                $product_id = 116;
                break;
            case '10':
                $product_id = 159;
                break;
            case '20':
                $product_id = 160;
                break;
        }

        $order->add_product(wc_get_product($product_id),1);

        $address = [
            'first_name' => $params['name'],
            'last_name'  => $params['surname'],
            'email'      => $params['email'],
            'phone'      => $params['phone'],
            'address_1'  => $params['address_1'],
            'city'       => $params['city'],
            'postcode'   => $params['post_code'],
            'country'    => $params['country'],
        ];

        $order->set_address($address,"billing");

        $order->calculate_totals();

        $order->set_payment_method('p24-online-payments');

        return new WP_REST_Response([
        'succes'=>true,
        'data'=> $order->get_checkout_payment_url(),
        ],200);
    }