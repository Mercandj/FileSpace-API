<?php
namespace lib\Entities;

class Conversation extends \lib\Entity{

	protected $_id,
		$_id_user,
		$_content,
		$_date_creation,
		$_visibility,
		$_public,
		$_to_all,
		$_to_yourself;


	public function getId() {
		return $this->_id;
	}
	public function getId_user() {
		return $this->_id_user;
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
	public function getTo_all() {
		return $this->_to_all;
	}
	public function getTo_yourself() {
		return $this->_to_yourself;
	}


	public function setId($id) {
		if(!empty($id))
			$this->_id = $id;
	}
	public function setId_user($id_user) {
		if(!empty($id_user))
			$this->_id_user = $id_user;
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
	public function setTo_all($to_all) {
		if(!empty($to_all))
			$this->_to_all = $to_all;
	}
	public function setTo_yourself($to_yourself) {
		if(!empty($to_yourself))
			$this->_to_yourself = $to_yourself;
	}


	public function isValid() {
		return !empty($this->_id) && !empty($this->_id_user);
	}
	public function toArray() {
		$json['id'] = $this->getId();
		if($this->getId_user()!=null)
			$json['id_user'] = $this->getId_user();
		if($this->getContent()!=null)
			$json['content'] = $this->getContent();
		if($this->getDate_creation()!=null)
			$json['date_creation'] = $this->getDate_creation();
		if($this->getVisibility()!=null)
			$json['visibility'] = $this->getVisibility();
		if($this->getPublic()!=null)
			$json['public'] = $this->getPublic();
		if($this->getTo_all()!=null)
			$json['to_all'] = $this->getTo_all();
		if($this->getTo_yourself()!=null)
			$json['to_yourself'] = $this->getTo_yourself();
        return $json;
    }
}