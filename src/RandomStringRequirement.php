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
		
		/**
		 * Generates one or more characters from the generator,
		 * counting the number.
		 * @param int $count If omitted, this generates the count of characters required.
		 * @return string
		 * @throws Exception
		 */
		public function generate(int $count = 0): string
		{
			return $this->generator->createString($count ?: $this->count);
		}
		
		public function count(): int
		{
			return $this->count;
		}
	}
