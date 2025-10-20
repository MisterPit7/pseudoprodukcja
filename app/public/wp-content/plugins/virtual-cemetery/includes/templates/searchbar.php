<script>
    window.addEventListener('load',function(){
       let loader = document.querySelector("#loader");
       let main = document.querySelector("#mainContent");
       loader.style.display="none";
       main.style.display="block";
    })
</script>

<style>
    <?php include MY_PLUGIN_PATH."assets/css/searchbar.css" ?>
</style>
<div id="loader"><?php include MY_PLUGIN_PATH."includes/templates/loader.php"?></div>
<div id="mainContent" style="display:none">
    <div>
        <nav id="mein">
            <form id="searchForm">
                <input type="text" name="name" placeholder="Wyszukaj imie...">
                <input type="text" name="surname" placeholder="Wyszukaj nazwisko...">
                <button type="submit" id="btn"><span class="dashicons dashicons-search"></span></button>
            </form>
        </nav>
        <main id="mainGrid">
            <!-- TU BEDA DODAWANE OSOBY PO WYSZUKANIU -->
        </main>
    </div>
</div>
<script><?php require_once(MY_PLUGIN_PATH."assets/js/popup.js") ?></script>
<script>
    jQuery(document).ready(function($){
        $("#searchForm").submit(function(event){
            $("#btn").attr("disabled","dubidubiduba");
            event.preventDefault();
            
            var form = $(this);

            var main =  $('#mainGrid');
            main.empty();

            $.ajax({
                type: 'POST',
                url: '<?php echo get_rest_url(null,"v1/search-persons") ?>',
                data: form.serialize(),
                success: function (response){          
                    if(response.data){
                        response= JSON.parse(response.data);            
                        response.forEach((element)=>{  
                            
                            var main2 = document.getElementById('mainGrid');
                            
                            var div = document.createElement('div')
                            div.id = 'dead-person'

                            var img = document.createElement('img')
                            img.src = "data:image/png;base64,"+element.Profilowe

                            var p = document.createElement('p')
                            p.textContent = element.Imie + " " + element.Nazwisko

                            var div2 = document.createElement('div')
                            div2.style.display = "flex";
                            div2.style.justifyContent = "center";

                            var button = document.createElement('button');
                            button.textContent = "Pokaż osobę"

                            var id = document.createElement('input')
                            id.type = "hidden"
                            id.value = element.ID

                            button.addEventListener('click', ()=>{
                               window.location.href = "<?php echo home_url('/single-person'); ?>?id=" + element.ID;
                               button.disabled = true;
                            })

                            div.appendChild(id)
                            div.appendChild(img)
                            div.appendChild(p)
                            div2.appendChild(button)
                            div.appendChild(div2)
                            main2.appendChild(div)

                            

                        })
                    }
                },
                error: function(){
                    var main2 = document.getElementById('mainGrid');
                    var p = document.createElement('p')
                    p.style.textAlign="center";
                    p.style.width = "100%";
                    p.style.marginTop = "50px";
                    p.style.fontSize = "2rem";
                    p.textContent = "Nie znaleziono takiej osoby"
                    main2.appendChild(p)
                    
                }
            })
            $("#btn").attr("disabled",false);
            
        })
            
    })


</script>