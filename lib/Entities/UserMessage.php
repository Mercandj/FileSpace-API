<?php
namespace lib\Entities;

class UserMessage extends \lib\Entity{

	protected $_id,
		$_id_user,
		$_id_user_recipient,
		$_content,
		$_date_creation;

	public function getId(){
		return $this->_id;
	}

	public function getId_user(){
		return $this->_id_user;
	}

	public function getId_user_recipient(){
		return $this->_id_user_recipient;
	}

	public function getContent(){
		return $this->_content;
	}

	public function getDate_creation(){
		return $this->_date_creation;
	}

	public function setId($id){
		if(!empty($id)){
			$this->_id = $id;
		}
	}

	public function setId_user_recipient($id_user_recipient){
		if(!empty($id_user_recipient)){
			$this->_id_user_recipient = $id_user_recipient;
		}
	}

	public function setContent($content){
		if(!empty($content)){
			$this->_content = $content;
		}
	}

	public function setDate_creation($date){
		if(!empty($date)){
			$this->_date_creation = $date;
		}
	}

	public function toArray() {
		$json['id'] = $this->getId();
		if($this->getId_user()!=null)
			$json['id_user'] = $this->getId_user();
		if($this->getId_user_recipient()!=null)
			$json['id_user_recipient'] = $this->getId_user_recipient();
		if($this->getContent()!=null)
			$json['content'] = $this->getContent();
		if($this->getDate_creation()!=null)
			$json['date_creation'] = $this->getDate_creation();
        return $json;
    }
}