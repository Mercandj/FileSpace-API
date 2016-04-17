<?php
namespace lib\Entities;

class Device extends \lib\Entity {

	protected $_id,
	$_content,
	$_date_creation,
	$_visibility,
	$_public,
	$_operating_system,
	$_android_app_gcm_id,
	$_android_app_version_code,
	$_android_app_version_name,
	$_android_device_display_language,
	$_android_device_country,
	$_android_device_version_sdk;


	public function getId() {
		return $this->_id;
	}
	public function getContent() {
		return $this->_content;
	}
	public function getDate_creation() {
		return $this->_date_creation;
	}
	public function getVisibility() {
		return $this->_visibility;
	}
	public function getPublic() {
		return $this->_public;
	}
	public function getOperating_system() {
		return $this->_operating_system;
	}
	public function getAndroid_app_gcm_id() {
		return $this->_android_app_gcm_id;
	}
	public function getAndroid_app_version_code() {
		return $this->_android_app_version_code;
	}
	public function getAndroid_app_version_name() {
		return $this->_android_app_version_name;
	}
	public function getAndroid_device_display_language() {
		return $this->_android_device_display_language;
	}
	public function getAndroid_device_country() {
		return $this->_android_device_country;
	}
	public function getAndroid_device_version_sdk() {
		return $this->_android_device_version_sdk;
	}


	public function setId($id) {
		if(!empty($id))
			$this->_id = $id;
	}
	public function setContent($content) {
		if(!empty($content))
			$this->_content = $content;
	}
	public function setDate_creation($date) {
		if(!empty($date))
			$this->_date_creation = $date;
	}
	public function setVisibility($visibility) {
		if(!empty($visibility))
			$this->_visibility = $visibility;
	}
	public function setPublic($public) {
		if(!empty($public))
			$this->_public = $public;
	}
	public function setOperating_system($operating_system) {
		if(!empty($operating_system))
			$this->_operating_system = $operating_system;
	}
	public function setAndroid_app_gcm_id($android_app_gcm_id) {
		if(!empty($android_app_gcm_id))
			$this->_android_app_gcm_id = $android_app_gcm_id;
	}
	public function setAndroid_app_version_code($android_app_version_code) {
		if(!empty($android_app_version_code))
			$this->_android_app_version_code = $android_app_version_code;
	}
	public function setAndroid_app_version_name($android_app_version_name) {
		if(!empty($android_app_version_name))
			$this->_android_app_version_name = $android_app_version_name;
	}
	public function setAndroid_device_display_language($android_device_display_language) {
		if(!empty($android_device_display_language))
			$this->_android_device_display_language = $android_device_display_language;
	}
	public function setAndroid_device_country($android_device_country) {
		if(!empty($android_device_country))
			$this->_android_device_country = $android_device_country;
	}
	public function setAndroid_device_version_sdk($android_device_version_sdk) {
		if(!empty($android_device_version_sdk))
			$this->_android_device_version_sdk = $android_device_version_sdk;
	}


	public function isValid() {
		return !empty($this->_id) && !empty($this->_id_user);
	}
	public function toArray() {
		$json['id'] = $this->getId();
		if($this->getContent()!=null)
			$json['content'] = $this->getContent();
		if($this->getDate_creation()!=null)
			$json['date_creation'] = $this->getDate_creation();
		if($this->getVisibility()!=null)
			$json['visibility'] = $this->getVisibility();
		if($this->getPublic()!=null)
			$json['public'] = $this->getPublic();

		if($this->getOperating_system()!=null)
			$json['operating_system'] = $this->getOperating_system();
		if($this->getAndroid_app_gcm_id()!=null)
			$json['android_app_gcm_id'] = $this->getAndroid_app_gcm_id();
		if($this->getAndroid_app_version_code()!=null)
			$json['android_app_version_code'] = $this->getAndroid_app_version_code();
		if($this->getAndroid_app_version_name()!=null)
			$json['android_app_version_name'] = $this->getAndroid_app_version_name();
		if($this->getAndroid_device_display_language()!=null)
			$json['android_device_display_language'] = $this->getAndroid_device_display_language();
		if($this->getAndroid_device_country()!=null)
			$json['android_device_country'] = $this->getAndroid_device_country();
		if($this->getAndroid_device_version_sdk()!=null)
			$json['android_device_version_sdk'] = $this->getAndroid_device_version_sdk();
		return $json;
	}
}