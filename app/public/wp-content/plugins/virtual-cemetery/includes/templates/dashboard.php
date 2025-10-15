<style>
    <?php include MY_PLUGIN_PATH."assets/css/dashboard.css"?>
</style>
<?php

    global $wpdb;
    $table_name = $wpdb->prefix . 'zmarli';

    $user = wp_get_current_user();


    $result = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE ID_Klienta = %d", $user->ID)
    );

    if($result){
        ?>

        <div id="dead-person-grid">

        <?php foreach($result as $person): ?>

            <div id="dead-person" name="dead-person" value="<?php echo $person->ID?>">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($person->Profilowe); ?>" width="200px" height="200px">
                <p> <?php echo $person->Imie?> <?php echo $person->Nazwisko?></p>
            </div>

        <?php endforeach;?>

        </div>

        <?php
    }

    ?>
    <div id="btns">
        <button onclick="window.location.href='<?php echo esc_url( home_url( '/create-dead-person/' ) ); ?>'">Dodaj</button>
        <button id="logout">WYLOGUJ SIE</button>
    </div>
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
    
