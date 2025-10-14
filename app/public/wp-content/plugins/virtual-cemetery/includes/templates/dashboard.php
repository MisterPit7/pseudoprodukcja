<?php


    global $wpdb;
    $table_name = $wpdb->prefix . 'zmarli';

    $user = wp_get_current_user();
    $result = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE ID_Klienta = %s", $user)
    );

    if($result){
        ?>

        <div>

        <?php foreach($result as $person): ?>

            <div>
                <!-- kiedys bedzie profilowe -->
                Imie: <?php $person->imie?>
                Nazwisko: <?php $person->nazwisko?>
            </div>

        <?php endforeach;?>

        </div>

        <?php
    }

