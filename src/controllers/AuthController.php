<?php namespace Webarq\Admin;

class AuthController extends Controller {
	
	public function getLogin()
	{	
		\Auth::logout();
		$this->layout->isLoginPage = true;
	}
	
	public function postLogin()
	{
		$authentication = \Auth::attempt(array('username' => \Input::get('username'), 'password' => \Input::get('password')));
		if ($authentication && \Auth::user()->admin)
		{
			return $this->redirect(null);
		}
		else
		{
			\Auth::logout();
			$this->createMessage('Incorrect username or password.', 'error');
			return $this->redirect('auth/login');
		}
	}
	
	public function getLogout()
	{
		$this->createMessage('You have successfully logged out.');
		return $this->redirect('auth/login');
	}
	
}