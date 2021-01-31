<?php
	/** @noinspection PhpExpressionResultUnusedInspection */
	
	use Generators\RandomStringGenerator;
	
	if (!function_exists('generate_random_string'))
	{
		/**
		 * Generates a random string using the specified alphabet.
		 * @param int $length The length of the random string to generate.
		 * @param string|null $alphabet
		 * @return string The random string.
		 */
		function generate_random_string(
			int $length = 16,
			?string $alphabet = RandomStringGenerator::ALPHABET_ALPHANUMERIC): string
		{
			return RandomStringGenerator::generate($length, $alphabet);
		}
	}
