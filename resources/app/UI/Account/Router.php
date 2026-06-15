<?php

declare(strict_types=1);

namespace App\UI\Account;

use Nette;
use Nette\Application\Routers\RouteList;


/** Account router. */
final class Router
{
	use Nette\StaticClass;

	/** Create router. */
	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Account')
			->addRoute('[<lang=cs cs|en>/]account/<presenter>/<action>', 'Account:default');

		return $router;
	}
}
