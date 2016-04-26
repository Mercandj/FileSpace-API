<?php
namespace lib\Models;

class CryptManager extends \lib\Manager {

	public function decryptDistance($string='', $offset=0, $distance=0) {
		$newstring = [];
		for ($i=0 ; $i < strlen($string) ; $i++) {
			$t = ord($string[$i]);
			$newstring[$i] = chr($t - $offset - $i * $distance);
		}
		return implode($newstring);
	}

	public function encryptDistance($string='', $offset=0, $distance=0) {
		$newstring = [];
		for ($i=0 ; $i < strlen($string) ; $i++) {
			$t = ord($string[$i]);
			$newstring[$i] = chr($t + $offset + $i * $distance);
		}
		return implode($newstring);
	}
}