<?php
require_once dirname(__FILE__).'/utils.php';

class ActiveRequest
{
	/*static*/ function method()
	{
		return ActiveUtils::strDefault(
			ActiveUtils::mapGet($_GET, 'x-rest-method'),
			ActiveUtils::mapGet($_SERVER, 'REQUEST_METHOD'),
			'GET'
		);
	}

	/*static*/ function script()
	{
		$scriptName = ActiveUtils::mapGet($_SERVER, 'SCRIPT_NAME', '');
		$script = substr(ActiveRequest::uri(), 0, strlen($scriptName));
		
		if( $script != $scriptName )
		{
			// SCRIPT_NAME may contains the extension when it should not
			// Remove it
			return ActiveUtils::substrBefore($scriptName, '.');
		}
		return $script;
	}

	/*static*/ function scriptDir()
	{
		return rtrim(dirname(ActiveRequest::script()), '/');
	}

	/*static*/ function uri()
	{
		return ActiveUtils::mapGet($_SERVER, 'REQUEST_URI', '');
	}
	
	/*static*/ function relative($uri)
	{
		return ActiveRequest::scriptDir().'/'.$uri;
	}

	/*static*/ function pathInfo()
	{
		$requestUri = ActiveRequest::uri();
		$script = ActiveRequest::script();
		$pathInfo = substr($requestUri, strlen($script));
		
		$pathInfo = ActiveUtils::escapeMagicQuotes($pathInfo);
		$pathInfo = ActiveUtils::substrBefore($pathInfo, '?');
		$pathInfo = urldecode($pathInfo);
		$pathInfo = trim($pathInfo, '/');
		return $pathInfo;
	}
	
	/*static*/ function query()
	{
		$queryString = ActiveUtils::substrAfter(ActiveRequest::uri(), '?');
		$query = array();
		parse_str($queryString, $query);
		return $query;
	}

	/*static*/ function input()
	{
		return file_get_contents('php://input');
	}
	
	/*static*/ function user()
	{
		return ActiveUtils::mapGet($_SERVER, 'PHP_AUTH_USER');
	}

	/*static*/ function password()
	{
		return ActiveUtils::mapGet($_SERVER, 'PHP_AUTH_PW');
	}
	
	/*static*/ function types()
	{
		return ActiveRequest::_parseAccept('HTTP_ACCEPT');
	}
	
	/*static*/ function languages()
	{
		return ActiveRequest::_parseAccept('HTTP_ACCEPT_LANGUAGE');
	}
	
	/*static*/ function encodings()
	{
		return ActiveRequest::_parseAccept('HTTP_ACCEPT_ENCODING');
	}
	
	/*static*/ function charsets()
	{
		return ActiveRequest::_parseAccept('HTTP_ACCEPT_CHARSET');
	}
	
	/*private*/
	
	/*static*/ function _parseAccept($key)
	{
		$acceptString = ActiveUtils::mapGet($_SERVER, $key, '');
		preg_match_all('$([\-/+*A-Za-z0-9]+(?:,[\-/+*a-z0-9]+)*)(?:;q=([0-9.]+))?$', $acceptString, $matches);
		
		$accept = array();
		foreach( $matches[2] as $index => $priority ) 
		{
			$values = ActiveUtils::strSplit($matches[1][$index], ',');
			$accept[$priority] = array_merge(ActiveUtils::mapGet($accept, $priority, array()), $values);
		}
		krsort($accept, SORT_NUMERIC);
		return $accept;		
	}
}

?>