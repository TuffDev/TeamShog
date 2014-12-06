<?php

namespace water\api;


class aylien {
    public static function call_api($endpoint, $parameters) {
        $ch = curl_init('https://api.aylien.com/api/v1/' . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'X-AYLIEN-TextAPI-Application-Key: ' . 'ad04ec13da667f7236466e8f4c4d2a71',
            'X-AYLIEN-TextAPI-Application-ID: ' . '1c72c12a'
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        $response = curl_exec($ch);
        return json_decode($response);
    }
} 