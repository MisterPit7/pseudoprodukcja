<?php

add_shortcode('dashboard','show_dashboard');



function show_dashboard() {

     if (!is_user_logged_in()) {
       echo "<script>window.location.href =" . home_url('/login/') . "</script>";
       exit;
    }
  
    include_once MY_PLUGIN_PATH . '/includes/templates/dashboard.php';
}