<?php
	namespace Generators;
	
	use Exception;
	
	/**
	 * Specifies a requirement for a random string composition.
	 * @package Generators
	 */
	class RandomStringRequirement
	{
		private int $count;
		private RandomStringGenerator $generator;
		
		public function __construct(int $count, string $alphabet)
		{
			$this->count = max(1, $count);
			$this->generator = new RandomStringGenerator($alphabet);
		}
		
		public function count(): int
		{
			return $this->count;
		}
		
		/**
		 * Generates the required number of characters for the requirement.
		 * @return string
		 * @throws Exception
		 */
		public function generate(): string
		{
			return $this->generator->createString($this->count);
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
