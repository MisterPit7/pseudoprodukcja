<style>
    <?php include MY_PLUGIN_PATH."assets/css/login-form.css"; ?>
</style>

<form id="login-form">
    <h1>Zaloguj się</h1>
    <?php wp_nonce_field('wp_rest', '_wpnonce') ?>

    <label for="email">Email: </label><br>
    <input type="email" name="email" required placeholder="Podaj email"><br><br>

    <label for="password">Hasło: </label><br>
    <input type="password" name="password" required placeholder="Podaj hasło"><br><br>

    <div style="display:flex;justify-content:center">
        <button type="submit">Zaloguj się</button>
    </div>
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