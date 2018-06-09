<?php

namespace FuzzyMatching;

use FuzzyMatching\Exception\MatcherException;

class Matcher
{
	const MAX_ENCRYPTED_STRING_LENGTH = 64;

	const MAX_STRING_LENGTH = 32;

	const MODE_STRICT = 'strict';

	const MODE_NORMAL = 'normal';

	private $foregroundAlphabet;

	private $backgroundAlphabet;

	public function __construct(Alphabet $foregroundAlphabet, Alphabet $backgroundAlphabet)
	{
		$this->foregroundAlphabet = $foregroundAlphabet;
		$this->backgroundAlphabet = $backgroundAlphabet;
	}

	public function similarity($str1, $str2)
	{
		if (mb_strlen($str1) > self::MAX_ENCRYPTED_STRING_LENGTH) {
			throw new MatcherException(self::MAX_ENCRYPTED_STRING_LENGTH);
		} elseif (mb_strlen($str2) > self::MAX_ENCRYPTED_STRING_LENGTH) {
			throw new MatcherException(self::MAX_ENCRYPTED_STRING_LENGTH);
		}

		// remove the chars of the background alphabet
		$backgroundAlphabetLetters = implode('', $this->backgroundAlphabet->letters());
		$str1 = preg_replace("/[$backgroundAlphabetLetters]/u", '', $str1);
		$str2 = preg_replace("/[$backgroundAlphabetLetters]/u", '', $str2);

		// calculate matches
		$chars1 = preg_split('//u', $str1, null, PREG_SPLIT_NO_EMPTY);
		$chars2 = preg_split('//u', $str2, null, PREG_SPLIT_NO_EMPTY);
		$matches = 0;
		for ($i=0; $i < count($chars1); $i++) {
			if (isset($chars1[$i]) && isset($chars2[$i])) {
				$chars1[$i] == $chars2[$i] ? $matches++ : false;
			}
		}

		// calculate similarity
		$averageLength = (mb_strlen($str1) + mb_strlen($str2)) / 2;
		$similarity = $matches / $averageLength;

		return round($similarity, 2);
	}
}
