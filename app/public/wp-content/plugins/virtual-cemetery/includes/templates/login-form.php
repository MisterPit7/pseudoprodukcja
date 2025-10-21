<style>
    <?php include MY_PLUGIN_PATH."assets/css/login-form.css"; ?>
</style>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<form id="login-form">

    <h1>Zaloguj się</h1>
    <?php wp_nonce_field('wp_rest', '_wpnonce') ?>

    <label for="email">Email: </label><br>
    <input type="email" name="email" required placeholder="Podaj email"><br>
    <span id="info1"></span>
    <br>
    
    <label for="password">Hasło: </label><br>
    <input type="password" name="password" required placeholder="Podaj hasło"><br>
    <span id="info2"></span>
    <br>
        <div class="g-recaptcha" data-sitekey="6Lc2v_ArAAAAAHfeltN6JiN8AT06Y_hw3MfRPAhC"></div>
      <br/>
    <div style="display:flex;justify-content:center">
        <button type="submit">Zaloguj się</button>
    </div>
</form>
<script><?php require_once(MY_PLUGIN_PATH."assets/js/popup.js") ?></script>
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
                },
                error: function(response){
                    let error = response.responseJSON;
                    let info1 = document.querySelector("#info1");
                    let info2 = document.querySelector("#info2");

                    if(error['message'] == "no account found"){
                        info1.innerHTML = "Błędny email!"; 
                    }else{
                        info1.innerHTML = ""; 
                    }
                    if(error['message'] == "incorrect paswrd"){
                        info2.innerHTML = "Błędne hasło!"; 
                    }else{                                                
                        info2.innerHTML = ""; 
                    }
                }

            })

        })
    })
</script>