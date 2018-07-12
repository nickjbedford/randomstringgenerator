# RandomStringGenerator Class

Simple class to generate cryptographically strong strings based on a supplied alphabet of characters (such as alphanumeric characters). This is perfect for generating API keys within a set of allowed characters.

The class relies on the `openssl_pseudo_random_bytes()` function to generate the cryptographically strong random numbers for use in building the random strings.

## Usage

To generate a 16-character string using the default 62-character alphanumeric alphabet, the usage is as simple as the following:
	
	$generator = new RandomStringGenerator();
	$key = $generator->generate();
	// string(16) "7m98Jvc0ArBDS4jK"

To use a different alphabet, such as uppercase hexadecimal, and to generate a random string of a different length, these can be passed to the class as follows:

	$generator = new RandomStringGenerator(RandomStringGenerator::ALPHABET_HEX_UPPER);
	$key = $generator->generate(8);
	// string(8) "CF70D24E"

A custom alphabet can be provided in the form of an array or string of characters, such as:

	$generator = new RandomStringGenerator('abc123');
	$key = $generator->generate();
	// string(16) "cb2c2a33c2c3a23b"
