<?
$url = 'https://api.carrotquest.io/v1/users/'.$_COOKIE['carrotquest_uid'].'/events';
 
$result = file_get_contents($url, false, stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query(array('event'   => '$cart_added',
        'params' => '{
            "$name": "$_POST["name"]",
            "$url": "$_POST["url"]",
            "$amount": "$_POST["amout"]",
           "$img": "$_POST["img"]",
 
        }',
        'auth_token' => 'app.13181.22db66eec20213c6a0638f9dcbba4dbff5d40e3aeed84e5c'
        )),
       
       
    )
)));
 echo json_encode(array(
        'result' => $result
    ));

?>