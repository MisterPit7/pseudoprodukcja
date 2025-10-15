<img src="" alt="Profilowe" id="profile">
<span id="name"></span>
<span id="surname"></span>
<span id="date"></span>
<span id="description"></span>
<span id="location"></span>
<script>

    jQuery(document).ready(function($){
        const url = new URL(window.location.href);
        const parts = url.toString().split('?');         
        const id = parts[parts.length - 1];
        if(id === null) return;
        $.ajax({
            type:'GET',
            url:"<?php echo get_rest_url(null, 'v1/get-single-person')?>?id=9",
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
                //Geolokalizacjia
                if(response.Geolokalizacjia){
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
    })

</script>