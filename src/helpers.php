<?php
	/** @noinspection PhpExpressionResultUnusedInspection */
	
	use Generators\RandomStringComposer;
	use Generators\RandomStringGenerator;
	
	if (!function_exists('generate_random_string'))
	{
		/**
		 * Generates a random string using the specified alphabet.
		 * This uses the Generators\RandomStringGenerator class.
		 * @param int $length The length of the random string to generate.
		 * @param string|null $alphabet The alphabet to use to generate the string.
		 * @return string The random string.
		 * @throws Exception
		 */
		function generate_random_string(
			int $length = 16,
			?string $alphabet = RandomStringGenerator::ALPHABET_ALPHANUMERIC): string
		{
			return RandomStringGenerator::generate($length, $alphabet);
		}
	}

	if (!function_exists('compose_random_string'))
	{
		/**
		 * Composes a random string based on a default upper/lowercase alphabet
		 * and a requirement of digit and punctuation characters of certain counts.
		 * This uses the Generators\RandomStringComposer class.
		 * @param int $length The length of the random string to generate.
		 * @param int $digits The number of digits to require.
		 * @param int $punctuation The number of punctuation marks to require.
		 * @return string
		 * @throws Exception
		 */
		function compose_random_string(int $length = 16, int $digits = 1, int $punctuation = 1): string
		{
			$composer = new RandomStringComposer();
			$composer->requireDigits($digits);
			$composer->requirePunctuation($punctuation);
			return $composer->createString($length);
		}
	}
