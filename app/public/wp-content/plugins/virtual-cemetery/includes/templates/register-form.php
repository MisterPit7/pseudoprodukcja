<style>
    <?php include MY_PLUGIN_PATH."assets/css/register-form.css"; ?>
</style>

<form id="register-form">
    <h1>Zarejestruj się</h1>
    <?php wp_nonce_field('wp_rest', '_wpnonce') ?>

    <label for="nickname">Nickname: </label><br>
    <input type="text" name="nickname" required placeholder="Wpisz swój pseudonim"><br>
    <span id="info3"></span>
    <br>
   
    <label for="email">Email: </label><br>
    <input type="email" name="email" required placeholder="Wpisz swój email"><br>
    <br>

    <label for="password">Hasło: </label><br>
    <input type="password" name="password" required placeholder="Wpisz swoje hasło"><br>
    <span id="info4"></span>
    <br>

    <div style="display:flex;justify-content:center">
        <button type="submit">Zarejestruj się</button>
    </div>
</form>

<script>
    jQuery(document).ready(function($){
        $("#register-form").submit(function(event){
            event.preventDefault();
           
            var form = $(this);  
            
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/;
            const regex2 = /^[a-zA-Z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ]{5,50}$/;

            const password = form.find('input[name="password"]').val();
            const nickname = form.find('input[name="nickname"]').val();
            
            const info1 = document.querySelector("#info3");
            const info2 = document.querySelector("#info4");

            if (!regex2.test(nickname)) {
                info1.innerHTML = "Nazwa musi się składać z tylko liter i cyfr oraz musi mieć conajmnie 5 znaków.";
                return; 
            }else{
                info1.innerHTML = "";
            }

            if (!regex.test(password)) {
                info2.innerHTML="Hasło musi się składać z conajmniej 8 znaków w tym: mała litera, duża litera, cyfra, znaku specjalnego.";
                return; 
            }else{
                info2.innerHTML="";
            }

            $.ajax({
                type: "POST",
                url: "<?php echo get_rest_url(null, 'v1/register') ?>",
                data: form.serialize()
            })

        })
    })
</script>