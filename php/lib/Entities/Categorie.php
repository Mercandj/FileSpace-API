<?php
namespace lib\Entities;

class Categorie extends \lib\Entity{

	const INVALID_TITLE = 1,
		INVALID_DETAILS = 2;

	protected $_id,
		$_title,
		$_details,
		$_thumb;

	public function getId(){
		return $this->_id;
	}

	public function getTitle(){
		return $this->_title;
	}

	public function getDetails(){
		return $this->_details;
	}

	public function getThumb(){
		return $this->_thumb;
	}

	public function setId($id){
		$this->_id = (int) $id;
	}

	public function setTitle($title){
		if(!empty($title) && is_string($title)){
			$this->_title = $title;
		}else{
			$this->_errors[] = self::INVALID_TITLE;
		}
	}

	public function setDetails($details){
		if(!empty($details) && is_string($details)){
			$this->_details = $details;
		}else{
			$this->_errors[] = self::INVALID_DETAILS;
		}
	}

	public function setThumb($thumb){
		if(!empty($thumb) && is_string($thumb)){
			$this->_thumb = $thumb;
		}
	}

	public function isValid(){
		return !empty($this->_title) && !empty($this->_details);
	}
}