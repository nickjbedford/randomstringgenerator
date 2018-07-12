<?php
	/**
	 * Represents a random string generator.
	 */
	class RandomStringGenerator
	{
		/**
		 * The alphabet to use when generating aa random string.
		 * @var string[] $alphabet
		 */
		private $alphabet;
		
		/**
		 * @var bool $crypto_strong Whether the last random string was generated with cryptographically strong bytes.
		 */
		public $crypto_strong;
		
		/**
		 * Represents the alphanumeric alphabet.
		 */
		const ALPHABET_ALPHANUMERIC = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
		/**
		 * Represents the uppercase hexadecimal alphabet.
		 */
		const ALPHABET_HEX_UPPER = '0123456789ABCDEF';
		
		/**
		 * Represents the lowercase hexadecimal alphabet.
		 */
		const ALPHABET_HEX_LOWER = '0123456789abcdef';
		
		/**
		 * Represents the entire printable ASCII character set.
		 */
		const ALPHABET_ASCII = '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
		
		/**
		 * Gets random bytes for the random string generation. This uses OpenSSL's PRNG function to generate random
		 * bytes for the key generator. For most machines, this should be cryptographically strong.
		 * @param int $length The number of bytes to generate.
		 * @param bool|null $crypto_strong If passed into the function, this will hold a boolean value that determines
		 * if the algorithm used was "cryptographically strong", e.g., safe for usage with GPG,
		 * passwords, etc. true if it did, otherwise false.
		 * @return string
		 */
		private static function get_random_bytes($length, &$crypto_strong)
		{
			return openssl_random_pseudo_bytes($length, $crypto_strong);
		}
		
		/**
		 * Initialises a new random string generator.
		 * @param string[] $alphabet The alphabet to use in string or string array form. Defaults to alphanumeric.
		 */
		public function __construct($alphabet = null)
		{
			$this->alphabet(!empty($alphabet) ? $alphabet : self::ALPHABET_ALPHANUMERIC);
		}
		
		/**
		 * Gets and optionally sets the random string alphabet to use.
		 * @param string[]|string|null $alphabet The new alphabet to use, in string or string array form.
		 * @return string[] The alphabet used by the random string generator,.
		 */
		public function alphabet($alphabet = null)
		{
			if (!empty($alphabet))
				$this->alphabet = is_array($alphabet) ? $alphabet : str_split($alphabet);
			return $this->alphabet;
		}
		
		/**
		 * Generates a random string using the provided alphabet.
		 * @param int $length The length of the random string to generate.
		 * @return string The random string.
		 */
		public function generate($length = 16)
		{
			$alphabetLength = count($this->alphabet);
			$bytes = self::get_random_bytes($length, $this->crypto_strong);
			
			$string = '';
			for($i = 0; $i < $length; $i++)
				$string .= $this->alphabet[ord($bytes[$i]) % $alphabetLength];
			return $string;
		}
		
		/**
		 * Determines the theoretical number of bits of entropy in a random string for a specified length.
		 * @param int $length The length of the random string being generated.
		 * @return float|int
		 */
		public function get_entropy_bits($length = 16)
		{
			return (log(count($this->alphabet)) / log(2)) * $length;
		}
	}
