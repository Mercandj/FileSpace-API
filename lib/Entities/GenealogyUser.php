<?php
namespace lib\Entities;

class GenealogyUser extends \lib\Entity{

	protected $_id,
		$_first_name_1,
		$_first_name_2,
		$_first_name_3,
		$_last_name,
		$_date_creation,
		$_content,
		$_id_father,
		$_id_mother,
		$_date_death,
		$_date_birth,
		$_is_man,
		$_profession;

	public function getId(){
		return $this->_id;
	}

	public function getFirst_name_1(){
		return $this->_first_name_1;
	}

	public function getFirst_name_2(){
		return $this->_first_name_2;
	}

	public function getFirst_name_3(){
		return $this->_first_name_3;
	}

	public function getLast_name(){
		return $this->_last_name;
	}

	public function getDate_creation(){
		return $this->_date_creation;
	}

	public function getContent(){
		return $this->_content;
	}

	public function getId_father(){
		return $this->_id_father;
	}

	public function getId_mother(){
		return $this->_id_mother;
	}

	public function getDate_death(){
		return $this->_date_death;
	}

	public function getDate_birth(){
		return $this->_date_birth;
	}

	public function getIs_man(){
		return $this->_is_man;
	}

	public function setId($id){
		if(!empty($id)){
			$this->_id = $id;
		}
	}

	public function setFirst_name_1($first_name_1){
		if(!empty($first_name_1)){
			$this->_first_name_1 = $first_name_1;
		}
	}

	public function setFirst_name_2($first_name_2){
		if(!empty($first_name_2)){
			$this->_first_name_2 = $first_name_2;
		}
	}

	public function setFirst_name_3($first_name_3){
		if(!empty($first_name_3)){
			$this->_first_name_3 = $first_name_3;
		}
	}

	public function setLast_name($last_name){
		if(!empty($last_name)){
			$this->_last_name = $last_name;
		}
	}

	public function setDate_creation($date){
		if(!empty($date)){
			$this->_date_creation = $date;
		}
	}

	public function setContent($content){
		if(!empty($content)){
			$this->_content = $content;
		}
	}

	public function setId_father($id_father){
		if(!empty($id_father)){
			$this->_id_father = $id_father;
		}
	}

	public function setId_mother($id_mother){
		if(!empty($id_mother)){
			$this->_id_mother = $id_mother;
		}
	}

	public function setDate_death($date_death){
		if(!empty($date_death)){
			$this->_date_death = $date_death;
		}
		return $this->_date_death;
	}

	public function setDate_birth($date_death){
		if(!empty($date_birth)){
			$this->_date_birth = $date_birth;
		}
		return $this->_date_birth;
	}

	public function setIs_man($is_man){
		if(!empty($is_man)){
			$this->_is_man = $is_man;
		}
		return $this->_is_man;
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
		if($this->getContent()!=null)
			$json['content'] = $this->getContent();
		if($this->getId_father()!=null)
			$json['id_father'] = $this->getId_father();
		if($this->getId_mother()!=null)
			$json['id_mother'] = $this->getId_mother();
		if($this->getDate_death()!=null)
			$json['date_death'] = $this->getDate_death();
		if($this->getDate_birth()!=null)
			$json['date_birth'] = $this->getDate_birth();
		if($this->getIs_man()!=null)
			$json['is_man'] = $this->getIs_man();
        return $json;
    }
}