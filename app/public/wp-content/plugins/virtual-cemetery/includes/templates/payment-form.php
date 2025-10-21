<style>
    <?php include MY_PLUGIN_PATH."assets/css/payment-form.css" ?>
</style>
<form id="payment-form">
    <h1>Adres rozliczeniowy</h1>
    <div id="inputDiv">
    <label for="name">Imie:</label><br>
    <input type="text" name="name" required><br>

    <label for="surname">Nazwisko:</label><br>
    <input type="text" name="surname" required><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" required><br>

    <label for="phone">Numer telefonu:</label><br>
    <input type="tel" name="phone" required><br>

    <label for="address_1">Adres:</label><br>
    <input type="text" name="address_1" required><br>

    <label for="city">Miejscowość:</label><br>
    <input type="text" name="city" required><br>

    <label for="post_code">Kod pocztowy:</label><br>
    <input type="text" name="post_code" required><br>

    <label for="country">Kraj:</label><br>
    <input type="text" name="country" required><br>

    <label for="country">Okres profilu:</label><br>
    <select name="howLong" id="howLong">
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="20">20</option>
    </select><br>

    <input type="hidden" value="<?= get_current_user_id() ?>" name="user_id">
    <?php if(isset($_GET['id'])):?>
    <input type="hidden" value="<?= $_GET['id'] ?>" name="dead_id">
    <?php endif ?>
    </div>
    <div id="buttonDiv">
        <button type="submit">Prześlij</button>
    </div>
</form>


<script>

    jQuery(document).ready(function($){
        $('#payment-form').submit(function(event){

            event.preventDefault()
            var form = $(this)
            

            $.ajax({
                type: 'POST',
                url: "<?php echo get_rest_url(null, 'v1/make-order') ?>",
                data: form.serialize(),
                success:function(response){
                    if(response.data){
                        window.location.href=response.data;
                    }
                }
            })

        })
    })

</script>