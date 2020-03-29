<?php
$ID = $_GET['scaned'];
$data = array("Product-ID" => $ID);
$data_string = json_encode($data); 
$getUrl = "http://localhost:7071/api/product";
$ch = curl_init();
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__)."/ssl.txt");                                                                     
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);
curl_setopt($ch, CURLOPT_URL, $getUrl);
curl_setopt($ch, CURLOPT_TIMEOUT, 80);                                
$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
?>