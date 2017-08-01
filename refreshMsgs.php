<?php
session_save_path('./');
session_start();

include_once('MYSQLDB.php');
include_once('db.php');
include_once('ChatBuilder.php');
include_once('message.php');
include_once('LanguageParser.php');

if (isset($_SESSION['username']))
{
	$username = $_SESSION['username'];
}
else
{
	echo "<br>session variable not set<br>";
}

$locale = new LanguageParser('en');
$message = new Message($db);
$chat = new ChatBuilder($db, $locale, $message);
$chat->setUser($username);
echo $chat->getChatContent();