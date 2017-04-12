<?php
include_once('MySQLDB.php');
include_once('db.php');

class Message
{
	protected $db;
	protected $content;
	protected $username;
	protected $recipient;
	
	public function __construct($db/*, $content, $username*/)
	{
		//database
		$this->db = $db;
		//content
		
	}

	public function setMessage($username, $content)
	{
		$this->content = $content;
		$this->username = $username;
		$this->recipient = $this->setRecipient($content);
	}

	public function getMessage($id)
	{	
		/* private messaging */
		$sql = "select timestamp, username, content
				from message
				where messageID = '$id'
				and (recipient = 'all'
				OR recipient = '$this->username')";
		/*$sql = "select timestamp, username, content
				from message
				where messageID = '$id'";*/
		$result = $this->db->query($sql);
		$row = $result->fetch();
		return $row;
	}

	public function setStatus($username, $status)
	{
		$content = "is [$status].";
		$this->setMessage($username, $content);
	}

	public function setRecipient($content)
	{
		$regex = '/^(%3E){2}(\w*)/';
		if (preg_match($regex, $content, $result))
		{
			$recipient = $result[2];
		}
		else
		{
			$recipient = 'all';
		}
		return $recipient;

	}

	public function save()
	{
		$sql = "insert into message values
				(
					null,
					'$this->content',
					now(),
					'$this->username',
					'$this->recipient'
				)";
		$result = $this->db->query($sql);
	}
	
}