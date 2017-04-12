<?php
include_once 'MySQLDB.php';
require 'db.php';

class Builder
{
	public function buildDatabase()
	{
		global $db;
		$db->dropDatabase();
		//  create the database again
		$db->createDatabase(); // worked
		// select the database
		$db->selectDatabase(); // worked

		// drop the tables
		$sql = "drop table if exists user";
		$result = $db->query($sql);

		$sql = "drop table if exists message";
		$result = $db->query($sql);

		// create the tables
		/* USER */
		$sql = "create table user
		(
			username varchar(30) not null unique,
			password varchar(255),
			email varchar(50) unique,
			status varchar(20),
			primary key(username)
		) ENGINE = InnoDB";

		$result = $db->query($sql);
		if ($result)
		{
			echo 'the user table was added<br>';
		}
		else
		{
			echo 'the user table was not added<br>';
		}

		/* MESSAGE */
		$sql = "create table message
		(
			messageID int not null auto_increment, 
			content text,
			timestamp timestamp,
			username varchar(30),
			recipient varchar(30),
			foreign key(username) references user(username),
			primary key(messageID)
		) ENGINE = InnoDB";
									 
		//  execute the sql query
		$result = $db->query($sql);
		if ($result)
		{
		   echo 'the message table was added<br>';
		}
		else
		{
		   echo 'the message table was not added<br>';
		}
	}

	public function insertData()
	{
		/* INSERT DATA */
		/* USER */
		$sql = "insert into user values (
			null,
			'jakedawg',
			'1337',
			'Jacob',
			'van Maanen',
			'jdlifta@gmail.com'
		)";

		//  execute the sql query
		$result = $db->query($sql);

		$sql = "insert into user values
		(
			null,
			'iMackle',
			'beats',
			'Isaac',
			'Mackle',
			'beatmasta@gmail.com'
		)";

		//  execute the sql query
		$result = $db->query($sql);
				   
		/* MESSAGE */    
		$sql = "insert into message values
		(
			null,
			'hey there',
			now(),
			1
		)";

		$result = $db->query($sql);

		$sql = "insert into message values
		(
			null,
			'yo sup',
			now(),
			2
		)";

		$result = $db->query($sql);

		$sql = "insert into message values
		(
			null,
			'hows it goin?',
			now(),
			1
		)";

		$result = $db->query($sql);

		$sql = "insert into message values
		(
			null,
			'yeah not bad',
			now(),
			2
		)";

		$result = $db->query($sql);
	}
}