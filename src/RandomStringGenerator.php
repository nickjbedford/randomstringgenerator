<?php
	/** @noinspection PhpUnused */
	
	namespace Generators;
	
	use Exception;
	
	/**
	 * Provides methods to generate cryptographically strong random strings based on an input alphabet.
	 * @package Generators
	 */
	class RandomStringGenerator
	{
		/** @var array $alphabet */
		private $alphabet = [];
		
		/** @var string $alphabetString */
		private $alphabetString = '';
		
		/** Specifies the alphanumeric alphabet. */
		const ALPHABET_ALPHANUMERIC = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
		/** Specifies the digits alphabet. */
		const ALPHABET_DIGITS = '0123456789';
		
		/** Specifies the letters alphabet. */
		const ALPHABET_LETTERS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
		/** Specifies the lowercase letters alphabet. */
		const ALPHABET_LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
		
		/** Specifies the uppercase letters alphabet. */
		const ALPHABET_UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
		/** Specifies the uppercase hexadecimal alphabet. */
		const ALPHABET_HEX_UPPER = '0123456789ABCDEF';
		
		/** Specifies the lowercase hexadecimal alphabet. */
		const ALPHABET_HEX_LOWER = '0123456789abcdef';
		
		/** Specifies the upper and lowercase hexadecimal alphabet. */
		const ALPHABET_HEX = '0123456789abcdefABCDEF';
		
		/** Specifies the set of non-alphanumeric punctuation related characters. */
		const ALPHABET_PUNCTUATION = '!"#$%&\'()*+,-./:;<=>?@[\]^_`{|}~';
		
		/** Specifies the entire printable ASCII character set. */
		const ALPHABET_ASCII = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
		
		/**
		 * Initialises a new random string generator.
		 * @param string|null $alphabet The alphabet to use in string or string array form. Defaults to alphanumeric.
		 */
		public function __construct(?string $alphabet = self::ALPHABET_ALPHANUMERIC)
		{
			$this->alphabet(!empty($alphabet) ? $alphabet : self::ALPHABET_ALPHANUMERIC);
		}
		
		/**
		 * Gets and optionally sets the random string alphabet to use.
		 * @param string[]|string|null $alphabet The new alphabet to use, in string or string array form.
		 * @return string[] The alphabet used by the random string generator.
		 */
		public function alphabet(?string $alphabet = null): array
		{
			if (!empty($alphabet)) {
				$this->alphabetString = $alphabet;
				$this->alphabet = str_split($alphabet);
			}
			return $this->alphabet;
		}
		
		/**
		 * Gets the alphabet characters as a string.
		 */
		public function getAlphabetString(): string
		{
			return $this->alphabetString;
		}
		
		/**
		 * Generates a random string using the generator's alphabet.
		 * @param int $length The length of the random string to generate.
		 * @return string The random string.
		 * @throws Exception If it was not possible to gather sufficient entropy.
		 */
		public function createString(int $length = 16): string
		{
			if ($length < 1)
				return '';
			
			$alphabetLength = count($this->alphabet);
			$bytes = random_bytes($length);
			
			$string = '';
			for($i = 0; $i < $length; $i++)
				$string .= $this->alphabet[ord($bytes[$i]) % $alphabetLength];
			return $string;
		}
		
		/**
		 * Generates a random string using the specified alphabet.
		 * @param int $length The length of the random string to generate.
		 * @param string|null $alphabet The alphabet to use to generate the string.
		 * @return string The random string.
		 * @throws Exception
		 */
		public static function generate(int $length = 16, ?string $alphabet = self::ALPHABET_ALPHANUMERIC): string
		{
			$random = new self($alphabet);
			return $random->createString($length);
		}
		
		/**
		 * Determines the theoretical number of bits of entropy in a random string for a specified length.
		 * @param int $length The length of the random string being generated.
		 * @return float|int
		 */
		public function calculateEntropyBits(int $length = 16): float
		{
			return (log(count($this->alphabet)) / log(2)) * $length;
		}
	}
