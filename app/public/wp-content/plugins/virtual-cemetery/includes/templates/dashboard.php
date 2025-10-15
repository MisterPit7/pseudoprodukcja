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
                <button onclick="window.location.href = '<?php echo home_url('/single-person?id=').$person->ID; ?>'">Pokaż osobe</button>
            </div>

        <?php endforeach;?>

        </div>

        <?php
    }

    ?>

    <button onclick="window.location.href='<?php echo esc_url( home_url( '/create-dead-person/' ) ); ?>'">Dodaj</button>
    <button id="logout">WYLOGUJ SIE</button>

    <script>
        jQuery(document).ready(function($){
            $("#logout").click(function(){
                $.ajax({
                    type: "GET",
                    url: "<?php echo get_rest_url(null, 'v1/logout') ?>",
                    success: function(response){
                        if(response.data){
                            window.location.href = response.data;  
                        }
                    }
                })
            })

            

        })
    </script>
    
