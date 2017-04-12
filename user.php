: a<?php
include_once('db.php');
include_once('MySQLDB.php');

class User
{
	protected $username;
	protected $password;
	protected $email;
	
	public function insert($username, $pw, $fName, $lName, $email)
	{
		return "insert into USER values
				(
					null,
					'$username',
					'$pw',
					'$fName',
					'$lName',
					'$email'
				)";
	}
	
	public function setInfo($usr, $pw, $email)
	{
		$this->username = $usr;
		$this->setPassword($pw);
		$this->email = $email;
	}
	
	public function get($username)
	{
		global $db;
		$sql = "select * from user
				where username = '$username'";
		$result = $db->query($sql);
		$row = $result->fetch();
		return $row;
	}
	
	public function getPassword($username)
	{
		global $db;
		$sql = "select password from user
				where username = '$username'";
		$result = $db->query($sql);
		$row = $result->fetch();
		return $row['password'];
	}
	
	public function setPassword($pw)
	{
		$this->password = $pw;
	}
	
	/*public function getCustomerID($db, $theSurname, $thePassword)
	{
		// code for question 3 (a) goes here
		$sql = "select * from customer where Surname = '$theSurname' and Password = '$thePassword'";
		$result = $db->query($sql);
		//var_dump($result);
		//echo "<br><br>";
		$row = $result->fetch();
		return $row;
	}*/
	
	public static function changeStatus($username, $status)
	{
		global $db;
		$sql = "update USER
				set status = '$status'
				where username = '$username'";
		$result = $db->query($sql);
	}

	public function getActiveUser()
	{
		global $db;
		$sql = "select username
				from user
				where status = 'active'";
		$result = $db->query($sql);
		while ($row = $result->fetch())
		{
			$array[] = $row;
		}
		return $array;
	}

	public function save()
	{
		global $db;
		$sql = "insert into user 
				(
					username,
					password,
					email
				)
				values
				(
					'$this->username',
					'$this->password',
					'$this->email'
				)";
		$result = $db->query($sql);
		echo "<br>User saved $this->password<br>";
	}
	
}