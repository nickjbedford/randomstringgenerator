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
		 * @param int $count The number of characters to include from this alphabet.
		 * @param string $alphabet The alphabet to require characters from.
		 * @return self
		 */
		public function addRequirement(int $count, string $alphabet): self
		{
			if ($count > 0)
				$this->requirements[] = new RandomStringRequirement($count, $alphabet);
			return $this;
		}
		
		/**
		 * Requires the specicifed count of digit characters in generated strings.
		 * @param int $count
		 * @return self
		 */
		public function requireDigits(int $count = 1): self
		{
			return $this->addRequirement($count, RandomStringGenerator::ALPHABET_DIGITS);
		}
		
		/**
		 * Requires the specicifed count of punctuation characters in generated strings.
		 * @param int $count
		 * @return self
		 */
		public function requirePunctuation(int $count = 1): self
		{
			return $this->addRequirement($count, RandomStringGenerator::ALPHABET_PUNCTUATION);
		}
		
		/**
		 * Requires the specicifed count of letter characters in generated strings.
		 * @param int $count
		 * @return self
		 */
		public function requireLetters(int $count = 1): self
		{
			return $this->addRequirement($count, RandomStringGenerator::ALPHABET_LETTERS);
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
			
			return str_split($input, 1);
		}
		
		/**
		 * Determines if a string value satisifies the composer's requirements.
		 * @param string $string The string to test.
		 * @param int $minimumLength Specifies the minimum length required.
		 * @return bool
		 */
		public function satisfiesRequirements(string $string, int $minimumLength = 0): bool
		{
			$length = strlen($string);
			if ($length < $minimumLength)
				return false;
			
			foreach($this->requirements as $requirement)
			{
				$reqCount = $requirement->count();
				$count = $requirement->matchedCount($string);
				if ($count < $reqCount)
					return false;
				$length -= $count;
			}
			if ($length > 0)
			{
				$default = new RandomStringRequirement($length, $this->default->getAlphabetString());
				return $default->matchedCount($string) == $length;
			}
			return true;
		}
	}
