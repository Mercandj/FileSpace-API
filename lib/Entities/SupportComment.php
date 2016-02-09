<?php
namespace lib\Entities;

class SupportComment extends \lib\Entity {

	protected $_id,
	$_id_device,
	$_content,
	$_date_creation,
	$_visibility,
	$_public;


	public function getId() {
		return $this->_id;
	}
	public function getId_device() {
		return $this->_id_device;
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


	public function setId($id){
		if(!empty($id))
			$this->_id = $id;
	}
	public function setId_device($id_device) {
		if(!empty($id_device))
			$this->_id_device = $id_device;
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


	public function isValid() {
		return !empty($this->_id) && !empty($this->_id_user);
	}
	public function toArray() {
		$json['id'] = $this->getId();
		if($this->getId_device()!=null) {
			$json['id_device'] = $this->getId_device();
		}	
		if($this->getContent()!=null)
			$json['content'] = $this->getContent();
		if($this->getDate_creation()!=null)
			$json['date_creation'] = $this->getDate_creation();
		if($this->getVisibility()!=null)
			$json['visibility'] = $this->getVisibility();
		if($this->getPublic()!=null)
			$json['public'] = $this->getPublic();
		return $json;
	}
}