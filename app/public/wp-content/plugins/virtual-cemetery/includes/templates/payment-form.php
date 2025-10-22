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
    <select id="country" name="country">
        <option value="PL">Polska</option>
        <option value="DE">Niemcy</option>
        <option value="FR">Francja</option>
        <option value="ES">Hiszpania</option>
        <option value="IT">Włochy</option>
        <option value="GB">Wielka Brytania</option>
        <option value="US">Stany Zjednoczone</option>
        <option value="CA">Kanada</option>
        <option value="AU">Australia</option>
        <option value="NZ">Nowa Zelandia</option>
        <option value="RU">Rosja</option>
        <option value="UA">Ukraina</option>
        <option value="BY">Białoruś</option>
        <option value="CZ">Czechy</option>
        <option value="SK">Słowacja</option>
        <option value="HU">Węgry</option>
        <option value="RO">Rumunia</option>
        <option value="BG">Bułgaria</option>
        <option value="HR">Chorwacja</option>
        <option value="SI">Słowenia</option>
        <option value="RS">Serbia</option>
        <option value="ME">Czarnogóra</option>
        <option value="BA">Bośnia i Hercegowina</option>
        <option value="MK">Macedonia Północna</option>
        <option value="GR">Grecja</option>
        <option value="PT">Portugalia</option>
        <option value="IE">Irlandia</option>
        <option value="NL">Holandia</option>
        <option value="BE">Belgia</option>
        <option value="LU">Luksemburg</option>
        <option value="CH">Szwajcaria</option>
        <option value="AT">Austria</option>
        <option value="SE">Szwecja</option>
        <option value="NO">Norwegia</option>
        <option value="FI">Finlandia</option>
        <option value="DK">Dania</option>
        <option value="IS">Islandia</option>
        <option value="EE">Estonia</option>
        <option value="LV">Łotwa</option>
        <option value="LT">Litwa</option>
        <option value="TR">Turcja</option>
        <option value="CY">Cypr</option>
        <option value="EG">Egipt</option>
        <option value="ZA">Republika Południowej Afryki</option>
        <option value="MA">Maroko</option>
        <option value="TN">Tunezja</option>
        <option value="DZ">Algieria</option>
        <option value="NG">Nigeria</option>
        <option value="KE">Kenia</option>
        <option value="ET">Etiopia</option>
        <option value="CN">Chiny</option>
        <option value="JP">Japonia</option>
        <option value="KR">Korea Południowa</option>
        <option value="VN">Wietnam</option>
        <option value="TH">Tajlandia</option>
        <option value="MY">Malezja</option>
        <option value="SG">Singapur</option>
        <option value="ID">Indonezja</option>
        <option value="PH">Filipiny</option>
        <option value="IN">Indie</option>
        <option value="PK">Pakistan</option>
        <option value="IR">Iran</option>
        <option value="IQ">Irak</option>
        <option value="SA">Arabia Saudyjska</option>
        <option value="AE">Zjednoczone Emiraty Arabskie</option>
        <option value="QA">Katar</option>
        <option value="KW">Kuwejt</option>
        <option value="OM">Oman</option>
        <option value="JO">Jordania</option>
        <option value="LB">Liban</option>
        <option value="SY">Syria</option>
        <option value="AF">Afganistan</option>
        <option value="NP">Nepal</option>
        <option value="BD">Bangladesz</option>
        <option value="LK">Sri Lanka</option>
        <option value="MM">Mjanma (Birma)</option>
        <option value="MN">Mongolia</option>
        <option value="KZ">Kazachstan</option>
        <option value="UZ">Uzbekistan</option>
        <option value="GE">Gruzja</option>
        <option value="AM">Armenia</option>
        <option value="AZ">Azerbejdżan</option>
        <option value="BR">Brazylia</option>
        <option value="AR">Argentyna</option>
        <option value="CL">Chile</option>
        <option value="PE">Peru</option>
        <option value="CO">Kolumbia</option>
        <option value="VE">Wenezuela</option>
        <option value="EC">Ekwador</option>
        <option value="UY">Urugwaj</option>
        <option value="PY">Paragwaj</option>
        <option value="BO">Boliwia</option>
        <option value="MX">Meksyk</option>
        <option value="CU">Kuba</option>
        <option value="DO">Dominikana</option>
        <option value="JM">Jamajka</option>
        <option value="CR">Kostaryka</option>
        <option value="PA">Panama</option>
        <option value="GT">Gwatemala</option>
        <option value="HN">Honduras</option>
        <option value="SV">Salwador</option>
        <option value="NI">Nikaragua</option>
        <option value="BS">Bahamy</option>
        <option value="TT">Trynidad i Tobago</option>
        <option value="HT">Haiti</option>
        <option value="AU">Australia</option>
        <option value="NZ">Nowa Zelandia</option>
        <option value="PG">Papua-Nowa Gwinea</option>
        <option value="FJ">Fidżi</option>
        <option value="WS">Samoa</option>
        <option value="TO">Tonga</option>
        <option value="VU">Vanuatu</option>
        <option value="SB">Wyspy Salomona</option>
        <option value="NR">Nauru</option>
        <option value="TV">Tuvalu</option>
        <option value="KI">Kiribati</option>
        <option value="FM">Mikronezja</option>
        <option value="MH">Wyspy Marshalla</option>
    </select>

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