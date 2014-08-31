<?php

namespace App;

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

class RouterFactory
{
	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();
		$router[] = new Route('', 'Article:default');
		$router[] = new Route('<slug>', 'Article:detail');
		$router[] = new Route('admin/<action>', 'Article:default');
		return $router;
	}
}
