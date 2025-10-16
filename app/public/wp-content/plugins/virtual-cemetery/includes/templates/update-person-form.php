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
<form id="update-dead-person-form" enctype="multipart/form-data">
    <h1>Tworzenie profilu</h1>    
    <?php echo wp_nonce_field('wp_rest', '_wpnonce')?>
    <div id="container">
    <section>
            <div id="imageDiv">
                <img src="data:image/png;base64,<?php print(base64_encode(file_get_contents(MY_PLUGIN_PATH."assets\images\\no-image.jpg"))); ?>" id="image" width="200px">
            </div>
            <label for="photo">Zdjecie</label><br>
            <input type="file" name="photo" id="imageFile"><br>

            <label for="name">Imię:</label><br>
            <input type="text" name="name"><br>

            <label for="surname">Nazwisko:</label><br>
            <input type="text" name="surname"><br>

        </section>
        <section>
            <label for="birth-date">Data narodzin:</label><br>
            <input type="date" name="birth-date"><br>

            <label for="death-date">Data zgonu:</label><br>
            <input type="date" name="death-date"><br>

            <label for="description">Opis:</label><br>
            <textarea name="description" rows="8" autocapitalize="sentences" style="resize:none"></textarea> <br>

            <label for="localization">Położenie grobu:</label><br>
            <input type="text" name="localization"><br>

            <input type="hidden" name="id" id="id">

        </section>
    </div>
    <div style="display: flex;justify-content:center;">
        <button id="add" type="submit">Zmień</button>
    </div>
</form>
<div id="buttonDiv">
 <button id="back" onclick="window.location.href='<?php echo esc_url( home_url( '/dashboard/' ) ); ?>'">Powrót</button>
</div>
</div>
<script>
    jQuery(document).ready(function(){

        const url = new URL(window.location.href);
        const parts = url.toString().split('id=')[1].split('&')[0];         
        const id = parts;
        if(id === null) return;

        $('#id').attr('value', id);

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

                }
                    
            }
        })

        $("#update-dead-person-form").submit(function(event){
            event.preventDefault()
            
            
            var formData = new FormData(this);

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
                },
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