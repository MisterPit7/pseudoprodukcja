<script defer>
    function convertDate(date){
        let arr = date.split('-');
        return arr[0];
    }

    window.addEventListener('load',function(){
       let loader = document.querySelector("#loader");
       let main = document.querySelector("#mainContent");
       loader.style.display="none";
       main.style.display="block";

       let dateU = document.querySelector("#dateU");
       dateU.innerHTML = convertDate(dateU.innerHTML);
       let dateS = document.querySelector("#dateS");
       dateS.innerHTML = convertDate(dateS.innerHTML);
    })
</script>
<script> <?php echo file_get_contents("https://maps.googleapis.com/maps/api/js?key=AIzaSyAVhrX7Y5AUPp6EJ5RBXMK38cNPXNuseuk")?></script>

<?php

    global $wpdb;
    $table_name = $wpdb->prefix.'zmarli';
    $dead_person_id = $_GET['id'];
    $user_id = get_current_user_id();

    $result = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table_name WHERE ID = %s", $dead_person_id)
    );

    if($result[0]->Is_payed == 1 && $result[0]->Data_wygasniecia < date('Y-m-d', time())){
        $wpdb->update($table_name,['Is_payed'=>false],['ID'=>$dead_person_id]);
        echo "<script>window.location.reload();</script>";
    }

    

    $is_user = false;

    if($result){
        $is_user = true;
        $owner = $result[0]->ID_Klienta;
    }
?>

<style>
    <?php include MY_PLUGIN_PATH."assets/css/single-person.css"?>
</style>
<div id="loader"><?php include MY_PLUGIN_PATH."includes/templates/loader.php"?></div>

<?php if(!$result[0]->Is_payed): ?>

    <style>
        #container{
            filter: blur(30px);
            -webkit-filter: blur(50px);
        }

       
    </style>

    <?php foreach ($result[0] as $key => &$value) {
                if($key != 'Is_payed' && $key != 'ID_Klienta' && $key != "ID"){
                    $value = "";
                }
        }
        unset($value); ?>

<?php endif;?>

<?php if($result): ?>

<div id="mainContent" style="display: none;">
<div id='container'>
        <div id="background"> 
            <img src="data:image/png;base64,<?php print(base64_encode(file_get_contents(MY_PLUGIN_PATH."assets\images\obramowka2.png"))); ?>">
        </div>
        <h1 id='header'><span id="name">Ś.P. <?php esc_html_e($result[0]->Imie) ?></span> <span id="surname"><?php esc_html_e($result[0]->Nazwisko) ?></span></h1>
        <div id="info">Żył(a) w latach</div>
        <div id='info'><span id="dateU"><?php esc_html_e($result[0]->Data_urodzenia) ?></span> - <span id="dateS"><?php esc_html_e($result[0]->Data_smierci) ?></span></div>
        <img id='profilePic' src="data:image/png;base64,<?php echo base64_encode($result[0]->Profilowe)?>">
        <hr>
        <h3 id="qoute"><i>"Na zawsze pozostanie w naszej pamięci"</i></h3>
        <h4 id="description"><?php esc_html_e($result[0]->Opis) ?></h4>

        <?php if($result[0]->Is_payed == 1): ?>

        <form id="getQrCode">
            <button type="submit" class="showBtn" id="showQR" style="margin-bottom: 0;">Pokaż kod QR</button>
            <?php  if($result[0]->Szerokosc_geograficzna != 0 && $result[0]->Wysokosc_geograficzna != 0):?>
                <button type="button" id="getLocalizationButton" class="showBtn" type="submit">Pokaż lokalizację</button>
            <?php endif?>
        </form>
            <div id="getLocalization">
                <div id="mapDiv" style="display: none;">
                    <div id="map">
                </div>    
                <button id="closeMap">X</button>
            </div>
        </div>
        
        
        <div id="underSection">
            <div id="the-other-under-section">    
                <p id='para'><b>Spoczywa na <span id="location"><?php esc_html_e($result[0]->Geolokalizacja) ?>, Numer grobu: <?php esc_html_e($result[0]->Numer_grobu) ?></span></b></p>
                <div id="imgGalleryDiv">
                <section id="imgGallery">
                <?php
                    $table_name = $wpdb->prefix.'zdjecia';
                    $result2 = $wpdb->get_results(
                        $wpdb->prepare("SELECT * FROM $table_name WHERE ID_Zmarlego = %d",$_GET['id'] )
                    );
                ?>
                <?foreach($result2 as $photo):?>
                     <img class='gallery' width="200px" height="100px" src='data:image/jpeg;base64,<?php echo base64_encode($photo->Zdjecie)?>'>
                <?php endforeach?>
                </section>
                </div>
            </div>
            <div id='data-viewer-comments'>
                <h3>Kondolencje</h3>
                <?php if(is_user_logged_in()):?>
                <form id="create-comment">
                    <?php wp_nonce_field('wp_rest', '_wpnonce')?>
                    <input type="hidden" name="id" value="<?php echo $_GET["id"]?>">
                    <p id="paraComments"><input type='text' id='comment' name="comment" placeholder='Byles/as dla mnie...'/><button  type='submit' >Opublikuj</button></p>
                </form>
                <?php endif?>
                <?php 
                    $table_name = $wpdb->prefix.'komentarze';
                    $join_table_name = $wpdb->prefix.'users';
                    $result1 = $wpdb->get_results(
                        $wpdb->prepare("SELECT $table_name.*,$join_table_name.display_name FROM $table_name JOIN $join_table_name ON $join_table_name.ID = $table_name.ID_Klienta WHERE ID_Zmarlego = %d",$_GET['id'] )
                    );
                ?>  
                <?php if($result1):?>
                    <?php foreach($result1 as $row):?>
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
                                <div id="commentBtns">
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
</div>
<?php endif; ?>

<?php if(!$is_user): ?>

    <div id="container">
        <div id="notFound">
            <img src="data:image/png;base64,<?php print(base64_encode(file_get_contents(MY_PLUGIN_PATH."assets\images\\404face.png")))?>">
            <h3 style="color:#586C51">Brak takiego użytkownika</h3>
        </div>
    </div>
<?php endif; ?>


<?php if($is_user && $owner == $user_id && $result[0]->Is_payed == 1): ?>
 
<div id="centerBtn" style="display: flex;justify-content:center;flex-grow:0;">

    <form id="update-person">
        <?php wp_nonce_field('wp_rest', '_wpnonce') ?>
        <input type="hidden" name="id" id="id-update">
        <button id="update" type="submit">Zmień dane</button>
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
        <button id="delBtn" type="submit">Usuń</button>
        </div>
    </form>

</div>
<?php endif; ?>

<div id="QRCodeDiv" style="display: none;">
    <img id="qrCode">
    <button id="closeQR">X</button>
</div>


<div id="getLocalization">
    <div id="map"></div>
    <button id="getLocalizationButton" type="submit">lokalizacja</button>
</div>


<script><?php require_once(MY_PLUGIN_PATH."assets/js/popup.js") ?></script>

<?php if($result[0]->Is_payed == 0 && $result[0]->ID_Klienta == $user_id): ?>
    <button type="button" id="buyAgain" onclick="window.location.href='<?= home_url('/payment-form')?>?id=<?= $dead_person_id ?>'">Wykup ponownie</button>
<?php endif ?>


<script>

    jQuery(document).ready(function($){

        $('#getLocalizationButton').on('click', function(e) {
        document.querySelector("#mapDiv").style="display:block;";
        var lat = <?= $result[0]->Szerokosc_geograficzna; ?>;
        var lng = <?= $result[0]->Wysokosc_geograficzna; ?>;
      
        initMap(lat,lng);

        function initMap(lat, lng) {
        var location = { lat: lat, lng: lng };
        
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: location
        });
        new google.maps.Marker({ position: location, map: map, title: "Tutaj jesteś" });
        }

    });

        $('#getQrCode').submit(function(event){
            $("#showQR").attr("disabled",true);
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
                        $("#qrCode").attr("src","data:image/png;base64," +response.data);
                        $("#QRCodeDiv").css("display","flex");
                        $("#container").css("filter","blur(100px)");
                        $("#centerBtn").css("filter","blur(100px)");
                    }
                }
            })
            $("#showQR").attr("disabled",false);

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
                },error:function(response){
                    let error = response.responseJSON;
                    if(error['code'] != "invalid_nonce") show_popup(error['message']);
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
                },error:function(response){
                    let error = response.responseJSON;
                    if(error['code'] != "invalid_nonce") show_popup(error['message']);
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
                },error:function(response){
                    let error = response.responseJSON;
                    if(error['code'] != "invalid_nonce") show_popup(error['message']);
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
            $("#delBtn").attr("disabled",true);
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
                },error:function(response){
                    let error = response.responseJSON;
                    if(error['code'] != "invalid_nonce") show_popup(error['message']);
                }
            })
        })

        $('#update-person').submit(function(event){
            $("#update").attr("disabled",true);
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
                },error:function(response){
                    let error = response.responseJSON;
                    if(error['code'] != "invalid_nonce") show_popup(error['message']);
                }

            })
        })

    
        $("#delProfile").click(function(){
            $("#delete-person").attr('hidden',false);
        })

        $("#cancel").click(function(){
            $("#delete-person").attr('hidden',true);
        })

        $("#closeQR").click(function(){
            $("#QRCodeDiv").css("display","none");
            $("#container").css("filter","blur(0px)");
            $("#centerBtn").css("filter","blur(0px)");
            $("body").css("filter","blur(0px)")
        })

        $("#closeMap").click(function(){
            $("#mapDiv").css("display","none");
        });

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


    
</script>