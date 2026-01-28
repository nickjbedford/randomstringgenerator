# Random String Generator

`Generator\RandomStringGenerator` generates cryptographically strong random strings based on a supplied alphabet of characters (such as alphanumeric characters). This is perfect for generating API keys within a set of allowed characters. It can also be used to generate strong passwords.

The class relies on the `random_bytes()` function to generate the cryptographically strong random numbers for use in building the random strings.

## Usage

To generate a 16-character string using the default 62-character alphanumeric alphabet, the usage is as simple as the following:

```php
	$generator = new Generators\RandomStringGenerator();
	$key = $generator->generate();
	// string(16) "7m98Jvc0ArBDS4jK"
```

To use a different alphabet, such as uppercase hexadecimal characters, and to generate a random string of a different length, these parameters can be passed to the class as follows:

	$generator = new RandomStringGenerator(RandomStringGenerator::ALPHABET_HEX_UPPER);
	$key = $generator->generate(8);
	// string(8) "CF70D24E"

A custom alphabet can be provided in the form of an input string of characters, such as:

	$generator = new RandomStringGenerator('abc123');
	$key = $generator->generate();
	// string(16) "cb2c2a33c2c3a23b"

This is then converted to an array of characters internally. This array can be retrieved using the `$generator->alphabet()` function with no argument.
