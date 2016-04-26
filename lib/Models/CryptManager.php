<?php
namespace lib\Models;

class CryptManager extends \lib\Manager {

	public function encryptDistanceCustom($string='', $offset=0, $distance=0, $mod=0) {
		$newstring = [];
		for ($i=0 ; $i < strlen($string) ; $i++) {
			$newstring[$i] = chr(ord($string[$i]) + ($offset + $i * $distance) % $mod);
		}
		return implode($newstring);
	}

	public function decryptDistanceCustom($string='', $offset=0, $distance=0, $mod=0) {
		$newstring = [];
		for ($i=0 ; $i < strlen($string) ; $i++) {
			$newstring[$i] = chr(ord($string[$i]) - ($offset + $i * $distance) % $mod);
		}
		return implode($newstring);
	}
}