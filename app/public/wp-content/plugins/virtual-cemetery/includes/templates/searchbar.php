<style>
    <?php include MY_PLUGIN_PATH."assets/css/searchbar.css" ?>
</style>
<div id="container">
    <nav>
        <form id="searchForm">
            <input type="text" name="search" placeholder="Wyszukaj...">
            <button type="submit"><span class="dashicons dashicons-search"></span></button>
        </form>
    </nav>
    <main id="mainGrid">
        <div id="dead-person">
            <img src="data:image/png;base64,<?php print(base64_encode(file_get_contents(MY_PLUGIN_PATH."assets\images\\no-image.jpg"))); ?>">
            <p>Imie Nazwisko</p>
            <div style="display: flex;justify-content:center;">
                <button>Pokaż Osobę</button>
            </div>
        </div>
                 
    </main>
</div>

<script>
    jQuery(document).ready(function($){
        $("#searchForm").submit(function(event){
            event.preventDefault();
        })
            
    })


</script>