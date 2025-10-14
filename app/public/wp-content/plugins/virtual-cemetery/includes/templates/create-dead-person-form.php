<form id="create-dead-person-form" enctype="multipart/form-data">
    <?php echo wp_nonce_field('wp_rest', '_wpnonce')?>

    <label for="photo">Zdjecie</label><br>
    <input type="file" name="photo"><br>

    <label for="name">Imię:</label><br>
    <input type="text" name="name"><br>

    <label for="surname">Nazwisko:</label><br>
    <input type="text" name="surname"><br>
    
    <label for="birth-date">Data narodzin:</label><br>
    <input type="date" name="birth-date"><br>

    <label for="death-date">Data zgonu:</label><br>
    <input type="date" name="death-date"><br>

    <label for="description">Opis:</label><br>
    <textarea name="description"></textarea> <br>

    <label for="localization">Położenie grobu:</label><br>
    <input type="text" name="localization"><br>


    <button type="submit">DODAJ</button>

</form>
 <button onclick="window.location.href='<?php echo esc_url( home_url( '/dashboard/' ) ); ?>'">Powrót</button>

<script>

    jQuery(document).ready(function($){
    $("#create-dead-person-form").submit(function(event){
        event.preventDefault();

        var formData = new FormData(this);
        console.log(formData);

        $.ajax({
            type: "POST",
            url: "<?php echo get_rest_url(null, 'v1/create-dead-person') ?>",
            data: formData,
            processData: false,
            contentType: false,
           
        });
    });
});


</script>