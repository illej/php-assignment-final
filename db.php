<?php
$host = 'localhost' ;
$dbUser = 'root';
$dbPass = '';
$dbName = 'livechat_db';
 
$db = new MySQL($host, $dbUser, $dbPass, $dbName);
$db->selectDatabase();
?>
