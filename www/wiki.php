<?php
require_once dirname(__FILE__) . '/../active-php/index.php';

///////////////////////////////////////////////////////////////////////////////
function authenticate($user, $password)
{
	if( $user == 'admin' && $password == 'plop' ) 
	{
		return true;
	}
	return false;
}

///////////////////////////////////////////////////////////////////////////////
Controller::route('get', 'pages', 'getPageList');

function getPageList($params)
{
	Controller::views('../views');
	Controller::respondWithView(__FILE__, 'html', 'text/html');
	Controller::respondWithView(__FILE__, 'xml', 'application/atom+xml');

	Controller::respond(array(
		'pages' => array('plop', 'onk'),
	));
}

///////////////////////////////////////////////////////////////////////////////
Controller::route('post', 'pages', 'createPage');

function createPage($params)
{
	
}

///////////////////////////////////////////////////////////////////////////////
Controller::route('get', 'pages/:id', 'getPage');

function getPage($params)
{
	if( $params[':id'] == 'Plop' ) 
	{
		$params[':loggedIn'] = Controller::authenticateBasic('authenticate', 'Wiki!');
	}

	Controller::respondWith('getPageAsText', 'text/plain', 'txt');
	Controller::respondWith('getPageAsHtml', 'text/html', 'html');
	Controller::respond($params);
}
function getPageAsText($params)
{
	var_dump(Request::query());

	echo 'Page '.$params[':id'];
}
function getPageAsHtml($params)
{
	echo 'Page ' . $params[':id'];
}

///////////////////////////////////////////////////////////////////////////////
Controller::route('put', 'pages/:id', 'updatePage');

function updatePage($params)
{
	echo 'updatePage';
}

///////////////////////////////////////////////////////////////////////////////
Controller::route('delete', 'pages/:id', 'deletePage');

function deletePage($params)
{
	echo 'deletePage';
}

///////////////////////////////////////////////////////////////////////////////
Controller::dispatch();
?>