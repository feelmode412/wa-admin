<?php
return array(
	'system' => array(
		'title' => 'System & Utilities',
		'img' => 'icon-nav-08.png',
		'route' => '#system',
		'subs' => array(
			'system.administrator' => array(
				'title' => 'Administrators',
				'route' => '#system.administrator',
				'subs' => array(
					'system.administrator.admin' => array(
						'title' => 'Administrators',
						'route' => 'user',
					),
					'system.administrator.role' => array(
						'title' => 'Roles',
						'route' => 'user/role',
					),
				),
			),
			'email' => array(
				'title' => 'Email',
				'route' => '#system.email',
				'subs' => array(
					'email.template' => array(
						'title' => 'Email Templates',
						'route' => 'email/template',
					),
					'email.tester' => array(
						'title' => 'Email Tester',
						'route' => 'email/tester',
					),
				),
			),
			'setting' => array(
				'title' => 'Settings',
				'route' => 'setting',
			),
		),
	),	
);