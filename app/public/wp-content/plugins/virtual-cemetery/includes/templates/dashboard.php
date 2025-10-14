<?php

    global $wpdb;
    $table_name = $wpdb->prefix . 'zmarli';

    $user = wp_get_current_user();

    $result = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE ID_Klienta = %d", $user->ID)
    );

    if($result){
        ?>

        <div>

        <?php foreach($result as $person): ?>

            <div>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($person->Profilowe); ?>" width="200px" height="200px">
                Imie: <?php echo $person->Imie?>
                Nazwisko: <?php echo $person->Nazwisko?>
            </div>

        <?php endforeach;?>

        </div>

        <?php
    }

    ?>

    <button onclick="window.location.href='<?php echo esc_url( home_url( '/create-dead-person/' ) ); ?>'">Dodaj</button>

