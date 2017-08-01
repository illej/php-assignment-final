<?php
include_once('MySQLDB.php');
include_once('db.php');

class Message
{
	protected $db;
	protected $content;
	protected $username;
	
	public function __construct($db)
	{
		$this->db = $db;
	}

	public function setMessage($username, $content)
	{
		$this->content = $content;
		$this->username = $username;
	}

	public function getMessage($id)
	{	
		$sql = "select time(timestamp) as time, username, content
				from message
				where messageID = '$id'";
		$result = $this->db->query($sql);
		$row = $result->fetch();
		return $row;
	}
	
	public function getStatistics($content)
	{
		$regex = '/^(%3E){2}(\w*)/';
		if (preg_match($regex, $content, $result))
		{
			$user = $result[2];
			$sql = "select avg(cnt) as average
					from (
						select count(messageID) as cnt,
							concat(CAST(HOUR(timestamp) as CHAR(2)),
							':',
							(CASE WHEN MINUTE(timestamp) < 30 THEN '00' ELSE '30' END)) as hour
						FROM message
						where username = '$user'
						GROUP BY CONCAT(CAST(HOUR(timestamp) AS CHAR(2)),
							':', (CASE WHEN MINUTE(timestamp) < 30 THEN '00' ELSE '30' END))
						ORDER BY hour
					) s";
			$result = $this->db->query($sql);
			$row = $result->fetch();
			$average = $row['average'];
			$html = "**$user has sent an average of $average messages in the last hour.";
			$this->setMessage($this->username, $html);
			$this->save();
		}
	}

	public function setStatus($username, $status)
	{
		$content = "is [$status].";
		$this->setMessage($username, $content);
	}

	public function save()
	{
		/*$sql = "insert into message values
				(
					null,
					'$this->content',
					now(),
					'$this->username'
				)";*/
		$sql = "insert into message values
				(	
					null,
					'" . $this->content . "',
					now(),
					'" . $this->username . "'
				)";
		$result = $this->db->query($sql);
	}
}