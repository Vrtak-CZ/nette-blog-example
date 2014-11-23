<?php

namespace App;

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

class RouterFactory
{

	/** @var boolean */
	private $https;

	/**
	 * @param boolean
	 */
	public function __construct($https = false)
	{
		$this->https = $https;
	}

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$defaultFlags = $this->https ? Route::SECURED : 0;

		$router = new RouteList();
		$router[] = new Route('', 'Article:default', $defaultFlags);
		$router[] = new Route('admin/login', 'Login:default', $defaultFlags);
		$router[] = new Route('admin[/<action>]', 'Admin:Article:default', $defaultFlags);
		$router[] = new Route('feed.xml', 'Rss:default', $defaultFlags);
		$router[] = new Route('<slug>/feed.xml', 'Rss:comments', $defaultFlags);
		$router[] = new Route('<slug>', 'Article:detail', $defaultFlags);
		return $router;
	}
}
