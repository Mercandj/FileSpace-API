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
	$_android_device_version_sdk,
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
	public function getAndroid_device_version_sdk() {
		return $this->_android_device_version_sdk;
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
	public function setAndroid_device_version_sdk($android_device_version_sdk) {
		if(!empty($android_device_version_sdk))
			$this->_android_device_version_sdk = $android_device_version_sdk;
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
			$json['is_dev_response'] = filter_var(HTTPRequest::postData($this->getIs_dev_response()), FILTER_VALIDATE_BOOLEAN);
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
		if($this->getAndroid_device_version_sdk()!=null)
			$json['android_device_version_sdk'] = $this->getAndroid_device_version_sdk();
		if($this->getNb_comments_with_this_id_device()!=null)
			$json['nb_comments_with_this_id_device'] = $this->getNb_comments_with_this_id_device();
		return $json;
	}
}