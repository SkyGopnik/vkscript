<?php
$token = "35297d91a4510d25fa0506881de232f4a154e5fec62a58767b50719f58213092846f8533683b254fced36";
$clubid = "57846937";
//does the community have a pin post? \ Есть ли в сообществе закреп true - да, false - нет
$havepoint = true; // if doesn't change to false \ Если есть напишите false
if($havepoint) {
	$getFirstPost = by('wall.get?owner_id=-'.$clubid.'&count=2&v=1&access_token='.$token);
	$getPostId = $getFirstPost[2]["id"];
} else {
	$getFirstPost = by('wall.get?owner_id=-'.$clubid.'&count=1&v=1&access_token='.$token);
	$getPostId = $getFirstPost[1]["id"];
}
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