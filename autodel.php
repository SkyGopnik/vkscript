<?php
	$token = "";
	// You can use cron-jon.org
	$getRequests = by("friends.getRequests?out=1&count=1&v=1&access_token=".$token);
    $text = "Вы были занесены в черный список моей страницы. \nПричина - Удаления из друзей. Сори но я ненавижу таких людей =3"; // Message that will send after unsubscribe 
    by("messages.send?user_id=".$getRequests[0]."&title=".urlencode("Вы были заблокированы")."&message=".urlencode($text)."&v=1&access_token=".$token); // Send message
    by("account.banUser?user_id=".$getRequests[0]."&v=1&access_token=".$token); // Add to black list 
    
	function by($method) {
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