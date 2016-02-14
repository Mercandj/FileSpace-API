<?php
namespace lib\Entities;

class SupportComment extends \lib\Entity {

	protected $_id,
	$_id_device,
	$_is_dev_response,
	$_content,
	$_date_creation,
	$_visibility,
	$_public,
	$_android_app_version_code,
	$_android_app_version_name,
	$_android_app_notification_id,
	$_android_device_version_sdk,
	$_android_device_model,
	$_android_device_manufacturer,
	$_android_device_display_language,
	$_android_device_country,
	$_nb_comments_with_this_id_device;





	public function getId() {
		return $this->_id;
	}
	public function getId_device() {
		return $this->_id_device;
	}
	public function getIs_dev_response() {
		return $this->_is_dev_response;
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
	public function getAndroid_app_version_code() {
		return $this->_android_app_version_code;
	}
	public function getAndroid_app_version_name() {
		return $this->_android_app_version_name;
	}
	public function getAndroid_app_notification_id() {
		return $this->_android_app_notification_id;
	}

	public function getAndroid_device_version_sdk() {
		return $this->_android_device_version_sdk;
	}
	public function getAndroid_device_model() {
		return $this->_android_device_model;
	}
	public function getAndroid_device_manufacturer() {
		return $this->_android_device_manufacturer;
	}
	public function getAndroid_device_display_language() {
		return $this->_android_device_display_language;
	}
	public function getAndroid_device_country() {
		return $this->_android_device_country;
	}

	public function getNb_comments_with_this_id_device() {
		return $this->_nb_comments_with_this_id_device;
	}






	public function setId($id){
		if(!empty($id))
			$this->_id = $id;
	}
	public function setId_device($id_device) {
		if(!empty($id_device))
			$this->_id_device = $id_device;
	}
	public function setIs_dev_response($is_dev_response) {
		if(!empty($is_dev_response))
			$this->_is_dev_response = $is_dev_response;
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

	public function setAndroid_app_version_code($android_app_version_code) {
		if(!empty($android_app_version_code))
			$this->_android_app_version_code = $android_app_version_code;
	}
	public function setAndroid_app_version_name($android_app_version_name) {
		if(!empty($android_app_version_name))
			$this->_android_app_version_name = $android_app_version_name;
	}
	public function setAndroid_app_notification_id($android_app_notification_id) {
		if(!empty($android_app_notification_id))
			$this->_android_app_notification_id = $android_app_notification_id;
	}

	public function setAndroid_device_version_sdk($android_device_version_sdk) {
		if(!empty($android_device_version_sdk))
			$this->_android_device_version_sdk = $android_device_version_sdk;
	}
	public function setAndroid_device_model($android_device_model) {
		if(!empty($android_device_model))
			$this->_android_device_model = $android_device_model;
	}
	public function setAndroid_device_manufacturer($android_device_manufacturer) {
		if(!empty($android_device_manufacturer))
			$this->_android_device_manufacturer = $android_device_manufacturer;
	}
	public function setAndroid_device_display_language($android_device_display_language) {
		if(!empty($android_device_display_language))
			$this->_android_device_display_language = $android_device_display_language;
	}
	public function setAndroid_device_country($android_device_country) {
		if(!empty($android_device_country))
			$this->_android_device_country = $android_device_country;
	}

	public function setNb_comments_with_this_id_device($nb_comments_with_this_id_device) {
		if(!empty($nb_comments_with_this_id_device))
			$this->_nb_comments_with_this_id_device = $nb_comments_with_this_id_device;
	}





	public function isValid() {
		return !empty($this->_id) && !empty($this->_id_user);
	}
	public function toArray() {
		$json['id'] = $this->getId();
		if($this->getId_device()!=null) {
			$json['id_device'] = $this->getId_device();
		}
		if($this->getIs_dev_response()!=null) {
			$json['is_dev_response'] = filter_var($this->getIs_dev_response(), FILTER_VALIDATE_BOOLEAN);
		}
		if($this->getContent()!=null)
			$json['content'] = $this->getContent();
		if($this->getDate_creation()!=null)
			$json['date_creation'] = $this->getDate_creation();
		if($this->getVisibility()!=null)
			$json['visibility'] = $this->getVisibility();
		if($this->getPublic()!=null)
			$json['public'] = $this->getPublic();

		if($this->getAndroid_app_version_code()!=null)
			$json['android_app_version_code'] = $this->getAndroid_app_version_code();
		if($this->getAndroid_app_version_name()!=null)
			$json['android_app_version_name'] = $this->getAndroid_app_version_name();
		if($this->getAndroid_app_notification_id()!=null)
			$json['android_app_notification_id'] = $this->getAndroid_app_notification_id();

		if($this->getAndroid_device_version_sdk()!=null)
			$json['android_device_version_sdk'] = $this->getAndroid_device_version_sdk();
		if($this->getAndroid_device_model()!=null)
			$json['android_device_model'] = $this->getAndroid_device_model();
		if($this->getAndroid_device_manufacturer()!=null)
			$json['android_device_manufacturer'] = $this->getAndroid_device_manufacturer();
		if($this->getAndroid_device_display_language()!=null)
			$json['android_device_display_language'] = $this->getAndroid_device_display_language();
		if($this->getAndroid_device_country()!=null)
			$json['android_device_country'] = $this->getAndroid_device_country();

		if($this->getNb_comments_with_this_id_device()!=null)
			$json['nb_comments_with_this_id_device'] = $this->getNb_comments_with_this_id_device();
		return $json;
	}
}