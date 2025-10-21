<form id="payment_form">

    <label for="name">Imie:</label>
    <input type="text" name="name"><br>

    <label for="surname">Nazwisko:</label>
    <input type="text" name="surname"><br>

    <label for="email">Email:</label>
    <input type="email" name="email"><br>

    <label for="phone">Numer telefonu:</label>
    <input type="tel" name="phone"><br>

    <label for="address_1">Adres:</label>
    <input type="text" name="address_1"><br>

    <label for="city">Miejscowość:</label>
    <input type="text" name="city"><br>

    <label for="post_code">Kod pocztowy:</label>
    <input type="text" name="post_code"><br>

    <label for="country">Kraj:</label>
    <input type="text" name="country"><br>

    <button type="submit">Prześlij</button>

</form>


<script>

    jQuery(document).ready(function($){
        $('#payment_form').submit(function(event){

            event.preventDefault()
            var form = $(this)
            

            $.ajax({
                type: 'POST',
                url: "<?php echo get_rest_url(null, 'v1/payment-form') ?>",
                data: form.serialize()
            })

        })
    })

</script>