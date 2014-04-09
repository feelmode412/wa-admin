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
			'setting' => array(
				'title' => 'Settings',
				'route' => 'setting',
			),			
		),
	),	
);