<?php
namespace lib\Entities;

class PushDevice extends \lib\Entity {

	protected $_id,
	$_id_gcm,
	$_content,
	$_date_creation,
	$_visibility,
	$_public;



	public function getId() {
		return $this->_id;
	}
	public function getId_gcm() {
		return $this->_id_gcm;
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



	public function setId($id){
		if(!empty($id))
			$this->_id = $id;
	}
	public function setId_gcm($id_gcm) {
		if(!empty($id_gcm))
			$this->_id_gcm = $id_gcm;
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




	public function isValid() {
		return !empty($this->_id) && !empty($this->_id_user);
	}
	public function toArray() {
		$json['id'] = $this->getId();
		if($this->getId_gcm()!=null) {
			$json['id_gcm'] = $this->getId_gcm();
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
		return $json;
	}
}