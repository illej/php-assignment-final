<?php
session_save_path('./');
session_start();

include_once('MYSQLDB.php');
include_once('db.php');
include_once('UserListBuilder.php');
include_once('user.php');

if (isset($_SESSION['username']))
{
	$username = $_SESSION['username'];
}
else
{
	echo "<br>session variable not set<br>";
}

$usr = new User();
$usrList = new UserListBuilder($db, $usr);
echo $usrList->buildList();