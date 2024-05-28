<?php
$prompt = $_REQUEST["prompt"];
if($prompt=="")
{
	die("Invalid Prompt");
	
}

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=AIzaSyCbKNDKE9aVwWdnNCYn-czSZn1pkHsyegM',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{ "contents":[{
            "parts":[{"text": "'.$prompt.' using this JSON schema: \{ \"type\": \"array\", \"properties\": \{ \"topic\": \{ \"type\": \"string\" \},\}\}"}] }],
          "generationConfig": {
            "response_mime_type": "application/json",
          } }',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

$json = json_decode($response);

$topics = $json->candidates[0]->content->parts[0]->text;

$topics = json_decode($topics);

//var_dump($json->candidates[0]->content->parts[0]->text);

echo"<h1>",$prompt,"</h1>";

foreach ($topics as $t){
	echo"<br>",$t->topic,"</br>";
}

//var_dump($json->candidates[0]->content->parts[0]->text);

curl_close($curl);

//echo $response;

?>
