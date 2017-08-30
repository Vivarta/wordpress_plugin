<?php
/**
 * Created by IntelliJ IDEA.
 * User: juyal
 * Date: 8/24/17
 * Time: 8:21 PM
 */

function get_curl_content($URL){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $URL);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}