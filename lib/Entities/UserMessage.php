<?php
namespace lib\Entities;

class UserMessage extends \lib\Entity{

	protected $_id,
		$_id_user,
		$_id_user_recipient,
		$_id_user_group_type_recipient,
		$_content,
		$_date_creation,
		$_visibility,
		$_public;


	public function getId() {
		return $this->_id;
	}
	public function getId_user() {
		return $this->_id_user;
	}
	public function getId_user_recipient() {
		return $this->_id_user_recipient;
	}
	public function getId_user_group_type_recipient() {
		return $this->_id_user_group_type_recipient;
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
		if(!empty($id)){
			$this->_id = $id;
		}
	}
	public function setId_user($id_user) {
		if(!empty($id_user)){
			$this->_id_user = $id_user;
		}
	}
	public function setId_user_recipient($id_user_recipient) {
		if(!empty($id_user_recipient)){
			$this->_id_user_recipient = $id_user_recipient;
		}
	}
	public function setId_user_group_type_recipient($id_user_group_type_recipient) {
		if(!empty($id_user_group_type_recipient)){
			$this->_id_user_group_type_recipient = $id_user_group_type_recipient;
		}
	}
	public function setContent($content) {
		if(!empty($content)){
			$this->_content = $content;
		}
	}
	public function setDate_creation($date) {
		if(!empty($date)){
			$this->_date_creation = $date;
		}
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
		if($this->getId_user()!=null)
			$json['id_user'] = $this->getId_user();
		if($this->getId_user_recipient()!=null)
			$json['id_user_recipient'] = $this->getId_user_recipient();
		if($this->getId_user_group_type_recipient()!=null)
			$json['id_user_group_type_recipient'] = $this->getId_user_group_type_recipient();		
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