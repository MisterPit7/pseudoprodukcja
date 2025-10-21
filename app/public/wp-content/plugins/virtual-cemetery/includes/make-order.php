<?php
    function make_order(){
        $order = wc_create_order();

        $order->add_product(wc_get_product(116),1);

        $address = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'john@example.com',
            'phone'      => '123-456-789',
            'address_1'  => 'Struga 3',
            'city'       => 'Radom',
            'postcode'   => '26-600',
            'country'    => 'PL',
        ];

        $order->set_address($address,"billing");

        $order->calculate_totals();

        $order->set_payment_method('p24-online-payments');

        $order_id = $order->get_id();
    }