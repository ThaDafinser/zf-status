<?php
/**
 * Quick & dirty code to fetch all ZF repos
 */

$url = 'https://api.github.com/orgs/zendframework/repos?per_page=100';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);  

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    
    CURLOPT_USERAGENT => 'PHP Curl',
    
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false
]);

$result = curl_exec($ch);

$repos = json_decode($result);

if(!is_array($repos)){
    throw new Exception('Result is not an array '.print_r($repos, true));
}

/*
 * Quick & dirty...
 * Page 2
 */
$url .= '&page=2';
curl_setopt($ch, CURLOPT_URL, $url);

$result = curl_exec($ch);

$repos2 = json_decode($result);

if(!is_array($repos2)){
    throw new Exception('Result is not an array '.print_r($repos2, true));
}

curl_close($ch);

$repos = array_merge($repos, $repos2);

if(count($repos2) === 200){
    throw new Exception('Please do real pagination! Already page 2 reached...');
}

return $repos;
