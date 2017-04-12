<?php

Class LanguageParser
{
	protected $locale;

	public function __construct($locale)
	{
		$this->locale = $locale;
	}

	public function translateWord($word)
	{
		$array = parse_ini_file('i18n.ini', true);
		$language = $array[$this->locale];
		if (array_key_exists($word, $language)) {
			# code...
			$newWord = $language[$word];
		}
		else
		{
			$newWord = $word;
		}
		//var_dump($language[$word]);
		return $newWord;
	}

	public function translateSentence($sentence)
	{
		$array = explode(' ', $sentence);
		$newSentence = '';
		foreach ($array as $word)
		{
			$newSentence .= $this->translateWord($word) . ' ';
			//$newSentence .= ' ';
		}
		//var_dump($newSentence);
		return $newSentence;
	}
}