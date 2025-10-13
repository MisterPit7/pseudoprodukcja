<style>
    <?php include MY_PLUGIN_PATH."assets/css/register-form.css"; ?>
</style>

<form id="register-form">
    <h1>Zarejestruj się</h1>
    <?php wp_nonce_field('wp_rest', '_wpnonce') ?>

    <label for="name">Imie: </label><br>
    <input type="text" name="name" placeholder="Wpisz swoje Imię"><br><br>

    <label for="surname">Nazwisko: </label><br>
    <input type="text" name="surname" placeholder="Wpisz swoje Nazwisko"><br><br>

    <label for="email">Email: </label><br>
    <input type="email" name="email" placeholder="Wpisz swój Email" ><br><br>

    <label for="password">Hasło: </label><br>
    <input type="password" name="password" placeholder="Wpisz swoje Hasło"><br><br>

    <div style="display:flex;justify-content:center">
        <button type="submit">Zarejestruj się</button>
    </div>
</form>

<script>
    jQuery(document).ready(function($){
        $("#register-form").submit(function(event){
            event.preventDefault();
           
            var form = $(this);            

            $.ajax({
                type: "POST",
                url: "<?php echo get_rest_url(null, 'v1/register') ?>",
                data: form.serialize(),
                processData: false,
                contetType: false,

            })

        })
    })
</script>