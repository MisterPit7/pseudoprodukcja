<form id="login-form">
    <?php wp_nonce_field('wp_rest', '_wpnonce') ?>
   
    <label for="email">Email: </label>
    <input type="email" name="email"><br><br>

    <label for="password">Hasło: </label>
    <input type="password" name="password"><br><br>

    <button type="submit">Zarejestruj się</button>

</form>

<script>
    jQuery(document).ready(function($){
        $("#login-form").submit(function(event){
            event.preventDefault();
           
            var form = $(this);  
            

            $.ajax({
                type: "POST",
                url: "<?php echo get_rest_url(null, 'v1/login') ?>",
                data: form.serialize(),
                processData: false,
                contetType: false,

            })

        })
    })
</script>