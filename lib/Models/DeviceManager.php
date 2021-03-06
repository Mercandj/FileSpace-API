<?php
namespace lib\Models;
use \lib\Entities\Device;

class DeviceManager extends \lib\Manager {
	protected static $instance;

	public function add(Device $device) {

		$to_insert['content']								= $device->getContent();
		$to_insert['date_creation']							= $device->getDate_creation();
		$to_insert['date_update']							= $device->getDate_update();
		$to_insert['visibility']							= intval($device->getVisibility());
		$to_insert['public']								= intval($device->getPublic());

		if(empty($to_insert['visibility']))					$to_insert['visibility'] = 1;
		if(empty($to_insert['public']))						$to_insert['public'] = 0;

		$to_insert['operating_system']						= $device->getOperating_system();

		$to_insert['android_app_gcm_id'] 					= $device->getAndroid_app_gcm_id();
		$to_insert['android_app_version_code'] 				= $device->getAndroid_app_version_code();
		$to_insert['android_app_version_name'] 				= $device->getAndroid_app_version_name();
		$to_insert['android_app_package'] 					= $device->getAndroid_app_package();

		$to_insert['android_device_id'] 					= $device->getAndroid_device_id();
		$to_insert['android_device_id_u1'] 					= $device->getAndroid_device_id_u1();
		$to_insert['android_device_advertising_id'] 		= $device->getAndroid_device_advertising_id();
		$to_insert['android_device_model'] 					= $device->getAndroid_device_model();
		$to_insert['android_device_manufacturer']			= $device->getAndroid_device_manufacturer();
		$to_insert['android_device_language']				= $device->getAndroid_device_language();
		$to_insert['android_device_display_language']		= $device->getAndroid_device_display_language();
		$to_insert['android_device_country']				= $device->getAndroid_device_country();
		$to_insert['android_device_version_sdk']			= $device->getAndroid_device_version_sdk();
		$to_insert['android_device_timezone']				= $device->getAndroid_device_timezone();
		$to_insert['android_device_year']					= $device->getAndroid_device_year();
		$to_insert['android_device_rooted']					= $device->getAndroid_device_rooted();

		$req_str = 'INSERT INTO `device` (';
		$numItems = count($to_insert);
		$i = 0;
		foreach ($to_insert as $key => $value) {
			if(++$i === $numItems) {
				// last index
				$req_str .= $key . ') VALUES (';
			} else {
				$req_str .= $key . ', ';
			}
		}
		$i = 0;
		foreach ($to_insert as $key => $value) {
			if(++$i === $numItems) {
				// last index
				$req_str .= ':' . $key . ')';
			} else {
				$req_str .= ':' . $key . ', ';
			}
		}
		
		$req = $this->_db->prepare($req_str);

		$req->bindParam(':content',								$to_insert['content'],								\PDO::PARAM_STR);
		$req->bindParam(':date_creation',						$to_insert['date_creation'],						\PDO::PARAM_STR);
		$req->bindParam(':date_update',							$to_insert['date_update'],							\PDO::PARAM_STR);
		$req->bindParam(':visibility',							$to_insert['visibility'],							\PDO::PARAM_INT);
		$req->bindParam(':public',								$to_insert['public'],								\PDO::PARAM_INT);

		$req->bindParam(':operating_system',					$to_insert['operating_system'],						\PDO::PARAM_STR);
		$req->bindParam(':android_app_gcm_id',					$to_insert['android_app_gcm_id'],					\PDO::PARAM_STR);
		$req->bindParam(':android_app_version_code',			$to_insert['android_app_version_code'],				\PDO::PARAM_STR);
		$req->bindParam(':android_app_version_name',			$to_insert['android_app_version_name'],				\PDO::PARAM_STR);
		$req->bindParam(':android_app_package',					$to_insert['android_app_package'],					\PDO::PARAM_STR);

		$req->bindParam(':android_device_id',					$to_insert['android_device_id'],					\PDO::PARAM_STR);
		$req->bindParam(':android_device_id_u1',				$to_insert['android_device_id_u1'],					\PDO::PARAM_STR);
		$req->bindParam(':android_device_advertising_id',		$to_insert['android_device_advertising_id'],		\PDO::PARAM_STR);
		$req->bindParam(':android_device_model',				$to_insert['android_device_model'],					\PDO::PARAM_STR);
		$req->bindParam(':android_device_manufacturer',			$to_insert['android_device_manufacturer'],			\PDO::PARAM_STR);
		$req->bindParam(':android_device_language',				$to_insert['android_device_language'],				\PDO::PARAM_STR);
		$req->bindParam(':android_device_display_language',		$to_insert['android_device_display_language'],		\PDO::PARAM_STR);
		$req->bindParam(':android_device_country',				$to_insert['android_device_country'],				\PDO::PARAM_STR);
		$req->bindParam(':android_device_version_sdk',			$to_insert['android_device_version_sdk'],			\PDO::PARAM_STR);
		$req->bindParam(':android_device_timezone',				$to_insert['android_device_timezone'],				\PDO::PARAM_STR);
		$req->bindParam(':android_device_year',					$to_insert['android_device_year'],					\PDO::PARAM_STR);
		$req->bindParam(':android_device_rooted',				$to_insert['android_device_rooted'],				\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function update(Device $device) {

		$to_insert['content'] 								= $device->getContent();
		$to_insert['date_creation'] 						= $device->getDate_creation();
		$to_insert['date_update'] 							= $device->getDate_update();
		$to_insert['visibility'] 							= intval($device->getVisibility());
		$to_insert['public'] 								= intval($device->getPublic());

		if(empty($to_insert['visibility'])) 				$to_insert['visibility'] = 1;
		if(empty($to_insert['public'])) 					$to_insert['public'] = 0;

		$to_insert['operating_system'] 						= $device->getOperating_system();

		$to_insert['android_app_gcm_id'] 					= $device->getAndroid_app_gcm_id();
		$to_insert['android_app_version_code'] 				= $device->getAndroid_app_version_code();
		$to_insert['android_app_version_name'] 				= $device->getAndroid_app_version_name();
		$to_insert['android_app_package'] 					= $device->getAndroid_app_package();

		$to_insert['android_device_id'] 					= $device->getAndroid_device_id();
		$to_insert['android_device_id_u1'] 					= $device->getAndroid_device_id_u1();
		$to_insert['android_device_advertising_id'] 		= $device->getAndroid_device_advertising_id();
		$to_insert['android_device_model'] 					= $device->getAndroid_device_model();
		$to_insert['android_device_manufacturer']			= $device->getAndroid_device_manufacturer();
		$to_insert['android_device_language'] 				= $device->getAndroid_device_language();
		$to_insert['android_device_display_language'] 		= $device->getAndroid_device_display_language();
		$to_insert['android_device_country'] 				= $device->getAndroid_device_country();
		$to_insert['android_device_version_sdk'] 			= $device->getAndroid_device_version_sdk();
		$to_insert['android_device_timezone'] 				= $device->getAndroid_device_timezone();
		$to_insert['android_device_year'] 					= $device->getAndroid_device_year();
		$to_insert['android_device_rooted'] 				= $device->getAndroid_device_rooted();

		$req_str = 'UPDATE `device` SET ';
		$numItems = count($to_insert);
		$i = 0;
		foreach ($to_insert as $key => $value) {
			if(++$i === $numItems) {
				// last index
				$req_str .= $key . " = :" . $key . ' WHERE android_app_gcm_id = :android_app_gcm_id';
			} else {
				$req_str .= $key . " = :" . $key . ', ';
			}
		}
		
		$req = $this->_db->prepare($req_str);

		$req->bindParam(':content',								$to_insert['content'],								\PDO::PARAM_STR);
		$req->bindParam(':date_creation',						$to_insert['date_creation'],						\PDO::PARAM_STR);
		$req->bindParam(':date_update',							$to_insert['date_update'],							\PDO::PARAM_STR);
		$req->bindParam(':visibility',							$to_insert['visibility'],							\PDO::PARAM_INT);
		$req->bindParam(':public',								$to_insert['public'],								\PDO::PARAM_INT);

		$req->bindParam(':operating_system',					$to_insert['operating_system'],						\PDO::PARAM_STR);
		$req->bindParam(':android_app_gcm_id',					$to_insert['android_app_gcm_id'],					\PDO::PARAM_STR);
		$req->bindParam(':android_app_version_code',			$to_insert['android_app_version_code'],				\PDO::PARAM_STR);
		$req->bindParam(':android_app_version_name',			$to_insert['android_app_version_name'],				\PDO::PARAM_STR);
		$req->bindParam(':android_app_package',					$to_insert['android_app_package'],					\PDO::PARAM_STR);

		$req->bindParam(':android_device_id',					$to_insert['android_device_id'],					\PDO::PARAM_STR);
		$req->bindParam(':android_device_id_u1',				$to_insert['android_device_id_u1'],					\PDO::PARAM_STR);
		$req->bindParam(':android_device_advertising_id',		$to_insert['android_device_advertising_id'],		\PDO::PARAM_STR);
		$req->bindParam(':android_device_model',				$to_insert['android_device_model'],					\PDO::PARAM_STR);
		$req->bindParam(':android_device_manufacturer',			$to_insert['android_device_manufacturer'],			\PDO::PARAM_STR);
		$req->bindParam(':android_device_language',				$to_insert['android_device_language'],				\PDO::PARAM_STR);
		$req->bindParam(':android_device_display_language',		$to_insert['android_device_display_language'],		\PDO::PARAM_STR);
		$req->bindParam(':android_device_country',				$to_insert['android_device_country'],				\PDO::PARAM_STR);
		$req->bindParam(':android_device_version_sdk',			$to_insert['android_device_version_sdk'],			\PDO::PARAM_STR);
		$req->bindParam(':android_device_timezone',				$to_insert['android_device_timezone'],				\PDO::PARAM_STR);
		$req->bindParam(':android_device_year',					$to_insert['android_device_year'],					\PDO::PARAM_STR);
		$req->bindParam(':android_device_rooted',				$to_insert['android_device_rooted'],				\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getByIdGcm($android_app_gcm_id) {
		$req = $this->_db->prepare('SELECT id,android_app_gcm_id,date_creation FROM device WHERE android_app_gcm_id = :android_app_gcm_id');
		$req->bindParam(':android_app_gcm_id', $android_app_gcm_id, \PDO::PARAM_STR);
		$req->execute();

		$donnee = $req->fetch(\PDO::FETCH_ASSOC);
		$req->closeCursor();
		return ($donnee['id'] != NULL) ? (new Device($donnee)) : NULL;
	}

	public function getAllDevVersion() {
		$device = [];
		$search = '%.dev';
		$req = $this->_db->prepare('SELECT * FROM device WHERE android_app_version_name LIKE :search AND android_app_gcm_id IS NOT NULL');
		$req->bindParam(':search', $search, \PDO::PARAM_STR);
		$req->execute();

		while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)) {
			$device[] = new Device($donnees);
		}

		$req->closeCursor();
		return $device;
	}

	public function getAll() {
		$device = [];
		$req = $this->_db->prepare('SELECT * FROM device WHERE android_app_gcm_id IS NOT NULL');
		$req->execute();

		while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)) {
			$device[] = new Device($donnees);
		}

		$req->closeCursor();
		return $device;
	}

	public function deleteById($id) {
		$req = $this->_db->prepare('DELETE FROM device WHERE id = :id');
		$req->bindParam(':id', $id, \PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function encryptDistanceCustom($string='', $offset=0, $distance=0, $mod=0) {
		$newstring = '';
		for ($i=0 ; $i < strlen($string) ; $i++) {
			$old = ord($string[$i]);
			$new = $old + (($offset + $i * $distance) % $mod);
			$newstring .= chr($new);
		}
		return $newstring;
	}

	public function decryptDistanceCustom($string='', $offset=0, $distance=0, $mod=0) {
		$newstring = '';
		for ($i=0 ; $i < strlen($string) ; $i++) {
			$old = ord($string[$i]);
			$new = $old - (($offset + $i * $distance) % $mod);
			$newstring .= chr($new);
		}
		return $newstring;
	}
}