<?php

session_save_path('./');
session_start();

include_once('MYSQLDB.php');
include_once('db.php');
include_once('message.php');
include_once('ChatBuilder.php');
include_once('user.php');
include_once('UserListBuilder.php');

/* <<View>> */
//include_once('MyView.php');
//$t = new MyView();
//$t->render('index.phtml');

/* LOCALE */
include_once('LanguageParser.php');
$locale = new LanguageParser('de');

/* Check SESSION */
if (isset($_SESSION['username']))
{
	$username = $_SESSION['username'];
	User::changeStatus($username, 'active');
}
else
{
	echo "<br>session variable not set<br>";
}

/* AJAX */
header('Content-Type: application/json');

$str = 'No message';
if (isset($_SERVER['QUERY_STRING']))
{
	$chat = new chatBuilder($db, $locale);
	$chat->setUser($username);
	if ($_SERVER['QUERY_STRING'] == 'init')
	{
		/* Build ChatBox Structure */
		echo $chat->getHtmlStructure();
	}
	else if ($_SERVER['QUERY_STRING'] == 'users')
	{
		if ($_SESSION['username'] == $username
			&& $_SESSION['user_logged_in'] == true)
		{
			$usr = new User();
			$usrList = new UserListBuilder($db, $usr);
			echo $usrList->buildList();

			$message = new Message($db);
			$status = 'online';
			$message->setStatus($username, $status);
			$message->save();
		}
	}
	else if (isset($_SERVER['QUERY_STRING']))
	{
		$str = $_SERVER['QUERY_STRING'];
		$message = new Message($db);
		$message->setMessage($username, $str);
		$message->save();
		echo $chat->getHtmlContent($message);
	}
}




/*

$locale = 'de';
$word = 'hello';

$lang = new LanguageParser($locale);
$lang->translateWord($word);

$sentence = 'hello my name is elliot';
$lang->translateSentence($sentence);*/

