<?php
	/** @noinspection PhpUnused */
	
	namespace Generators;
	
	use Exception;
	
	/**
	 * Provides methods to generate cryptographically strong random strings based
	 * on input alphabets of certain requirements.
	 * @package Generators
	 */
	class RandomStringComposer
	{
		/** @var RandomStringRequirement[] $requirements */
		private array $requirements = [];
		
		private RandomStringGenerator $default;
		
		/**
		 * Initialises the composer with a default alphabet (defaults to letter characters only).
		 * @param string|null $defaultAlphabet
		 */
		public function __construct(?string $defaultAlphabet = RandomStringGenerator::ALPHABET_LETTERS)
		{
			$this->default = new RandomStringGenerator($defaultAlphabet);
		}
		
		/**
		 * Adds a requirement of a specific count of included characters of a specific alphabet.
		 * @param int $maximumCount The number of characters to include from this alphabet. If $minimumCount is
		 * specified, this will specify the maximum number of characters to include.
		 * @param string $alphabet The alphabet to require characters from.
		 * @param int|null $minimumCount Optional. A minimum number of characters to include.
		 * @return self
		 */
		public function addRequirement(int $maximumCount, string $alphabet, ?int $minimumCount = null): self
		{
			if ($maximumCount > 0)
				return $this->requireExisting(new RandomStringRequirement($maximumCount, $alphabet, $minimumCount));
			return $this;
		}
		
		/**
		 * Adds a requirement object of a specific count of included characters of a specific alphabet.
		 * @param RandomStringRequirement $requirement
		 * @return self
		 */
		public function requireExisting(RandomStringRequirement $requirement): self
		{
			$this->requirements[] = $requirement;
			return $this;
		}
		
		/**
		 * Requires the specicifed count of digit characters in generated strings.
		 * @param int $maximumCount The number of characters to include from this alphabet. If $minimumCount is
		 * specified, this will specify the maximum number of characters to include.
		 * @param int|null $minimumCount Optional. A minimum number of characters to include.
		 * @return self
		 */
		public function requireDigits(int $maximumCount = 1, ?int $minimumCount = null): self
		{
			return $this->addRequirement($maximumCount, RandomStringGenerator::ALPHABET_DIGITS, $minimumCount);
		}
		
		/**
		 * Requires the specicifed count of punctuation characters in generated strings.
		 * @param int $maximumCount The number of characters to include from this alphabet. If $minimumCount is
		 * specified, this will specify the maximum number of characters to include.
		 * @param int|null $minimumCount Optional. A minimum number of characters to include.
		 * @return self
		 */
		public function requirePunctuation(int $maximumCount = 1, ?int $minimumCount = null): self
		{
			return $this->addRequirement($maximumCount, RandomStringGenerator::ALPHABET_PUNCTUATION, $minimumCount);
		}
		
		/**
		 * Requires the specicifed count of letter characters in generated strings.
		 * @param int $maximumCount The number of characters to include from this alphabet. If $minimumCount is
		 * specified, this will specify the maximum number of characters to include.
		 * @param int|null $minimumCount Optional. A minimum number of characters to include.
		 * @return self
		 */
		public function requireLetters(int $maximumCount = 1, ?int $minimumCount = null): self
		{
			return $this->addRequirement($maximumCount, RandomStringGenerator::ALPHABET_LETTERS, $minimumCount);
		}
		
		/**
		 * Requires the specicifed count of lowercase letter characters in generated strings.
		 * @param int $maximumCount The number of characters to include from this alphabet. If $minimumCount is
		 * specified, this will specify the maximum number of characters to include.
		 * @param int|null $minimumCount Optional. A minimum number of characters to include.
		 * @return self
		 */
		public function requireLowercaseLetters(int $maximumCount = 1, ?int $minimumCount = null): self
		{
			return $this->addRequirement($maximumCount, RandomStringGenerator::ALPHABET_LOWERCASE, $minimumCount);
		}
		
		/**
		 * Requires the specicifed count of uppercase letter characters in generated strings.
		 * @param int $maximumCount The number of characters to include from this alphabet. If $minimumCount is
		 * specified, this will specify the maximum number of characters to include.
		 * @param int|null $minimumCount Optional. A minimum number of characters to include.
		 * @return self
		 */
		public function requireUppercaseLetters(int $maximumCount = 1, ?int $minimumCount = null): self
		{
			return $this->addRequirement($maximumCount, RandomStringGenerator::ALPHABET_UPPERCASE, $minimumCount);
		}
		
		/**
		 * Creates a new string based on the specified requirements.
		 * @param int $length The length of the generated string.
		 * @return string
		 * @throws Exception
		 */
		public function createString(int $length = 16): string
		{
			$input = $this->generateInputCharacterSet($length);
			
			$string = '';
			while($count = count($input))
			{
				$i = random_int(0, $count - 1);
				$string .= $input[$i];
				array_splice($input, $i, 1);
			}
			return $string;
		}
		
		/**
		 * Generates the input character set for random shuffling.
		 * @param int $length
		 * @return string[]
		 * @throws Exception
		 */
		private function generateInputCharacterSet(int $length): array
		{
			$input = '';
			foreach ($this->requirements as $requirement)
				$input .= $requirement->generate();
			
			if ($remaining = max(0, $length - strlen($input)))
				$input .= $this->default->createString($remaining);
			
			return str_split($input);
		}
		
		/**
		 * Determines if a string value satisifies the composer's requirements.
		 * @param string $string The string to test.
		 * @param int $minimumLength Specifies the minimum length required.
		 * @param bool $strictCounting Whether to require that no more than the maximum count of characters
		 * from each requirement is included.
		 * @return bool
		 */
		public function satisfiesRequirements(string $string, int $minimumLength = 0, bool $strictCounting = true): bool
		{
			$length = strlen($string);
			if ($length < $minimumLength)
				return false;
			
			foreach($this->requirements as $requirement)
			{
				$count = $requirement->matchedCount($string);
				
				if ($count < $requirement->minimumCount())
					return false;
				
				if ($strictCounting && $count > $requirement->maximumCount())
					return false;
				
				$length -= $count;
			}
			
			if ($length > 0)
			{
				$default = new RandomStringRequirement($length, $this->default->alphabetString);
				return $default->matchedCount($string) == $length;
			}
			return true;
		}
	}
