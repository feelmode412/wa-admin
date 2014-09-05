<?php namespace Webarq\Admin\Email;

use Exception;
use Input;
use Mail;
use Webarq\Admin as Admin;
use Webarq\Site\Setting;

class TesterController extends Admin\Controller {

	public function __construct(Setting $setting)
	{
		parent::__construct();

		$this->setting = $setting;
		$this->fromEmail = $this->setting->ofCodeType('email', 'noreply')->value;
		$this->fromName = $this->setting->ofCodeType('name', 'noreply')->value;
		$this->subject = 'Test Subject {random_number}';
		$this->content = 'Test Content {random_number}';

		$this->section = 'email/tester';
		$this->pageTitle = 'Email Tester';
		$this->activeMainMenu = 'system';
	}

	public function getIndex()
	{
		$this->layout->breadcrumbs = $this->createBreadcrumbs();
		$this->layout->content = \View::make('admin::email.tester.index', array(
			'fromEmail' => $this->fromEmail,
			'fromName' => $this->fromName,
			'subject' => $this->subject,
			'content' => $this->content,
			'section' => $this->section,
		));
	}

	public function postIndex()
	{
		$randomNumber = rand();
		$subject = str_replace('{random_number}', $randomNumber, $this->subject);
		$content = str_replace('{random_number}', $randomNumber, $this->content);

		$fromEmail = $this->fromEmail;
		$fromName = $this->fromName;

		$mail = true;
		$message = 'The message was successfully sent.';
		$messageType = 'info';
		try
		{
			Mail::send('site::layouts.email.master', array('content' => $content), function($message) use ($fromEmail, $fromName, $subject)
			{
				$message->from($fromEmail, $fromName);
				$message->to(Input::get('to'));
				$message->subject($subject);
			});
		}
		catch (Exception $e)
		{
			$mail = false;
			$message = $e->getMessage();
			$messageType = 'error';
		}

		$this->createMessage($message, $messageType);

		return $this->redirect($this->section);
	}

}