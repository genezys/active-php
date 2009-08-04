<?php
require_once dirname(__FILE__) . '/../active-php/index.php';

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('get', 'pages(.:format)', function($params)
{
	var_dump(ActiveRequest::types());
	
	
	ActiveController::views(__FILE__, '../views/pages');
	ActiveController::respondWithView('html', 'text/html');
	ActiveController::respondWithView('xml', 'application/atom+xml');

	ActiveController::value('pages', array('plop', 'onk'));
	ActiveController::respond();
});

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('post', 'pages(.:format)', function()
{
	
});

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('get', 'pages/:id(.:format)', function($params)
{
	if( $params['id'] == 'Plop' ) 
	{
		$params['loggedIn'] = ActiveController::authenticateBasic('Wiki!', function($user, $password)
		{
			if( $user == 'admin' && $password == 'plop' ) 
			{
				return true;
			}
			return false;
		});
	}

	ActiveController::views(__FILE__, '../views/page');
	ActiveController::respondWithView('txt', 'text/plain');
	ActiveController::respondWithView('html', 'text/html');
	
	ActiveController::value('title', $params['id']);
	ActiveController::value('content', str_repeat($params['id'], 10));
	ActiveController::respond();
});


///////////////////////////////////////////////////////////////////////////////
ActiveController::route('put', 'pages/:id(.:format)', function($params)
{
	echo 'updatePage';
});

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('delete', 'pages/:id(.:format)', function($params)
{
	echo 'deletePage';
});

///////////////////////////////////////////////////////////////////////////////
ActiveController::dispatch();
?>