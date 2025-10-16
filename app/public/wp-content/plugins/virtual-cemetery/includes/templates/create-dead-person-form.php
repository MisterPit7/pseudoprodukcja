<style>
    <?php include MY_PLUGIN_PATH."assets/css/create-dead-person-form.css" ?>
</style>

<form id="create-dead-person-form" enctype="multipart/form-data">
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
        </section>
    </div>
    <div style="display: flex;justify-content:center;">
        <button id="add" type="submit">DODAJ</button>
    </div>
</form>
<div id="buttonDiv">
 <button id="back" onclick="window.location.href='<?php echo esc_url( home_url( '/dashboard/' ) ); ?>'">Powrót</button>
</div>
<script>

    jQuery(document).ready(function($){
    $("#create-dead-person-form").submit(function(event){
        event.preventDefault();
        $('#add').attr('disabled',true);

        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "<?php echo get_rest_url(null, 'v1/create-dead-person') ?>",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.data){
                    window.location.href = response.data;
                }
            },
            error: function(){
                alert('złe dane');
                $('#add').attr('disabled',false);
            }
           
        });
    });

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
});


</script>