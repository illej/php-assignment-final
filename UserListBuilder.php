<?php

class UserListBuilder
{
	protected $db;
	protected $usrObj;

	public function __construct($db, $usrObj)
	{
		$this->db = $db;
		$this->usrObj = $usrObj;
	}

	public function buildList()
	{
		$count = $this->getUserCount();
		$array = $this->usrObj->getActiveUser();
		$html = '';
		for ($i = 0; $i < $count; $i++)
		{
			$user = $array[$i];
			$name = $user['username'];	
			$html .= '<br>';
			$html .= "$name";
			$html .= '<br>';
		}
		return $html;
	}

	public function getHtmlContent($msgObj)
	{
		$lang = new LanguageParser('de');
		$this->getMessageCount($msgObj);
		
		/* print all messages*/
		$htmlContent = '';
		for ($i = $this->lastTen; $i < $this->msgCount; $i++)
		{
			$array = $this->msgObj->getMessage($i + 1);
			$time = $array['timestamp'];	
			$name = $array['username'];	
			$contentRaw = urldecode($array['content']);
			$content = $lang->translateSentence($contentRaw);
			$htmlContent .= '<br>';
			$htmlContent .= "[$time] $name : $content";
			$htmlContent .= '<br>';
		}
		return $htmlContent;
	}

	public function getUserCount()
	{
		/* number of users */
		$sql = "select count(username) as 'count'
						from user
						where status = 'active'";
		$result = $this->db->query($sql);
		$usrs = $result->fetch();

		$usrCount = intval($usrs['count']);
		//var_dump($usrCount);
		return $usrCount;
	}
}