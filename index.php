<?php
include_once('MySQLDB.php');
include_once('db.php');
include_once('user.php');

session_save_path('./');
session_start();

include_once('LanguageParser.php');
$locale = new LanguageParser('kw');

if (isset($_SESSION['user_logged_in']))
{
	User::changeStatus($_SESSION['username'], 'inactive');
	session_destroy();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	session_save_path('./');
	session_start();

	try
	{
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
		$user = new User();
		$user->get($username);
		$pw = $user->getPassword($username);
		
		if (!$pw)
		{
			throw new Exception('Username does no exist');
		}
		if (password_verify($password, $pw) === false)
		{
			throw new Exception('Invalid password');
		}
		/*
		// Re-hash password if necessary
		$currentHashAlgorithm = PASSWORD_DEFAULT;
		$currentHashOptions = array('cost' => 15);
		$passwordNeedsRehash = password_needs_rehash
		(
			$user->getPassword($username),
			$currentHashAlgorithm,
			$currentHashOptions
		);
		if ($passwordNeedsRehash === true)
		{
			// Save new password hash(THIS IS PSUEDO-CODE)
			$user->setPassword(password_hash
				(
					$password,
					$currentHashAlgorithm,
					$currentHashOptions
				));
			$user->save();
		}*/
		
		$_SESSION['user_logged_in'] = 'yes';
		$_SESSION['username'] = $username;
		
		header('HTTP/1.1 302 Redirect');
		header('Location: ./live-chat.html'); //live-chat.html
		die();
	}
	catch (Exception $e)
	{
		header('HTTP/1.1 401 Unauthorised');
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
	<!-- <h1>Login</h1> -->
	<h1><?php echo $locale->translateSentence('Login'); ?></h1>
	<form action="index.php" method="POST">
		<label><?php echo $locale->translateSentence('Username'); ?>: </label>
		<input type="text" name="username">
			<br>
		<label><?php echo $locale->translateSentence('Password'); ?>: </label>
		<input type="password" name="password">
			<br>
		<input type="submit" value="<?php echo $locale->translateSentence('Submit'); ?>">
	</form>
	<a href="registration.php"><?php echo $locale->translateSentence('New User'); ?></a>
</body>
</html>