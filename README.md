# WEBARQ Admin

A Laravel 4.0.* package for building Admin Panel in WEBARQ's web application projects.

See also: [WEBARQ\Presence](http://128.199.208.157/gitlist/index.php/webarq/presence.git).

## Included Packages
- [Webarq\Site](http://128.199.208.157/gitlist/index.php/webarq/site.git)

## Installation

### Basic

1. Make sure that your SSH public key has been registered on the private repository server (128.199.208.157).
2. Add the private repository to `composer.json`:

		"repositories": [
			{
				"type": "vcs",
				"url": "git@128.199.208.157:/opt/git/webarq/admin.git"
			},
			{
				"type": "vcs",
				"url": "git@128.199.208.157:/opt/git/webarq/site.git"
			}
		]
3. Add the dependency:

		"require": {
			"webarq/admin": "dev-master",
			"webarq/site": "dev-master"
		}
4. Change `preferred-install` to `auto`:

		"config": {
			"preferred-install": "auto"
		},
5. Update Composer:

		"composer update"
6. Update your `/app/config/app.php`:
		
		'providers' => array(
			'Webarq\Admin\AdminServiceProvider',
			'Webarq\Site\SiteServiceProvider',
		);

		'aliases' => array(
			'Admin' => 'Webarq\Admin\AdminFacade',
			'Site' => 'Webarq\Site\SiteFacade',
		);

### Merge Schemas

Merge the following 2 schema to your MySQL database, in sequence:

- `/vendor/webarq/site/schema.sql`
- `/vendor/webarq/admin/schema.sql`

### Setup The Admin Panel

1. Publish Admin's assets:

		php artisan asset:publish webarq/admin
2. Publish Admin's configurations:

		php artisan config:publish webarq/admin

Now you can access the admin panel at, for example: `http://localhost/my-project/admin-cp/`. Default user: `admin`, default password: `webarq`.



Copyright 2014 [Web Architect Technology](http://www.webarq.com/)