<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;
use App\Models\PetFacade;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		//$router->addRoute('<presenter>/<action>[/<id>]', 'Pets:index');
		$router->addRoute('/', 'Pets:home');
		$router->addRoute('pet/add', 'Pets:add');
		$router->addRoute('pet/all', 'Pets:all');
		$router->addRoute('pet/edit', 'Pets:edit');
		$router->addRoute('pet/delete', 'Pets:delete');
		$router->addRoute('pet/findByStatus', 'Pets:find');
		return $router;
	}
}
