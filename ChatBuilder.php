<?php
include_once('LanguageParser.php');

class ChatBuilder
{
	protected $user;
	protected $db;
	protected $lastTen;
	protected $msgCount;
	protected $msgObj;
	protected $locale;
	
	public function __construct($db, $locale)
	{
		$this->db = $db;
		$this->locale = $locale;
	}

	public function setUser($user)
	{
		$this->user = $user;
	}
	
	public function getMessageCount($msgObj)
	{
		$this->msgObj = $msgObj;
		
		/* number of messages */
		$sqlMsgs = "select count(messageID) as 'count'
						from message";
		$resultMsgs = $this->db->query($sqlMsgs);
		$msgs = $resultMsgs->fetch();

		$this->msgCount = intval($msgs['count']);
		/*echo '<br>';
		echo $this->msgCount;
		echo '<br>';*/
		$this->lastTen = $this->msgCount - 20;
		/*echo '<br>';
		echo $this->lastTen;
		echo '<br>';*/
	}
	
	public function getHtmlStructure()
	{
		$heading = $this->locale->translateSentence("Live Chat");
		$greeting = $this->locale->translateSentence("Welcome");
		$logout = $this->locale->translateSentence("Log Out");
		$html = "<h1>$heading</h1>";
		$html .= "<p>$greeting $this->user (";
		$html .= '<a id="log-out" href="index.php">';
		$html .= "$logout</a>)</p>";
		$html .= '<input id="input" type="text" onchange="go(); resetForm();">';
		$html .= '<input id="sub" type="submit" onclick="go(); resetForm();">';
		$html .= '<div id="chat-div" style="display: flex">';
		$html .= '<div id="msgs">Loading..</div>';
		$html .= '<div id="user-list">Loading..</div>';
		$html .= '</div>';
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
}