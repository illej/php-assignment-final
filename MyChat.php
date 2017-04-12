<?php
session_save_path('./');

include_once('message.php');
include_once('db.php');
include_once('MySQLDB.php');
include_once('chatBuilder.php');

if (isset($_SESSION['username']))
{
	$username = $_SESSION['username'];
}
else
{
	echo "<br>session variable not set<br>";
}

/* DELIVERY */
header('Content-Type: application/json');

$str = 'No message';
if (isset($_SERVER['QUERY_STRING']))
{
	$str = $_SERVER['QUERY_STRING'];
	$message = new Message($db, $str, $username);
	$message->save();
	$chat = new ChatBuilder();
	$content = $chat->getHtmlStructure();
	echo $content;
}