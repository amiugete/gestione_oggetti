<?php

require_once('funzioni_jwt.php');



if ($_SESSION['test']==1) {
    require_once ('./conn_test.php');
} else {
    require_once ('./conn.php');
}
$url= "http://amiupostgres/SIT_API_TEST/api/aste/1418020002/percorsi/22146";


$issuedAt   = new DateTimeImmutable();
$expire     = $issuedAt->modify('+420 minutes')->getTimestamp();


$headers = array('alg'=>'HS256','typ'=>'JWT');
$payload = array('role'=>'IT',
                'name'=>'Balletto',
                "userId"=>"117",
                "roleId"=>"5",
                "userMail"=>"",
                'iss' => $iss,
                'grants' => '',
                'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
                'nbf'  => $issuedAt->getTimestamp(),
                //'exp'	=>(time() + 60)
                'exp'  => $expire,                           // Expire
            );

$jwt = generate_jwt($headers, $payload, $secret_pwd);

echo $jwt."<br><hr>";

$token="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiIxMTciLCJ1c2VyTWFpbCI6IiIsIm5hbWUiOiJNYXJ6b2NjaGkiLCJyb2xlSWQiOiI1Iiwicm9sZSI6IklUIiwiZ3JhbnRzIjoiVl9QRVJDT1JTSTtNT0RfVVRFTlRJO01PRF9VVEVOWkU7Vl9TRVJWSVpJO1ZfVVRFTlpFO01PRF9QRVJDT1JTSTtBRERfQVNURTtNT0RfQVNURTtNT0RfRUxFTUVOVEk7Vl9MT0c7TU9EX1NFUlZJWkk7REVMX0VMRU1FTlRPO01PRF9WRUhJQ0xFUztJTVBPUlRfUElBWlpPTEU7Vl9VVEVOVEk7QUREX0VMRU1FTlRPO0FERF9QSUFaWk9MRTtBU1NPQ19QRVJDT1JTSTtERUxfUElBWlpPTEE7RklMVEVSX0VMX0lEO0ZJTFRFUl9QSUFaX0lEO01PRF9QSUFaWk9MRTtNT0RfU0VBU09OO1ZfTEFZRVJfR1JBRklDSTtWX0xBWUVSX1RPUE87SU1QT1JUX1BFUkNPUlNJO0FERF9QRVJDT1JTSSIsImlhdCI6MTY1NTgwMTI1MywibmJmIjoxNjU1ODAxMjUzLCJleHAiOjE2NTU4NDg3OTl9.8uPi-OwjeW2DXopk3hKR2OUpg8_9f9G-q875QSE-EM0";
echo $token."<br><hr>";
//$jwt="";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


$headers = array(
   "Accept: application/json",
   "Authorization: Bearer {$jwt}",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
echo $resp."<br>";
curl_close($curl);
#var_dump($resp);

?>