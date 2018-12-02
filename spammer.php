<?php
$token = ""; // your access token from VK
$clubid = "57846937"; // club id MUST BE NUMBERS (http://regvk.com/id/)
$message = trim("Человек который читает этот коммент - Знай ты лучший :)"); 
/*
Mysql base
CREATE TABLE `postsid` (
  `id` int(11) NOT NULL,
  `club` text NOT NULL,
  `postid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `postsid`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `postid` (`postid`);

ALTER TABLE `postsid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

*/
$mysqlsite = mysqli_connect('localhost', 'base_name', 'base_password', 'user_name');
//does the community have a pin post? \ Есть ли в сообществе закреп true - да, false - нет
$havepoint = true; // if doesn't change to false \ Если нет напишите false
if($havepoint) {
	$getFirstPost = by('wall.get?owner_id=-'.$clubid.'&count=2&v=1&access_token='.$token);
	$getPostId = $getFirstPost[2]["id"];
} else {
	$getFirstPost = by('wall.get?owner_id=-'.$clubid.'&count=1&v=1&access_token='.$token);
	$getPostId = $getFirstPost[1]["id"];
}
$getMysqlLastPostId = mysqli_query($mysqlsite, "SELECT `postid` FROM `postsid` WHERE `club`='".$clubid."' ORDER BY `id` DESC LIMIT 1");
$mysqlLastPostId = mysqli_fetch_array($getMysqlLastPostId);
echo "Last post id in MYSQL: ".$mysqlLastPostId['postid']."<br>";


$createCommentParams = array(
    'owner_id'      => -$clubid,
    'post_id'       => $getPostId,
    'message'       => $message,
    'access_token'  => $token,
    'v'             => 1,
);

$createComment = curl_init( 'https://api.vk.com/method/wall.createComment');

curl_setopt_array( $createComment, array(
    CURLOPT_POST            => TRUE,
    CURLOPT_POSTFIELDS      => $createCommentParams,

    CURLOPT_RETURNTRANSFER  => TRUE,
    CURLOPT_SSL_VERIFYPEER  => FALSE,
    CURLOPT_SSL_VERIFYHOST  => FALSE,
    CURLOPT_CONNECTTIMEOUT  => 10,
    CURLOPT_TIMEOUT         => 10,
));
if($mysqlLastPostId['postid'] != $getPostId) {
	$postCreateComment = curl_exec($createComment);
	$insertMysqlLastPostid = mysqli_query($mysqlsite, "INSERT INTO `postsid` (`club`, `postid`) VALUES ('".$clubid."', '".$getPostId."')");
	if($insertMysqlLastPostid) {
		echo "Successful insert to mysql<br>";
	}
} else {
	echo "Ignore post<br>";
}
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