<script defer>
    window.addEventListener('load',function(){
       let loader = document.querySelector("#loader");
       let main = document.querySelector("#mainContent");
       loader.style.display="none";
       main.style.display="block";
    })
</script>

<?php

    global $wpdb;
    $table_name = $wpdb->prefix.'zmarli';
    $dead_person_id = $_GET['id'];
    $user_id = get_current_user_id();

    $result = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table_name WHERE ID = %s", $dead_person_id)
    );
    $is_user = false;
    if($result) $is_user = true;

    if($result)$owner = $result[0]->ID_Klienta;

?>

<style>
    <?php include MY_PLUGIN_PATH."assets/css/single-person.css"?>
</style>
<div id="loader"><?php include MY_PLUGIN_PATH."includes/templates/loader.php"?></div>
<?php if($result): ?>
<div id="mainContent" style="display: none;">
<div id='container'>
        <h1 id='header'>Ś.P. <span id="name"><?php esc_html_e($result[0]->Imie) ?></span> <span id="surname"><?php esc_html_e($result[0]->Nazwisko) ?></span></h1>
        <img id='profilePic' src="data:image/png;base64,<?php echo base64_encode($result[0]->Profilowe)?>">
        <hr>
        <h2>
            <div id='info'><span id="dateU"><?php esc_html_e($result[0]->Data_urodzenia) ?></span></div> <div style='padding-top:8px;font-size:3rem'>-</div> <div id='info'><span id="dateS"><?php esc_html_e($result[0]->Data_smierci) ?></span></div>
        </h2>
        <p id='para'><i>"<span id="description"><?php esc_html_e($result[0]->Opis) ?></span>"</i></p>
        <p id='para'><b>Spoczywa na <span id="location"><?php esc_html_e($result[0]->Geolokalizacja) ?></span></b></p>

        <form id="getQrCode">
            <img id="qrCode">
            <button type="submit">pokaz qr code</button>
        </form>

        <section id="imgGallery">
            <?php
                $table_name = $wpdb->prefix.'zdjecia';
                $result = $wpdb->get_results(
                    $wpdb->prepare("SELECT * FROM $table_name WHERE ID_Zmarlego = %d",$_GET['id'] )
                );
            ?>
            <?foreach($result as $photo):?>
                <img class='gallery' width="200px" height="100px" src='data:image/jpeg;base64,<?php echo base64_encode($photo->Zdjecie)?>'>
            <?php endforeach?>
        </section>

        <div id='data-viewer-comments'>
            <h3>Komentarze</h3>
            <?php if(is_user_logged_in()):?>
            <form id="create-comment">
                <?php wp_nonce_field('wp_rest', '_wpnonce')?>
                <input type="hidden" name="id" value="<?php echo $_GET["id"]?>">
                <label for='comment'>Wpisz komentarz</label> 
                <p style='width:100%;text-align:center'><input type='text' id='comment' name="comment" placeholder='Byles/as dla mnie...'/></p>
                <button  type='submit'>Opublikuj</button>
            </form>
            <?php endif?>
            <?php 
                $table_name = $wpdb->prefix.'komentarze';
                $join_table_name = $wpdb->prefix.'users';
                $result = $wpdb->get_results(
                    $wpdb->prepare("SELECT $table_name.*,$join_table_name.display_name FROM $table_name JOIN $join_table_name ON $join_table_name.ID = $table_name.ID_Klienta WHERE ID_Zmarlego = %d",$_GET['id'] )
                );
            ?>  
            <?php if($result):?>
                <?php foreach($result as $row):?>
                    <?php if($row->Is_accepted == 0 && $owner == $user_id ):?>

                        <div id='comment'>
                            <div style="width:95%;">
                            <h4>
                                <?php esc_html_e($row->display_name) ?>
                            </h4>
                            <span>
                                <?php esc_html_e($row->Tekst) ?>
                            </span>
                            </div>
                            <div>
                            <form class="comment-accept">
                                <?php echo wp_nonce_field('wp_rest', '_wpnonce')?>
                                <input type="hidden" name="person_id" value="<?= $_GET["id"]?>">
                                <input type="hidden" name="id" value="<?=$row->ID?>">
                                <button type="submit" name='option' value='Accept'>
                                    <span class="dashicons dashicons-yes"></span>
                                </button>
                            </form>
                            <form class="comment-delete">
                                <?php echo wp_nonce_field('wp_rest', '_wpnonce')?>
                                <input type="hidden" name="person_id" value="<?= $_GET["id"]?>">
                                <input type="hidden" name="id" value="<?= $row->ID?>">
                                <button type="submit" name='option' value="Delete">
                                    <span class="dashicons dashicons-no"></span>
                                </button>
                            </form>
                            </div>
                        </div>
                    <?php 
                        continue;
                        endif
                    ?>
                    <?if($row->Is_accepted != 0 || $row->ID_Klienta == get_current_user_id()):?>

                        <div id='comment'>
                            <div>
                            <h4>
                                <?php esc_html_e($row->display_name) ?>
                            </h4>
                            <span>
                                <?php esc_html_e($row->Tekst) ?>
                            </span>
                            </div>
                            <?php if($row->Is_accepted == 0 && $row->ID_Klienta == get_current_user_id()) esc_html_e("(Administrator jeszcze nie zaakceptował twojego komentarza)") ?>
                        </div>

                    <?php endif?>
                <?php endforeach?>
            <?php endif?>
        </div>
</div>
<?php endif ?>
<?php if(!$is_user): ?>

    <!-- </?php print_r($result)?>
    </?php echo count($result)?> -->
    <div id="container">Brak takiego uztkownika</div>

<?php endif ?>


<?php if( $is_user&& $owner == $user_id): ?>

<div id="centerBtn" style="display: flex;justify-content:center;flex-grow:0;">

    <form id="update-person">
        <?php wp_nonce_field('wp_rest', '_wpnonce') ?>
        <input type="hidden" name="id" id="id-update">
        <button type="submit">Zmień dane</button>
    </form>

    <button type="button" id="delProfile">Usuń profil</button>
</div>
<div>
    <form id="delete-person" hidden="true">
        <?php wp_nonce_field('wp_rest', '_wpnonce') ?>
        <input type="hidden" name="id" id="id-delete">
        <input type="text" name="text" placeholder="Wpisz nazwisko z profilu, aby potwierdzić"><br>
        <div id="delBtns">
            <button type="button" id="cancel">Anuluj</button>
        <button type="submit">Usuń</button>
        </div>
    </form>

</div>
<?php endif ?>

<script>

    jQuery(document).ready(function($){

        $('#getQrCode').submit(function(event){
            event.preventDefault();
            const url = new URL(window.location.href);
            const copyurl = url.href.toString();
            const parts = url.toString().split('id=')[1].split('&')[0];         
            const id = parts;
            if(id === null) return;

            $.ajax({
                type: "POST",
                url: '<?php echo get_rest_url(null,'v1/get-qr-code')?>',
                data: {copyurl},
                success: function(response){
                    if(response.data){
                        $("#qrCode").attr("src","data:image/png;base64," +response.data)
                    }
                }
            })

        })
       
        $(".comment-accept").submit(function(event){
           
            event.preventDefault()
            var formData = new FormData(this);
            

            $.ajax({
                type:'POST',
                url:'<?php echo get_rest_url(null,'v1/comment-accept')?>',
                data:formData,
                contentType:false,
                processData:false,
                success: function(response){
                    if(response.data){
                        window.location.href = response.data
                    }
                }
            })
        })
        $(".comment-delete").submit(function(event){
            event.preventDefault()
            var formData = new FormData(this);

            $.ajax({
                type:'POST',
                url:'<?php echo get_rest_url(null,'v1/comment-delete')?>',
                data:formData,
                contentType:false,
                processData:false,
                success: function(response){
                    if(response.data){
                        window.location.href = response.data
                    }
                }
                
            })
        })

        $("#create-comment").submit(function(event){
            
            var form = $(this)

            $.ajax({
                type:'POST',
                data:form.serialize(),
                url:'<?php echo get_rest_url(null,'v1/create-comment')?>',
                success: function(response){
                    if(response.data){
                        window.location.href = response.data
                    }
                }
            })
        })

        const url = new URL(window.location.href);
        const parts = url.toString().split('id=')[1].split('&')[0];         
        const id = parts;
        if(id === null) return;
        $('#id-delete').val(id);
        $('#id-update').val(id);

          $('#delete-person').submit(function(event){
            event.preventDefault();
            form = $(this)
            $.ajax({
                type: 'POST',
                url: "<?php echo get_rest_url(null, 'v1/delete-single-person')?>",
                data: form.serialize(),
                success: function(response){
                    if(response.data){
                        window.location.href = response.data
                    }
                }
            })
        })

        $('#update-person').submit(function(event){
            event.preventDefault()

            form = $(this)
            
            $.ajax({
                type:'POST',
                url: "<?php echo get_rest_url(null,'v1/redirect-update') ?>",
                data:form.serialize(),
                success:function(response){
                    if(response.data){
                        window.location.href = response.data + '?id=' + id;
                    }
                }

            })
        })

    
        $("#delProfile").click(function(){
            $("#delete-person").attr('hidden',false);
        })

        $("#cancel").click(function(){
            $("#delete-person").attr('hidden',true);
        })

    })

        //Wersja 0.9
        // $.ajax({
        //     type:'GET',
            // url:"<?php // echo get_rest_url(null, 'v1/get-single-person')?>?id="+id,
        //     dataType: 'json',
        //     success: function(response){
        //         //Imie
        //         response= JSON.parse(response.data);
        //         if(response.Imie){
        //             $('#name').append(response.Imie)
        //         }
        //         else{
        //             $('#name').append('Nie udało się pobrać imienia')
        //         }
        //         //Nazwisko
        //         if(response.Nazwisko){
        //             $('#surname').append(response.Nazwisko)
        //         }
        //         else{
        //             $('#surname').append('Nie udało się pobrać nazwiska')
        //         }
        //         //Opis
        //         if(response.Opis){
        //             $('#description').append(response.Opis)
        //         }
        //         else{
        //             $('#description').append('Nie udało się pobrać opisu')
        //         }
        //         //Geolokalizacja
        //         if(response.Geolokalizacja){
        //             $('#location').append(response.Geolokalizacja)
        //         }
        //         else{
        //             $('#location').append('Nie udało się pobrać lokalizacji')
        //         }
        //         //Daty
        //         if(response.Data_urodzenia){
        //             $('#dateU').append(convertDate(response.Data_urodzenia))

        //         }
        //         else{
        //             $('#dateU').append('Nie udało się pobrać daty urodzenia')
        //         }
        //         if(response.Data_smierci){
        //             $('#dateS').append(convertDate(response.Data_smierci))
        //         }
        //         else{
        //             $('#dateS').append('Nie udało się pobrać daty śmierci')
        //         }
        //         //Profilowe
        //         if(response.Profilowe){
        //             $('#profilePic').attr('src','data:image/jpeg;base64,'+response.Profilowe)
        //         }else{
        //             $('#profilePic').attr('src','https://i.pinimg.com/736x/1d/ec/e2/1dece2c8357bdd7cee3b15036344faf5.jpg');
        //         }

        //     }
        // })

      
    function convertDate(date){
        let arr = date.split('-');
        return arr[2] +"-"+arr[1]+"-"+arr[0];
    }

    
</script>
