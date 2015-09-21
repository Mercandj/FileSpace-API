<?php
namespace lib\Entities;

class GenealogyMarriage extends \lib\Entity{

	protected $_id,
		$_id_person_husband,
		$_id_person_wife,
		$_date,
		$_location,
		$_visibility,
		$_public,
		$_date_creation,
		$_content,
		$_description;

	public function getId(){
		return $this->_id;
	}

	public function getId_person_husband(){
		return $this->_id_person_husband;
	}

	public function getId_person_wife(){
		return $this->_id_person_wife;
	}

	public function getDate(){
		return $this->_date;
	}

	public function getLocation(){
		return $this->_location;
	}

	public function getVisibility(){
		return $this->_visibility;
	}

	public function getPublic(){
		return $this->_public;
	}

	public function getDate_creation(){
		return $this->_date_creation;
	}

	public function getContent(){
		return $this->_content;
	}

	public function getDescription(){
		return $this->_description;
	}

	public function setId($id){
		if(!empty($id)){
			$this->_id = $id;
		}
	}

	public function setId_person_husband($id_person_husband){
		if(!empty($id_person_husband)){
			$this->_id_person_husband = $id_person_husband;
		}
	}

	public function setId_person_wife($id_person_wife){
		if(!empty($id_person_wife)){
			$this->_id_person_wife = $id_person_wife;
		}
	}

	public function setDate($date){
		if(!empty($date)){
			$this->_date = $date;
		}
	}

	public function setLocation($location){
		if(!empty($location)){
			$this->_location = $location;
		}
	}

	public function setDate_creation($date_creation){
		if(!empty($date_creation)){
			$this->_date_creation = $date_creation;
		}
	}

	public function setContent($content){
		if(!empty($content)){
			$this->_content = $content;
		}
	}

	public function setDescription($description){
		if(!empty($description)){
			$this->_description = $description;
		}
		return $this->_description;
	}	

	public function isValid(){
		return true;
	}

	public function toArray() {
		$json['id'] = $this->getId();
		if($this->getId_person_husband()!=null)
			$json['id_person_husband'] = $this->getId_person_husband();
		if($this->getId_person_wife()!=null)
			$json['id_person_wife'] = $this->getId_person_wife();
		if($this->getDate()!=null)
			$json['date'] = $this->getDate();
		if($this->getLocation()!=null)
			$json['location'] = $this->getLocation();
		if($this->getDate_creation()!=null)
			$json['date_creation'] = $this->getDate_creation();
		if($this->getContent()!=null)
			$json['content'] = $this->getContent();
		if($this->getDescription()!=null)
			$json['description'] = $this->getDescription();
        return $json;
    }
}