<style>
    <?php include MY_PLUGIN_PATH."assets/css/single-person.css"?>
</style>
<div id='container'>
        <h1 id='header'>Ś.P. <span id="name"></span> <span id="surname"></span></h1>
        <img id='profilePic'>
        <hr>
        <h2>
            <div id='info'><span id="dateU"></span></div> <div style='padding-top:8px;font-size:3rem'>-</div> <div id='info'><span id="dateS"></span></div>
        </h2>
        <p id='para'><i>"<span id="description"></span>"</i></p>
        <p id='para'><b>Spoczywa na <span id="location"></span></b></p>
        <div id='data-viewer-comments'>
            <h3>Komentarze</h3>
            <label for='comment'>Wpisz komentarz</label>
            <p style='width:100%;text-align:center'><input type='text' id='comment' placeholder='Byles/as dla mnie...'/></p>
        
           <div id='comment'><h4>Osoba 1</h4>tresc komentarza 1</span></div>
           <div id='comment'><h4>Osoba 2</h4>tresc komentarza 2</span></div>
        </div>
</div>
<div id="centerBtn" style="display: flex;justify-content:center;flex-grow:0;">

    <form id="update-person">
        <?php wp_nonce_field('wp_rest', '_wpnonce') ?>
        <input type="hidden" name="id" id="id-update">
        <button type="submit">Zmień dane</button>
    </form>

    <button type="button" id="delProfile">Usuń profil</button>
</div>

    <form id="delete-person" hidden="true">
        <?php wp_nonce_field('wp_rest', '_wpnonce') ?>
        <input type="hidden" name="id" id="id-delete">
        <input type="text" name="text" placeholder="Wpisz nazwisko z profilu, aby potwierdzić"><br>
        <div id="delBtns">
            <button type="button" id="cancel">Anuluj</button>
        <button type="submit">Usuń</button>
        </div>
    </form>


<script>

    jQuery(document).ready(function($){
        
        const url = new URL(window.location.href);
        const parts = url.toString().split('id=')[1].split('&')[0];         
        const id = parts;
        if(id === null) return;
        $('#id-delete').val(id);
        $('#id-update').val(id);

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
                if(response.Data_urodzenia){
                    $('#dateU').append(convertDate(response.Data_urodzenia))

                }
                else{
                    $('#dateU').append('Nie udało się pobrać daty urodzenia')
                }
                if(response.Data_smierci){
                    $('#dateS').append(convertDate(response.Data_smierci))
                }
                else{
                    $('#dateS').append('Nie udało się pobrać daty śmierci')
                }
                //Profilowe
                if(response.Profilowe){
                    $('#profilePic').attr('src','data:image/jpeg;base64,'+response.Profilowe)
                }else{
                    $('#profilePic').attr('src','https://i.pinimg.com/736x/1d/ec/e2/1dece2c8357bdd7cee3b15036344faf5.jpg');
                }

            }
        })

        $('#delete-person').submit(function(event){
            event.preventDefault();
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

        $('#update-person').submit(function(event){
            event.preventDefault()

            form = $(this)
            
            $.ajax({
                type:'POST',
                url: "<?php echo get_rest_url(null,'v1/redirect-update') ?>",
                data:form.serialize(),
                success:function(response){
                    if(response.data){
                        window.location.href = response.data + '?id=' + id;
                    }
                }

            })
        })

    
        $("#delProfile").click(function(){
            $("#delete-person").attr('hidden',false);
        })

        $("#cancel").click(function(){
            $("#delete-person").attr('hidden',true);
        })

    })
    function convertDate(date){
        let arr = date.split('-');
        return arr[2] +"-"+arr[1]+"-"+arr[0];
    }

    
</script>
