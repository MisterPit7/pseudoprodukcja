<?php

add_shortcode('searchbar','show_searchbar');

function show_searchbar(){

    if (!is_user_logged_in()) {
       echo "<script>window.location.href ='"  . home_url('/login/') . "'</script>";
       exit;
    }

    include MY_PLUGIN_PATH."/includes/templates/searchbar.php";
}