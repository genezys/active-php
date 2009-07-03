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
ActiveController::route('get', 'pages.:format', 'getPageList');
ActiveController::route('get', 'pages', 'getPageList');

function getPageList($params)
{
	ActiveController::views(__FILE__, '../views');
	ActiveController::respondWithView('html', 'text/html');
	ActiveController::respondWithView('xml', 'application/atom+xml');

	ActiveController::respond(array(
		'pages' => array('plop', 'onk'),
	));
}

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('post', 'pages.:format', 'getPageList');
ActiveController::route('post', 'pages', 'createPage');

function createPage($params)
{
	
}

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('get', 'pages/:id.:format', 'getPage');
ActiveController::route('get', 'pages/:id', 'getPage');

function getPage($params)
{
	if( $params['id'] == 'Plop' ) 
	{
		$params['loggedIn'] = ActiveController::authenticateBasic('Wiki!', 'authenticate');
	}

	ActiveController::respondWith('txt', 'text/plain', 'getPageAsText');
	ActiveController::respondWith('html', 'text/html', 'getPageAsHtml');
	ActiveController::respond($params);
}
function getPageAsText($params)
{
	var_dump(ActiveRequest::query());

	echo 'Page '.$params['id'];
}
function getPageAsHtml($params)
{
	echo 'Page ' . $params['id'];
}

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('put', 'pages/:id.:format', 'updatePage');
ActiveController::route('put', 'pages/:id', 'updatePage');

function updatePage($params)
{
	echo 'updatePage';
}

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('delete', 'pages/:id.:format', 'deletePage');
ActiveController::route('delete', 'pages/:id', 'deletePage');

function deletePage($params)
{
	echo 'deletePage';
}

///////////////////////////////////////////////////////////////////////////////
ActiveController::dispatch();
?>