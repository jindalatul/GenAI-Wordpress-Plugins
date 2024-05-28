<?php
ini_set("display_errors","On");

$action = $_REQUEST["action"];
$title=$_REQUEST["title"];

if($action=="ideas")
{
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
            "parts":[{"text": "'.$prompt.' using this JSON schema: \\{ \\"type\\": \\"array\\", \\"properties\\": \\{ \\"topic\\": \\{ \\"type\\": \\"string\\" \\},\\"summary\\": \\{ \\"type\\": \\"string\\" \\},\\}\\}"}] }],
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

echo'<div style="width:600px;">';
echo"<h1>",$prompt,"</h1>";

foreach ($topics as $t)
{
	$prompt="Prepare blog post outline for topic ".$t->topic." and include summary of points to cover on each subtopic";
	
	echo"<h2>",$t->topic,"</h2>";
	echo"<p style='text-align:justify;'>",$t->summary,"</p>";
	echo"<a href='?title=$t->topic&action=outline'>View Outline</a>";
}
echo'</div>';

curl_close($curl);
}

if($action=="outline")
{
$title=$_REQUEST["title"];
$prompt="Prepare blog post outline for topic ".$title." and include summary of points to cover on each subtopic";

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
            "parts":[{"text": "'.$prompt.' using this JSON schema: \\{ \\"type\\": \\"array\\", \\"properties\\": \\{ \\"topic\\": \\{ \\"type\\": \\"string\\" \\},\\"summary\\": \\{ \\"type\\": \\"string\\" \\},\\}\\}"}] }],
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

echo'<div style="width:600px;">';

echo"<h1>",$title,"</h1>";

foreach ($topics as $t)
{
	echo"<h2>",$t->topic,"</h2>";
	echo"<p style='text-align:justify;'>",$t->summary,"</p>";
}

if($_REQUEST["is_outline"]="yes")
	echo"<a href='#'>Create Article</a>";

echo'</div>';

curl_close($curl);
}
?>
