<form id="login-form">
    <?php wp_nonce_field('wp_rest', '_wpnonce') ?>
   
    <label for="email">Email: </label>
    <input type="email" name="email" required><br><br>

    <label for="password">Hasło: </label>
    <input type="password" name="password" required><br><br>

    <button type="submit">Zaluguj się</button>

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
                success: function(response){
                    if (response.success && response.redirect) {
                        window.location.href = response.redirect;
                    }
                }

            })

        })
    })
</script>