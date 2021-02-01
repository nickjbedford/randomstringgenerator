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
		
		public function testRandomStringsSatisfyComposerRequirements()
		{
			$valid = [
				'o-?^N_Yr4x*tdT3nkgvU',
				'2U_9w7%3NvM.?_%-n1ar',
				'C_@rU8>E#Q~2Hc8i]y4g',
				'rXQtMNA1C*~.t3cb'
			];
			
			$invalid = [
				'12!@aC',
				'     ',
				'monkey123',
				'password',
				'Abc123',
				'Some Password That iS! Bad'
			];
			
			$composer = new RandomStringComposer();
			$composer->requireDigits(2);
			$composer->requirePunctuation(2);
			
			foreach($valid as $item)
				$this->assertTrue($composer->satisfiesRequirements($item, 10));
			
			foreach($invalid as $item)
				$this->assertFalse($composer->satisfiesRequirements($item, 10));
		}
	}
