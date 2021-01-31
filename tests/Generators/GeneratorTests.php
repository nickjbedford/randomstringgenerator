<?php
	/** @noinspection PhpUnhandledExceptionInspection */
	
	namespace Generators;
	
	use PHPUnit\Framework\TestCase;
	
	class GeneratorTests extends TestCase
	{
		public function testGeneratorCreatesRandomStringsWithOnlyLettersInAlphabet()
		{
			$alphabets = [
				RandomStringGenerator::ALPHABET_ALPHANUMERIC,
				RandomStringGenerator::ALPHABET_ASCII,
				RandomStringGenerator::ALPHABET_HEX,
				RandomStringGenerator::ALPHABET_HEX_LOWER,
				RandomStringGenerator::ALPHABET_HEX_UPPER
			];
			
			foreach($alphabets as $alphabet)
			{
				$desiredLength = rand(10, 20);
				$generator = new RandomStringGenerator($alphabet);
				$string = $generator->createString($desiredLength);
				$length = strlen($string);
				$alphabetArray = $generator->alphabet();
				
				$this->assertEquals($desiredLength, $length);
				
				for($i = 0; $i < $length; $i++)
					$this->assertTrue(in_array($string[$i], $alphabetArray, true));
			}
		}
		
		public function testGeneratedStringWasCryptoStrong()
		{
			$generator = new RandomStringGenerator();
			$this->assertEquals(16, strlen($generator->createString()));
			$this->assertTrue($generator->wasCryptoStrong);
		}
	}
