<form id="register-form">
    <?php wp_nonce_field('wp_rest', '_wpnonce') ?>

    <label for="nickname">Nickname: </label>
    <input type="text" name="nickname" required><br><br>
   
    <label for="email">Email: </label>
    <input type="email" name="email" required><br><br>

    <label for="password">Hasło: </label>
    <input type="password" name="password" required><br><br>

    <button type="submit">Zarejestruj się</button>

</form>

<script>
    jQuery(document).ready(function($){
        $("#register-form").submit(function(event){
            event.preventDefault();
           
            var form = $(this);  
            
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/;
            const regex2 = /^[a-zA-Z0-9]{5,50}$/;

            const password = form.find('input[name="password"]').val();
            const nickname = form.find('input[name="nickname"]').val();
            
            if (!regex2.test(nickname)) {
                alert("zly nickname");
                return; 
            }

            if (!regex.test(password)) {
                alert("Zbyt słabe hasło");
                return; 
            }    

            $.ajax({
                type: "POST",
                url: "<?php echo get_rest_url(null, 'v1/register') ?>",
                data: form.serialize()
            })

        })
    })
</script>