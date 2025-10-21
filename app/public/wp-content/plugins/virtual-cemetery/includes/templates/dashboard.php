<script defer>
    window.addEventListener('load',function(){
       let loader = document.querySelector("#loader");
       let main = document.querySelector("#mainContent");
       console.log(main);
       loader.style.display="none";
       main.style.display="block";
    })
</script>

<style>
    <?php include MY_PLUGIN_PATH."assets/css/dashboard.css"?>
</style>
    <div id="loader"><?php include MY_PLUGIN_PATH."includes/templates/loader.php"?></div>
<?php
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'zmarli';

    $user = wp_get_current_user();


    $result = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE ID_Klienta = %d", $user->ID)
    );
?>
<div id="mainContent" style="display: none;">
     <div id="btns">
        <button id="add" onclick="window.location.href='<?php echo esc_url( home_url( '/payment-form/' ) ); ?>';document.querySelector('#add').disabled=true;">Dodaj</button>
        <button id="logout">Wyloguj się</button>
    </div>
        <div id="dead-person-grid">
        <? if($result): ?>
            <?php foreach($result as $person): ?>

            <div id="dead-person">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($person->Profilowe); ?>" width="200px" height="200px">
                <p> <?php esc_html_e($person->Imie) ?> <?php esc_html_e($person->Nazwisko) ?></p>
                <div style="display: flex;justify-content:center;">
                    <button class="show" onclick="window.location.href = '<?php echo home_url('/single-person?id=').$person->ID; ?>'">Pokaż osobe</button>
                </div>
            </div>

             <?php endforeach;?>
        <?php endif ?>
        </div>

        <?php
    

    ?>
</div>
<button id="test" style="display:none;">Test</button> <!-- Dla pamietnych -->
<script>
    <?php require_once(MY_PLUGIN_PATH."assets/js/popup.js") ?>

    document.querySelector('#test').addEventListener("click",()=>{
        show_popup("test");
    })
</script>
    <script>
        

        jQuery(document).ready(function($){
            $("#logout").click(function(){
                $("#logout").attr("disabled",true);
                $.ajax({
                    type: "GET",
                    url: "<?php echo get_rest_url(null, 'v1/logout') ?>",
                    success: function(response){
                        if(response.data){
                            window.location.href = response.data;  
                        }
                    },error:function(response){
                        let error = response.responseJSON;
                        if(error['code'] != "invalid_nonce") show_popup(error['message']);
                    }
                })
            })

            

        })
    </script>
    
