<?php
namespace lib\Entities;

class GenealogyUser extends \lib\Entity{

	protected $_id,
		$_first_name_1,
		$_first_name_2,
		$_first_name_3,
		$_last_name;

	public function getId(){
		return $this->_id;
	}

	public function getFirst_name_1(){
		return $this->_first_name_1;
	}

	public function getLast_name(){
		return $this->_last_name;
	}

	public function getDate_creation(){
		return $this->_date_creation;
	}

	public function setId($id){
		if(!empty($id)){
			$this->_id = $id;
		}
	}

	public function setFirst_name_1($first_name_1){
		if(!empty($first_name_1)){
			$this->_first_name_1 = $first_name_1;
		}else{
			// Well ...
		}
	}

	public function setLast_name($last_name){
		if(!empty($last_name)){
			$this->_last_name = $last_name;
		}else{
			// Well ...
		}
	}

	public function setDate_creation($date){
		if(!empty($date)){
			$this->_date_creation = $date;
		}
	}

	public function isValid(){
		return true;
	}

	public function toArray() {
		$json['id'] = $this->getId();
		if($this->getFirst_name_1()!=null)
			$json['first_name_1'] = $this->getFirst_name_1();
		if($this->getFirst_name_2()!=null)
			$json['first_name_2'] = $this->getFirst_name_2();
		if($this->getFirst_name_3()!=null)
			$json['first_name_3'] = $this->getFirst_name_3();
		if($this->getLast_name()!=null)
			$json['last_name'] = $this->getLast_name();
		if($this->getDate_creation()!=null)
			$json['date_creation'] = $this->getDate_creation();
        return $json;
    }
}