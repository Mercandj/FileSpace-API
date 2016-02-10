<?php
namespace app\controller;
use \lib\Entities\SupportComment;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class SupportController extends \lib\Controller {

/**
* @uri    /support/comment
* @method GET
* @return JSON with info about server Info
*/
public function commentGet() {
	$json['succeed'] = true;

	$id_device = '';
	if(HTTPRequest::getExist('id_device')) {
		$id_device = HTTPRequest::getData('id_device');
	} else {
		$json['succeed'] = false;
	}

	$supportManager = $this->getManagerof('Support');
	$list_comment = $supportManager->getAllByIdDevice($id_device);
	$result = [];
	foreach ($list_comment as $comment) {
		$comment_array = $comment->toArray();
		$result[] = $comment_array;
	}

	$json['result'] = $result;
	$json['debug'] = '$id_device:' . $id_device;

	HTTPResponse::send(json_encode($json));
}

/**
* @uri    /support/comment
* @method POST
* @return JSON with info about server Info
*/
public function commentPost() {
	$json['succeed'] = true;
	
	$id_device = '';
	if(HTTPRequest::postExist('id_device')) {
		$id_device = HTTPRequest::postData('id_device');
	} else {
		$json['succeed'] = false;
	}

	$content = '';
	if(HTTPRequest::postExist('content')) {
		$content = HTTPRequest::postData('content');
	} else {
		$json['succeed'] = false;
	}

	$is_dev_response = 0;
	if(HTTPRequest::postExist('is_dev_response')) {
		$is_dev_response = boolval(HTTPRequest::postData('is_dev_response')) ? 1 : 0;
	}

	$supportManager = $this->getManagerof('Support');

	$supportComment = new SupportComment(array(
				'id'=> 0,
				'id_device' => $id_device,
				'is_dev_response' => $is_dev_response,
				'content' => $content,
				'date_creation' => date('Y-m-d H:i:s')
			));
	$supportManager->add($supportComment);

	$list_comment = $supportManager->getAllByIdDevice($id_device);
	$result = [];
	foreach ($list_comment as $comment) {
		$comment_array = $comment->toArray();
		$result[] = $comment_array;
	}

	$json['result'] = $result;
	$json['debug'] = '$id_device:' . $id_device . ' $content:' . $content . ' $is_dev_response:' . $is_dev_response;

	HTTPResponse::send(json_encode($json));
}
}