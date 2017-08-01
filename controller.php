<?php

session_save_path('./');
session_start();

include_once('MYSQLDB.php');
include_once('db.php');
include_once('message.php');
include_once('ChatBuilder.php');
include_once('user.php');
include_once('UserListBuilder.php');
include_once('LanguageParser.php');

/* <<View>> */
//include_once('MyView.php');
//$t = new MyView();
//$t->render('index.phtml');

/* TO DO
- include VIEWS within if/elses
- also make refresh send another xhr to controller instead of refreshmsgs/usrs.php
*/

/* LOCALE */
$locale = new LanguageParser('kw');

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
	$message = new Message($db);
	$chat = new ChatBuilder($db, $locale, $message);
	$chat->setUser($username);
	if ($_SERVER['QUERY_STRING'] == 'init')
	{
		/* Build ChatBox structure */
		echo $chat->getHtmlStructure();
	}
	else if ($_SERVER['QUERY_STRING'] == 'users')
	{
		if ($_SESSION['username'] == $username
			&& $_SESSION['user_logged_in'] == true)
		{
			/* Build UserList structure */
			$usr = new User();
			$usrList = new UserListBuilder($db, $usr);
			echo $usrList->buildList();
		}
	}
	else if (isset($_SERVER['QUERY_STRING']))
	{
		/* Populate MesssageBox */
		$str = $_SERVER['QUERY_STRING'];
		$message->setMessage($username, $str);
		$message->save();
		echo $chat->getChatContent();
		$message->getStatistics($str);
	}
}