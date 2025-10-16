<?php

add_shortcode('searchbar','show_searchbar');

function show_searchbar(){
    include MY_PLUGIN_PATH."/includes/templates/searchbar.php";
}