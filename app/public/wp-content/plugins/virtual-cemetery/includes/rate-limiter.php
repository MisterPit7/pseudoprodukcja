<?php
if (!defined('ABSPATH')){
    die('you cannot be here');
}

class RateLimiter {

    public static function check($key, $limit = 10, $seconds = 60) {
        $transient_key = 'rate_' . md5($key);
        $data = get_transient($transient_key);

        if (!$data) {
            $data = ['count' => 1, 'expires' => time() + $seconds];
            set_transient($transient_key, $data, $seconds);
            return true;
        }

        if ($data['count'] >= $limit) {
            return false;
        }

        $data['count']++;
        set_transient($transient_key, $data, $data['expires'] - time());
        return true;
    }
}