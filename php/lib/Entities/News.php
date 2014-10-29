<?php
namespace lib\Entities;

class News extends \lib\Entity{

	const NEWS_PUBLIC = 2,
		NEWS_PROTECTED = 1,
		NEWS_PRIVATE = 0,
		INVALID_TITLE = 3,
		INVALID_DATE = 4,
		INVALID_CONTENT = 5,
		INVALID_THUMB = 6;


	protected $_id,
		$_title,
		$_date_publish,
		$_date_update,
		$_content,
		$_thumb,
		$_status,
		$_id_categorie,
		$_categorie;

	public function getId(){
		return $this->_id;
	}

	public function getTitle(){
		return $this->_title;
	}

	public function getDate_publish(){
		return $this->_date_publish;
	}

	public function getDate_update(){
		return $this->_date_update;
	}

	public function getContent(){
		return $this->_content;
	}

	public function getThumb(){
		return $this->_thumb;
	}

	public function getStatus(){
		return $this->_status;
	}

	public function getId_categorie(){
		return $this->_id_categorie;
	}

	public function getCategorie(){
		return $this->_categorie;
	}

	public function setId($id){
		if(!empty($id)){
			$this->_id = (int) $id;
		}
	}

	public function setTitle($title){
		if(!empty($title) && is_string($title)){
			$this->_title = $title;
		}else{
			$this->_errors[] = self::INVALID_TITLE;
		}
	}

	public function setDate_publish($date){
		if(!empty($date)){
			$this->_date_publish = $date;
		}else{
			$this->_errors[] = self::INVALID_DATE;
		}
	}

	public function setDate_update($date){
		if(!empty($date)){
			$this->_date_update = $date;
		}
	}

	public function setContent($content){
		if(!empty($content) && is_string($content)){
			$this->_content = $content;
		}else{
			$this->_errors[] = self::INVALID_CONTENT;
		}
	}

	public function setThumb($thumb){
		if(!empty($thumb) && is_string($thumb)){
			$this->_thumb = $thumb;
		}else{
			$this->_errors[] = self::INVALID_THUMB;
		}
	}

	public function setStatus($status = self::NEWS_PRIVATE){
		$this->_status = (int) $status;
	}

	public function setId_categorie($categorie){
		$this->_id_categorie = (int) $categorie;
	}

	public function setCategorie($categorie){
		if(!empty($categorie) && is_string($categorie)){
			$this->_categorie = $categorie;
		}
	}

	public function format_date($date_integer){
		$hour = $date_integer%100;
		$day = (($date_integer-$hour)/100)%100;
		$month = (($date_integer-$day*100-$hour)/10000)%100;
		$year = (($date_integer-$month*10000-$day*100-$hour)/1000000)%100;
		return $day.'/'.$month.'/'.$year;
	}

	public function isValid(){
		return !empty($this->_title) && !empty($this->_content) && !empty($this->_id_categorie) && !empty($this->_date_publish);
	}
}
