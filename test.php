<?php
echo decryptPBEWithMD5AndDES('fcLUY5ZV02RaRgN1hZyt6HG3ctcXFJF0'); // Will print 'Hello World!'

function decryptPBEWithMD5AndDES($encrypted, $password = 'bilbo', $iterations = 1000) {
	$data = bin2hex(base64_decode($encrypted));
	$salt = substr($data, 0, 16);
	$eb = hex2bin(substr($data, 16));

	$dk = array_shift(unpack('H*', $password)) . $salt;
	for ($i = 0; $i < $iterations; $i++) {
		$dk = md5(hex2bin($dk));
	}
	$key = hex2bin(substr($dk, 0, 16));
	$iv = hex2bin(substr($dk, 16));

	$cipher = mcrypt_module_open(MCRYPT_DES, '', MCRYPT_MODE_CBC, '');
	mcrypt_generic_init($cipher, $key, $iv);
	$text = mdecrypt_generic($cipher, $eb);
	$padding = array_values(unpack('C', substr($text, -1)))[0];
	$text = substr($text, 0, strlen($text) - $padding);

	mcrypt_generic_deinit($cipher);
	mcrypt_module_close($cipher);
	
	return $text;
}
?>