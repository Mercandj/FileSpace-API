<?php
namespace lib;

class Markdown{

	/**
	 * Transform a markdown text into html
	 * @param  String $text 
	 * @return String   
	 */
	public static function text($text){
		$parsed ='';
		$tokens = self::getToken($text);
		
		foreach($tokens as $token){
			$parsed .= self::toHtml($token);
		}
		
		return $parsed;
	}

	/**
	 * Split a text into tokens
	 * @param  String $content 
	 * @return String          
	 */
	private static function getToken($content){

		$content =  str_replace("\r\n",'/n', $content);
		$content = preg_replace('#\/n\*#', '*', $content);
		return explode('/n',$content);
	}

	/**
	 * Transform a markdown token into html  
	 * @param  String $token 
	 * @return String
	 */
	private static function toHtml($token){

		// if token null
		if($token === ''){
			return '';
		}			

		else if(preg_match('#^(\#|!|\*|&gt;)#',$token, $matches)){

			switch($matches[0]){
				case '#':
					return preg_replace('#\# (.+)#', '<h3>$1</h3>',$token);

				case "!":

					//Picture
					$token = preg_replace('#!\[(.+)\]\(([0-9a-zA-Z.,_\/-]+)\)#', '<a href="/public/uploads/Images/$2"><img src="/public/uploads/Images/$2" alt="$1"></a>',$token);
					
					//table
					$token = str_replace("!!t", "</div><div>",$token);
					$token = str_replace("!t", "<div><div>",$token);
					$token = str_replace("!:t", "</div></div>",$token);

					return $token;

				case "&gt;":
					return preg_replace('#&gt; (.+)#', '<blockquote>$1</blockquote>',$token);

				case "*":
					$token = preg_replace('#\* ([\w\(\)\s\!\/\'àéêèîôùû\:ç$€\.,\+-]+)#', '<li>$1</li>',$token);
					return '<ul>'.$token.'</ul>';

				default:
					return '<p></p>';
			}
		}else{
			//link
			$token = preg_replace('#\[(.+)\]\((.+)\)#isU', '<a href="$2">$1</a>',$token);

			//Bold
			$token = preg_replace('#\*\*(.+)\*\*#isU', '<strong>$1</strong>',$token);

			//Italic
			$token = preg_replace('#\*(.+)\*#isU', '<em>$1</em>',$token);

			//Line-through
			$token = preg_replace('#~~(.+)~~#isU', '<del>$1</del>',$token);

			return '<p>'.$token.'</p>';
		}
	}
}