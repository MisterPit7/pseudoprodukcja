<script>
    window.addEventListener('load',function(){
       let loader = document.querySelector("#loader");
       let main = document.querySelector("#mainContent");
       loader.style.display="none";
       main.style.display="block";
    })
</script>
<style>
    <?php include MY_PLUGIN_PATH."assets/css/create-dead-person-form.css" ?>
</style>

<div id="loader"><?php include MY_PLUGIN_PATH."includes/templates/loader.php"?></div>

<div id="mainContent" style="display: none;">
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
    <section id="sectionGall">
        <label for="galery-photo">Zdjęcia do galeri</label>
        <input type="file" name="gallery-photo">
        <div id="right">
        <button type="button" id="add-photo" value="XD">Dodaj zdjęcie</button>
        </div>
        <div id="gallery"></div>
    </section>
    <div style="display: flex;justify-content:center;">
        <button id="add" type="submit">Dodaj</button>
    </div>
</form>
<div id="buttonDiv">
 <button id="back" onclick="window.location.href='<?php echo esc_url( home_url( '/dashboard/' ) ); ?>'">Powrót</button>
</div>
</div>
<script>

    jQuery(document).ready(function($){

    var formData = new FormData(document.querySelector("#create-dead-person-form"));
    
    function delete_image(){
        console.log('click');
        let val = $(this).val();
        formData.delete('gallery-photo[]', formData.getAll('gallery-photo[]')[val]);
        $(this).parent().parent().remove();
        document.querySelectorAll('.remove').forEach((btn, index) => {
            btn.value = index;
        });
    };

    $("#add-photo").click(function(){
        let allowedExtensions = ['jpg', 'jpeg', 'png'];
        var photoFile = $('input[name="gallery-photo"]')[0].files[0];
        if(photoFile.length === 0) return;
        if(allowedExtensions.includes(photoFile.name.split('.').pop().toLowerCase())){
            
            formData.append('gallery-photo[]', photoFile);
            var sec = document.createElement('section');
            sec.id="secImg";
            var img = document.createElement('img');
            img.src = URL.createObjectURL(photoFile);
            sec.appendChild(img);
            var div = document.createElement('div');
            var button = document.createElement('button');
            div.id = "right";
            button.type="button";
            button.value=formData.getAll('gallery-photo[]').length - 1
            button.innerText="Usuń zdjęcie";
            div.appendChild(button);
            button.addEventListener('click', delete_image);
            button.className = "remove";
            sec.appendChild(div);
            document.querySelector("#gallery").append(sec);
        }
        document.querySelector('input[name="gallery-photo"').value=null;
    });


    $("#create-dead-person-form").submit(function(event){
        event.preventDefault();
        $('#add').attr('disabled',true);

        formData.set('_wpnonce', $('input[name="_wpnonce"]').val());
        formData.set('name', $('input[name="name"]').val());
        formData.set('surname', $('input[name="surname"]').val());
        formData.set('birth-date', $('input[name="birth-date"]').val());
        formData.set('death-date', $('input[name="death-date"]').val());
        formData.set('description', $('textarea[name="description"]').val());
        formData.set('localization', $('input[name="localization"]').val());
        formData.set('photo', $('input[name="photo"]')[0].files[0]);

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