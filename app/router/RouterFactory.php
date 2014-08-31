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
		$router[] = new Route('admin/<action>', 'Article:list');
		$router[] = new Route('<slug>', 'Article:detail');
		return $router;
	}
}
