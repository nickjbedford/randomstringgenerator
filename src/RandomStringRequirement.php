<?php
	namespace Generators;
	
	use Exception;
	
	/**
	 * Specifies a requirement for a random string composition.
	 * @package Generators
	 */
	class RandomStringRequirement
	{
		private int $maximumCount;
		private int $minimumCount;
		private RandomStringGenerator $generator;
		
		public function __construct(int $maximumCount, string $alphabet, ?int $minimumCount = null)
		{
			$this->maximumCount = max(1, $maximumCount);
			$this->minimumCount = max(0, min($this->maximumCount, $minimumCount ?? $this->maximumCount));
			$this->generator = new RandomStringGenerator($alphabet);
		}
		
		/**
		 * Gets the maximum number of characters to require from this alphabet.
		 * @return int
		 */
		public function maximumCount(): int
		{
			return $this->maximumCount;
		}
		
		/**
		 * Gets the minimum number of characters to require from this alphabet.
		 * @return int
		 */
		public function minimumCount(): int
		{
			return $this->minimumCount;
		}
		
		/**
		 * Generates the required number of characters for the requirement.
		 * @return string
		 * @throws Exception
		 */
		public function generate(): string
		{
			$count = random_int($this->minimumCount, $this->maximumCount);
			return $this->generator->createString($count);
		}
		
		/**
		 * Determines the number of characters found in the requirement's alphabet.
		 * @param string $string
		 * @return int
		 */
		public function matchedCount(string $string): int
		{
			$count = 0;
			$length = strlen($string);
			$alphabet = $this->generator->alphabet();
			for($i = 0; $i < $length; $i++)
				if (in_array($string[$i], $alphabet, true))
					$count++;
			return $count;
		}
	}
