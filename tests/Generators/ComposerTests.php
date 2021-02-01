<?php
	/** @noinspection PhpUnhandledExceptionInspection */
	
	namespace Generators;
	
	use PHPUnit\Framework\TestCase;
	
	class ComposerTests extends TestCase
	{
		public function testComposerCreatesStringWithCorrectRequirements()
		{
			$length = random_int(20, 30);
			$digits = random_int(1, 5);
			$punctuation = random_int(1, 5);
			$letters = $length - $digits - $punctuation;
			
			$string = compose_random_string($length, $digits, $punctuation);
			
			$this->assertEquals($length, strlen($string));
			
			$countDigits = 0;
			$countPunctuation = 0;
			$countLetters = 0;
			
			$digitArray = str_split(RandomStringGenerator::ALPHABET_DIGITS);
			$punctuationArray = str_split(RandomStringGenerator::ALPHABET_PUNCTUATION);
			$letterArray = str_split(RandomStringGenerator::ALPHABET_LETTERS);
			
			for($i = 0; $i < strlen($string); $i++)
			{
				$char = $string[$i];
				if (in_array($char, $digitArray, true))
					$countDigits++;
				else if (in_array($char, $punctuationArray, true))
					$countPunctuation++;
				else if (in_array($char, $letterArray, true))
					$countLetters++;
			}
			
			$this->assertEquals($digits, $countDigits);
			$this->assertEquals($punctuation, $countPunctuation);
			$this->assertEquals($letters, $countLetters);
		}
	}
