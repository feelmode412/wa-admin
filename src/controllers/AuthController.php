<?php namespace Webarq\Admin;

class AuthController extends Controller {
	
	public function getLogin()
	{
		if (\Auth::check() && ! \Auth::user()->admin) // A frontend user is logged in
		{
			$this->createMessage('A frontend user ('.\Auth::user()->email.') is currently logged in. To continue logging in to this admin panel, please consider using another browser or else the frontend session will be deleted.', 'warning', false);
		}
		else
		{
			\Auth::logout();
		}

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