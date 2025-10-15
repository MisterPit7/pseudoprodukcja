<img src="" alt="Profilowe" id="profile">
<span id="name"></span>
<span id="surname"></span>
<span id="date"></span>
<span id="description"></span>
<span id="location"></span>


<form id="delete-person">
    <?php wp_nonce_field('wp_rest', '_wpnonce') ?>
    <input type="hidden" name="id" id="id">
    <input type="text" name="text">
    <button type="submit">usun</button>
</form>


<script>

    jQuery(document).ready(function($){
        const url = new URL(window.location.href);
        const parts = url.toString().split('id=')[1].split('&')[0];         
        const id = parts;
        if(id === null) return;
        $('#id').val(id);

        $.ajax({
            type:'GET',
            url:"<?php echo get_rest_url(null, 'v1/get-single-person')?>?id="+id,
            dataType: 'json',
            success: function(response){
                //Imie
                response= JSON.parse(response.data);
                if(response.Imie){
                    $('#name').append(response.Imie)
                }
                else{
                    $('#name').append('Nie udało się pobrać imienia')
                }
                //Nazwisko
                if(response.Nazwisko){
                    $('#surname').append(response.Nazwisko)
                }
                else{
                    $('#surname').append('Nie udało się pobrać nazwiska')
                }
                //Opis
                if(response.Opis){
                    $('#description').append(response.Opis)
                }
                else{
                    $('#description').append('Nie udało się pobrać opisu')
                }
                //Geolokalizacja
                if(response.Geolokalizacja){
                    $('#location').append(response.Geolokalizacja)
                }
                else{
                    $('#location').append('Nie udało się pobrać lokalizacji')
                }
                //Daty
                if(response.Data_urodzenia && response.Data_smierci){
                    $('#date').append(response.Data_urodzenia +' - '+response.Data_smierci)
                }
                else{
                    $('#date').append('Nie udało się pobrać dat')
                }
                //Profilowe
                if(response.Profilowe){
                    $('#profile').attr('src','data:image/jpeg;base64,'+response.Profilowe)
                }

            }
        })

        $('#delete-person').submit(function(){
            form = $(this)
            $.ajax({
                type: 'POST',
                url: "<?php echo get_rest_url(null, 'v1/delete-single-person')?>",
                data: form.serialize(),
                success: function(response){
                    if(response.data){
                        window.location.href = response.data
                    }
                }
            })
        })

    })

</script>
