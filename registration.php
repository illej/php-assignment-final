<?php
include_once('MySQLDB.php');
include_once('db.php');
include_once('user.php');
include_once('LanguageParser.php');
include_once('builder.php');

include_once('LanguageParser.php');
$locale = new LanguageParser('kw');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		$build = new Builder();
		$build->buildDatabase();

		$user = new User();
		
		// validate email
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		if (!$email)
		{
			throw new Exception('Invalid email');
		}
		
		// validate username
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
		if (!$username || $user->get($username))
		{
			throw new Exception('Username already exists');
		}
		
		// validate password
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
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
		if ($passwordHash === false)
		{
			throw new Exception('Password hash failed');
		}
		
		$user->setInfo($username, $passwordHash, $email);
		$user->save();
		
		// Redirect to login page
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
	<h1><?php echo $locale->translateSentence('Registration'); ?></h1>
	<form action="registration.php" method="POST">
		<label><?php echo $locale->translateSentence('Username'); ?>: </label>
		<input type="text" name="username">
			<br>
		<label><?php echo $locale->translateSentence('Email'); ?>: </label>
		<input type="email" name="email">
			<br>
		<label><?php echo $locale->translateSentence('Password'); ?>: </label>
		<input type="password" name="password">
			<br>
		<input type="submit" value="<?php echo $locale->translateSentence('Submit'); ?>">
	</form>
	<a href="index.php"><?php echo $locale->translateSentence('Back to Login'); ?></a>
</body>
</html>