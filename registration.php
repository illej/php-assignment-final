<?php
include_once('MySQLDB.php');
include_once('db.php');
include_once('user.php');

include_once('builder.php');

/*
POST /register.php HTTP/1.1
Content-Length: 43
Content-Type: application/x-www-form-urlencoded

email=john@example.com&password=sekritshhh!
*/

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		//$build = new Builder();
		//$build->buildDatabase();

		$user = new User();
		
		// validate email
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		if (!$email)
		{
			throw new Exception('Invalid email');
		}
		
		// validate username
		$username = filter_input(INPUT_POST, 'username');
		if (!$username || $user->get($username))
		{
			throw new Exception('Username already exists');
		}
		
		// validate password
		$password = filter_input(INPUT_POST, 'password');
		if (!$password || strlen($password) < 8) //mb_strlen() :(
		{
			throw new Exception('Password must contain 8+ characters');
		}
		
		// Create password hash
		$passwordHash = password_hash
		(
			$password,
			PASSWORD_DEFAULT,
			['cost' => 12]
		);
		var_dump($passwordHash);
		if ($passwordHash === false)
		{
			throw new Exception('Password hash failed');
		}
		
		// Create user account (PSUEDO-CODE)
		//$user = new user();
		$user->setInfo($username, $passwordHash, $email);
		$user->save();
		
		// Redirect to longin.php
		header('HTTP/1.1 302 Redirect');
		header('Location: ./index.php');
		die();
	}
	catch (Exception $e)
	{
		// Report error
		header('HTTP/1.1 400 Bad request');
		echo $e->getMessage();
	}
}

?>

<!doctype html>
<html>
<head>
	<link rel="stylesheet" href="stylesheet.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body class="container">
	<h1>Registration</h1>
	<form action="registration.php" method="POST">
		<label>Username: </label>
		<input type="text" name="username">
			<br>
		<label>Email: </label>
		<input type="email" name="email">
			<br>
		<label>Password: </label>
		<input type="password" name="password">
			<br>
		<input type="submit">
	</form>
	<a href="index.php">Back to Login</a>
</body>
</html>