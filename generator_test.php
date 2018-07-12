<?php
	require(__DIR__ . '/RandomStringGenerator.php');
	
	if ($argc > 1)
		parse_str($argv[1], $_REQUEST);
	
	$length = intval($_REQUEST['length'] ? $_REQUEST['length'] : 16);
	
	$alphabet = null;
	switch($_REQUEST['alphabet'])
	{
		case 'HEX':
			$alphabet = RandomStringGenerator::ALPHABET_HEX_UPPER;
			break;
			
		case 'hex':
			$alphabet = RandomStringGenerator::ALPHABET_HEX_LOWER;
			break;
			
		case 'ascii':
			$alphabet = RandomStringGenerator::ALPHABET_ASCII;
			break;
			
		default:
			if (!empty($_REQUEST['alphabet']))
				$alphabet = $_REQUEST['alphabet'];
			break;
			
	}
	
	$generator = new RandomStringGenerator($alphabet);
	
	$output = [];
	$output[] = '';
	$output[] = 'Generating ' . $length . '-character random string:';
	$output[] = $key = $generator->generate($length);
	$output[] = '';
	
	$output[] = 'Theoretical Entropy: ' . number_format($generator->get_entropy_bits($length), 1) . ' bits';
	$output[] = 'Cryptographically Strong: ' . ($generator->crypto_strong ? 'Yes' : 'No');
	$output[] = '';
	
	echo join("\r\n", $output), "\r\n";

	var_dump($key);
