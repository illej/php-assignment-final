<?php

class ChatBuilder
{
	protected $user;
	protected $db;
	protected $lastTen;
	protected $msgCount;
	protected $message;
	protected $locale;
	
	public function __construct($db, $locale, $message)
	{
		$this->db = $db;
		$this->locale = $locale;
		$this->message = $message;
	}

	public function setUser($user)
	{
		$this->user = $user;
	}
	
	public function getMessageCount()
	{
		$sqlMsgs = "select count(messageID) as 'count'
						from message";
		$resultMsgs = $this->db->query($sqlMsgs);
		$msgs = $resultMsgs->fetch();
		$this->msgCount = intval($msgs['count']);
		$this->lastTen = $this->msgCount - 13;
	}
	
	public function getHtmlStructure()
	{
		$heading = $this->locale->translateSentence("Live Chat");
		$greeting = $this->locale->translateSentence("Welcome");
		$logout = $this->locale->translateSentence("Log Out");
		$loading = $this->locale->translateSentence("Loading");
		$submit = $this->locale->translateSentence("Submit");
		
		$html = "<h1>$heading</h1>";
		$html .= "<p>$greeting $this->user (";
		$html .= '<a id="log-out" href="index.php">';
		$html .= "$logout</a>)</p>";
		$html .= '<input id="input" type="text" onchange="go(); resetForm();">';
		$html .= '<input id="sub" type="submit" value="';
		$html .= "$submit";
		$html .= '"onclick="go(); resetForm();">';
		$html .= '<div id="chat-div">';
		$html .= '<div id="user-list" style"height: 30%">';
		$html .= "$loading..</div>";
		$html .= '<div id="msgs" style="overflow: auto; height: 70%">';
		$html .= "$loading..</div>";
		$html .= '</div>';
		return $html;
	}
	
	public function getChatContent()
	{
		$this->getMessageCount();
		
		/* Get messages */
		$htmlContent = '';
		for ($i = $this->lastTen; $i < $this->msgCount; $i++)
		{
			$array = $this->message->getMessage($i + 1);
			$time = $array['time'];	
			$name = $array['username'];	
			$contentRaw = urldecode($array['content']);
			$content = $this->locale->translateSentence($contentRaw);
			$htmlContent .= '<br>';
			$htmlContent .= "[$time] $name : $content";
			$htmlContent .= '<br>';
		}
		return $htmlContent;
	}
}