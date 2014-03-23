<?php namespace Webarq\Admin;

// use AdminController, Auth, Input;

class AuthController extends AdminController {
	
	public function getLogin()
	{	
		\Auth::logout();
		$this->layout->isLoginPage = true;
	}
	
	public function postLogin()
	{
		if (\Auth::attempt(array('username' => \Input::get('username'), 'password' => \Input::get('password'))) && \Auth::user()->role->code == 'admin')
		{
			return $this->redirect(null);
		}
		else
		{
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