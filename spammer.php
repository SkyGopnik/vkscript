<?php
$token = "";
$clubid = "57846937";
$getFirstPost = by('wall.get?owner_id=-'.$clubid.'&count=1&v=1&access_token='.$token);
$getPostId = $getFirstPost[1]["id"];
$createComment = by('wall.createComment?owner_id=-'.$clubid.'&post_id='.$getPostId
	.'&message=Первый&v=1&access_token='.$token);
echo "Post id: ".$getPostId;
function by($method){
	$ch = curl_init("https://api.vk.com/method/".$method);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	$response = curl_exec($ch);
	curl_close($ch);
	$json = json_decode($response, true);
	return $json["response"];
}
?>