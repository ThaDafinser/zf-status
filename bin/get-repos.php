<?php
/**
 * Quick & dirty code to fetch all ZF repos
 */

$url = 'https://api.github.com/orgs/zendframework/repos?per_page=5000';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);  

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    
    CURLOPT_USERAGENT => 'PHP Curl',
    
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false
]);

$result = curl_exec($ch);

curl_close($ch);

$repos = json_decode($result);

if(!is_array($repos)){
    throw new Exception('Result is not an array '.print_r($repos, true));
}

return $repos;
