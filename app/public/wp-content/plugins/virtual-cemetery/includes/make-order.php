<?php
    function make_order($data){

        $params = $data->get_params();
        $user_id = $params['user_id'];
        $order = wc_create_order([
            'customer_id' => $user_id 
        ]);

        $order->update_meta_data( 'isProfileExisting', false);
        $order->save();

        $order->add_product(wc_get_product(116),1);

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