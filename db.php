<?php
$host = 'localhost' ;
$dbUser = 'root';
$dbPass = '';
$dbName = 'livechat';
 
$db = new MySQL($host, $dbUser, $dbPass, $dbName);
$db->selectDatabase();
?>
