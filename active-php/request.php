<?php
require_once dirname(__FILE__).'/utils.php';
require_once dirname(__FILE__).'/string.php';

class ActiveRequest
{
	/*static*/ function method()
	{
		return ActiveString::defaults(
			ActiveUtils::arrayGet($_GET, 'x-rest-method'),
			ActiveUtils::arrayGet($_SERVER, 'REQUEST_METHOD'),
			'GET'
		);
	}

	/*static*/ function scriptPath()
	{
		$scriptName = ActiveUtils::arrayGet($_SERVER, 'SCRIPT_NAME', '');
		$script = substr(ActiveRequest::requestUri(), 0, strlen($scriptName));
		
		if( $script != $scriptName )
		{
			// SCRIPT_NAME may contains the extension when it should not
			// Remove it
			return ActiveString::before($scriptName, '.');
		}
		return $script;
	}

	/*static*/ function scriptDir()
	{
		return rtrim(dirname(ActiveRequest::scriptPath()), '/');
	}
	
	/*static*/ function scriptUri()
	{
		return ActiveRequest::scheme().'://'.ActiveRequest::domain().ActiveRequest::scriptPath();
	}

	/*static*/ function requestUri()
	{
		return ActiveUtils::arrayGet($_SERVER, 'REQUEST_URI', '');
	}
	
	/*sttaic*/ function scheme()
	{
		return isset($_SERVER['HTTPS']) ? 'https' : 'http';
	}
	
	/*static*/ function domain()
	{
		return getenv('HTTP_HOST');
	}
	
	/*static*/ function pathInfo()
	{
		$requestUri = ActiveRequest::requestUri();
		$script = ActiveRequest::scriptPath();
		$pathInfo = substr($requestUri, strlen($script));
		
		$pathInfo = ActiveString::escapeMagicQuotes($pathInfo);
		$pathInfo = ActiveString::before($pathInfo, '?');
		$pathInfo = urldecode($pathInfo);
		$pathInfo = trim($pathInfo, '/');
		return $pathInfo;
	}
	
	/*static*/ function query()
	{
		$queryString = ActiveString::after(ActiveRequest::requestUri(), '?');
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
		return ActiveUtils::arrayGet($_SERVER, 'PHP_AUTH_USER');
	}

	/*static*/ function password()
	{
		return ActiveUtils::arrayGet($_SERVER, 'PHP_AUTH_PW');
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
		$acceptString = ActiveUtils::arrayGet($_SERVER, $key, '');
		
		$accept = array();
		foreach( explode(',', $acceptString) as $acceptType ) 
		{
			preg_match('$([\-/+*A-Za-z0-9]+)(?:;q=([0-9.]+))?$', $acceptType, $matches);
			$mime = ActiveString::defaults($matches[1], "*/*");
			$priority = ActiveString::defaults($matches[2], "1.0");
			$accept[$priority][] = $mime;
		}
		krsort($accept, SORT_NUMERIC);
		return $accept;
	}
}

?>