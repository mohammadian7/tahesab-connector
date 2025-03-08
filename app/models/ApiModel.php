<?php

class ApiModel {
    public function send_request($url, $method, $api_authorization, $db_name, $body) {
        $headers = array(
            'Authorization' => 'Bearer ' . $api_authorization,
            'Content-Type' => 'application/json',
            'DBName' => $db_name, // اضافه کردن پارامتر DBName به هدرها
        );
        $args = array(
            'method'  => $method,
            'headers' => $headers,
            'body'    => $body,
            'sslverify' => false,
        );

        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            error_log("API Request Error: " . print_r($response, true));
            return false;
        }

        error_log("API Response: " . wp_remote_retrieve_body($response));
        return wp_remote_retrieve_body($response);
    }
}