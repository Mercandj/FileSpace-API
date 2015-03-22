<?php
namespace lib\Entities;

class File extends \lib\Entity{

	const INVALID_URL = 1,
		INVALID_SIZE = 2,
		INVALID_VISIBILITY = 3;

	protected $_id,
		$_url,
		$_name,
		$_size,
		$_visibility,
		$_date_creation,
		$_id_user,
		$_type,
		$_directory,
		$_content;

	public function getId() {
		return $this->_id;
	}

	public function getUrl() {
		return $this->_url;
	}

	public function getName() {
		return $this->_name;
	}

	public function getSize() {
		return $this->_size;
	}

	public function getVisibility() {
		return $this->_visibility;
	}

	public function getDate_creation() {
		return $this->_date_creation;
	}

	public function getId_User() {
		return $this->_id_user;
	}

	public function getType() {
		return $this->_type;
	}

	public function getDirectory() {
		return $this->_directory;
	}

	public function getContent() {
		return $this->_content;
	}

	public function setId($id) {
		if(!empty($id))
			$this->_id = $id;
	}

	public function setUrl($url) {
		if(!empty($url))
			$this->_url = $url;
		else
			$this->_errors[] = self::INVALID_URL;
	}

	public function setName($name) {
		if(!empty($name))
			$this->_name = $name;
		else
			$this->_errors[] = self::INVALID_URL;
	}

	public function setSize($size) {
		if(!empty($size))
			$this->_size = $size;
		else
			$this->_errors[] = self::INVALID_SIZE;
	}

	public function setVisibility($visibility) {
		if(!empty($visibility))
			$this->_visibility = $visibility;
		else
			$this->_errors[] = self::INVALID_VISIBILITY;
	}

	public function setDate_creation($date) {
		if(!empty($date))
			$this->_date_creation = $date;
	}

	public function setId_user($id) {
		if(!empty($id))
			$this->_id_user = $id;
	}

	public function setType($type) {
		if(!empty($type))
			$this->_type = $type;
	}

	public function setDirectory($directory) {
		if(!empty($directory))
			$this->_directory = $directory;
	}

	public function setContent($content) {
		if(!empty($content))
			$this->_content = $content;
	}

	public function isValid() {
		return !empty($this->_id) && !empty($this->_url);
	}

    public function toArray() {
		$json['id'] = $this->getId();
		$json['name'] = $this->getName();
		$json['url'] = $this->getUrl();
		$json['size'] = $this->getSize();
		$json['date_creation'] = $this->getDate_creation();
		$json['type'] = $this->getType();
		if($this->getContent()!=null)
			$json['content'] = $this->getContent();
		if($this->getDirectory()!=null)
			$json['directory'] = $this->getDirectory();
        return $json;
    }
}