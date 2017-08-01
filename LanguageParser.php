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
		if (array_key_exists($word, $language))
		{
			$newWord = $language[$word];
		}
		else
		{
			$newWord = $word;
		}
		return $newWord;
	}

	public function translateSentence($sentence)
	{
		$array = explode(' ', $sentence);
		$newSentence = '';
		$index = 0;
		foreach ($array as $word)
		{
			$newSentence .= $this->translateWord($word);
			if ($index < (count($array) - 1))
			{
				$newSentence .= ' ';
			}
			$index++;
		}
		return $newSentence;
	}
}