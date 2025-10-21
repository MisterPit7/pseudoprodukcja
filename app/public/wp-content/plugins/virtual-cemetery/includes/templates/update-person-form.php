<script>
    window.addEventListener('load',function(){
       let loader = document.querySelector("#loader");
       let main = document.querySelector("#mainContent");
       loader.style.display="none";
       main.style.display="block";
    })

</script>
<style>
    <?php include MY_PLUGIN_PATH."assets/css/update-dead-person-form.css" ?>
</style>
<div id="loader"><?php include MY_PLUGIN_PATH."includes/templates/loader.php"?></div>

<div id="mainContent" style="display: none;">
<div class="update-dead-person">
<form enctype="multipart/form-data" id="update-dead-person-form">
    <h1>Modyfikacja profilu</h1>    
    <?php echo wp_nonce_field('wp_rest', '_wpnonce')?>
    <div id="container">
    <section>
            <div id="imageDiv">
                <img src="data:image/png;base64,<?php print(base64_encode(file_get_contents(MY_PLUGIN_PATH."assets\images\\no-image.jpg"))); ?>" id="image" width="200px">
            </div>
            <label for="photo">Zdjecie profilowe</label><br>
            <input type="file" name="photo" id="imageFile"><br>

            <label for="name">Imię:</label><br>
            <input type="text" name="name"><br>

            <label for="surname">Nazwisko:</label><br>
            <input type="text" name="surname"><br>

        </section>
        <section>
            <label for="birth-date">Data narodzin:</label><br>
            <input type="date" name="birth-date" max="<?= date('Y-m-d'); ?>"><br>

            <label for="death-date">Data zgonu:</label><br>
            <input type="date" name="death-date" max="<?= date('Y-m-d'); ?>"><br>

            <label for="description">Opis:</label><br>
            <textarea name="description" rows="8" autocapitalize="sentences" style="resize:none"></textarea> <br>

            <div id="daneGrobu">
                <section class="grob">
                    <label for="localization">Położenie grobu:</label><br>
                    <input type="text" name="localization"><br>
                </section>
                <section class="grob">
                    <label for="graveId">Numer grobu:</label><br>
                    <input type="text" name="graveID"><br>
                </section>
            </div>

            <input type="hidden" name="id" id="id">

        </section>
    </div>
    <div style="display: flex;justify-content:center;">
        <button id="add" type="submit">Zmień</button>
    </div>
</form>
</div>
<div class="update-dead-person" style="margin-top:30px;">
<form id="add-photo-form" enctype="multipart/form-data">
    <h2>Dodaj zdjęcie do galerii</h2>
    <?php echo wp_nonce_field('wp_rest', '_wpnonce')?>
    <input type="file" name="photoGallery" id="photoInput" required>
    <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']?>">
    <div style="display: flex;justify-content:center"><button type="submit">Dodaj</button></div>
</form>
 <section id="photoGallery">
        <?php
        global $wpdb;
            $table_name = $wpdb->prefix.'zdjecia';
            $result = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $table_name WHERE ID_Zmarlego = %d",$_GET['id'] )
            );
        ?>
        <?php foreach($result as $photo):?>
            <form class="delete-photo" style="margin-bottom: 0px">
                <?php wp_nonce_field('wp_rest', '_wpnonce') ?>
                <img class='gallery' width="200px" height="100px" src='data:image/jpeg;base64,<?php echo base64_encode($photo->Zdjecie)?>'/>
                <input type="hidden" name="photo-id" value="<?php echo $photo->ID?>"/>
                <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']?>">
                <div id="right"><button type="submit" style="font-weight: 600;" >Usuń zdjęcie</button></div>
            </form>
        <?php endforeach?>
    </section>
</div>
<div id="buttonDiv">
 <button id="back" onclick="window.location.href='<?php echo home_url('/single-person?id=').$_GET['id']; ?>';this.disabled=true">Powrót</button>
</div>
</div>
<script><?php require_once(MY_PLUGIN_PATH."assets/js/popup.js") ?></script>
<script>
    jQuery(document).ready(function(){

        const url = new URL(window.location.href);
        const parts = url.toString().split('id=')[1].split('&')[0];         
        const id = parts;
        if(id === null) return;

        $('#id').attr('value', id);

        $("#add-photo-form").submit(function(event){
            event.preventDefault()
            
            
            var formData = new FormData(this);
            formData.append('_wpnonce', jQuery(this).find('input[name="_wpnonce"]').val());
            formData.append('id', id);
            formData.append('photo', jQuery(this).find('input[name="photoGallery"]')[0].files[0]);

            jQuery.ajax({
                type:'POST',
                url: "<?php echo get_rest_url( null, 'v1/update-photos' ) ?>",
                data: formData,
                processData: false,
                contentType: false,
                success:function(response){
                    if(response.data){
                        window.location.href = response.data;
                    }
                },error:function(response){
                    let error = response.responseJSON;
                    if(error['code'] != "invalid_nonce") show_popup(error['message']);
                }
            })

            

        })

        $(".delete-photo").submit(function(event){
            event.preventDefault();
            var form = $(this);
            var formData = new FormData(this);
            formData.append('_wpnonce', form.find('input[name="_wpnonce"]').val());
            formData.append('photo-id', form.find('input[name="photo-id"]').val());
            formData.append('id', form.find('input[name="id"]').val());

            $.ajax({
                type:'POST',
                url: "<?php echo get_rest_url( null, 'v1/delete-person-photo' ) ?>",
                data: formData,
                processData: false,
                contentType: false,
                success:function(response){
                    if(response.data){
                        window.location.href = response.data;
                    }
                },error:function(response){
                    let error = response.responseJSON;
                    if(error['code'] != "invalid_nonce") show_popup(error['message']);
                }
            })
        })

        $.ajax({
            type: 'GET',
            url: "<?php echo get_rest_url(null,'v1/get-single-person') ?>"+'?id='+id,
            success: function(response){
                if(response.data){
                    response = JSON.parse(response.data);

                    if(response.Profilowe){
                        let img = "data:image/png;base64,"+response.Profilowe;
                        $('#image').attr('src', img);
                    }

                    if(response.Imie){
                        $('input[name="name"]').val(response.Imie)
                    }

                    if(response.Nazwisko){
                        $('input[name="surname"]').val(response.Nazwisko)
                    }

                    if(response.Data_urodzenia){
                        $('input[name="birth-date"]').val(response.Data_urodzenia)
                    }

                    if(response.Data_smierci){
                        $('input[name="death-date"]').val(response.Data_smierci)
                    }

                    if(response.Opis){
                        $('textarea[name="description"]').val(response.Opis)
                    }

                    if(response.Geolokalizacja){
                        $('input[name="localization"]').val(response.Geolokalizacja)
                    }
                    if(response.Numer_grobu){
                        $('input[name="graveID"]').val(response.Numer_grobu)
                    }

                }
                    
            },error:function(response){
                if(response){
                    window.location.href = "<?= home_url('dashboard') ?>"
                }

            }
            
        })

        $("#update-dead-person-form").submit(function(event){
            event.preventDefault()
            
            
            var formData = new FormData(this);
            formData.append('_wpnonce', jQuery(this).find('input[name="_wpnonce"]').val());
            formData.append('id', jQuery(this).find('input[name="id"]').val());
            formData.append('photo', jQuery(this).find('input[name="photo"]')[0].files[0]);
            formData.append('name', jQuery(this).find('input[name="name"]').val());
            formData.append('surname', jQuery(this).find('input[name="surname"]').val());
            formData.append('birth-date', jQuery(this).find('input[name="birth-date"]').val());
            formData.append('death-date', jQuery(this).find('input[name="death-date"]').val());
            formData.append('description', jQuery(this).find('textarea[name="description"]').val());
            formData.append('localization', jQuery(this).find('input[name="localization"]').val());
            formData.append('graveID', jQuery(this).find('input[name="graveID"]').val());

            $.ajax({
                type:'POST',
                url: "<?php echo get_rest_url( null, 'v1/update-single-person' ) ?>",
                data: formData,
                processData: false,
                contentType: false,
                success:function(response){
                    if(response.data){
                        window.location.href = response.data;
                    }
                },error:function(response){
                    let error = response.responseJSON;
                    if(error['code'] != "invalid_nonce") show_popup(error['message']);
                }
            })

            

        })

        $("#imageFile").change(function(){
                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && ( ext == "png" || ext == "jpeg" || ext == "jpg" )) 
                {   
                    var reader = new FileReader();

                    reader.onload = function (e) {
                    $('#image').attr('src', e.target.result);
                    }
                reader.readAsDataURL(input.files[0]);
                }
                else
                {
                let img = "data:image/png;base64,<?php print(base64_encode(file_get_contents(MY_PLUGIN_PATH."assets\images\\no-image.jpg"))); ?>"
                $('#image').attr('src', img);
                }
            });

    })
</script>