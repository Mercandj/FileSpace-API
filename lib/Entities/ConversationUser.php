<?php
namespace lib\Entities;

class ConversationUser extends \lib\Entity{

	protected $_id,
		$_id_user,
		$_id_conversation,
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
	public function getId_conversation() {
		return $this->_id_conversation;
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
	public function setId_user($id_user) {
		if(!empty($id_user))
			$this->_id_user = $id_user;
	}
	public function setId_conversation($id_conversation) {
		if(!empty($id_conversation))
			$this->_id_conversation = $id_conversation;
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
		if($this->getId_user()!=null)
			$json['id_user'] = $this->getId_user();
		if($this->getId_conversation()!=null)
			$json['id_conversation'] = $this->getId_conversation();		
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