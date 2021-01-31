<?php
	/** @noinspection PhpUnused */
	
	namespace Generators;
	
	/**
	 * Provides methods to generate cryptographically strong random strings based on an input alphabet.
	 */
	class RandomStringGenerator
	{
		private array $alphabet = [];
		public bool $wasCryptoStrong = false;
		
		/** Specifies the alphanumeric alphabet. */
		const ALPHABET_ALPHANUMERIC = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
		/** Specifies the uppercase hexadecimal alphabet. */
		const ALPHABET_HEX_UPPER = '0123456789ABCDEF';
		
		/** Specifies the lowercase hexadecimal alphabet. */
		const ALPHABET_HEX_LOWER = '0123456789abcdef';
		
		/** Specifies the upper and lowercase hexadecimal alphabet. */
		const ALPHABET_HEX = '0123456789abcdefABCDEF';
		
		/** Specifies the entire printable ASCII character set. */
		const ALPHABET_ASCII = '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
		
		private static function getRandomBytes(int $length, ?bool &$cryptoStrong = null): string
		{
			return openssl_random_pseudo_bytes($length, $cryptoStrong);
		}
		
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
			if (!empty($alphabet))
				$this->alphabet = is_array($alphabet) ? $alphabet : str_split($alphabet);
			return $this->alphabet;
		}
		
		/**
		 * Generates a random string using the generator's alphabet.
		 * @param int $length The length of the random string to generate.
		 * @return string The random string.
		 */
		public function createString(int $length = 16): string
		{
			$alphabetLength = count($this->alphabet);
			$bytes = self::getRandomBytes($length, $this->wasCryptoStrong);
			
			$string = '';
			for($i = 0; $i < $length; $i++)
				$string .= $this->alphabet[ord($bytes[$i]) % $alphabetLength];
			return $string;
		}
		
		/**
		 * Generates a random string using the specified alphabet.
		 * @param int $length The length of the random string to generate.
		 * @param string|null $alphabet
		 * @return string The random string.
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
