<?php

declare(strict_types=1);

/**
 * Basic access rules of this package for drago-ex/permission.
 * Generated into the project by: php vendor/bin/create-permission --packages
 */
return [
	// Signed-in users may manage their own account.
	[
		'className' => 'PermissionProvider',
		'namespace' => 'App\Presentation\Account',
		'resource' => 'Account',
		'outputDir' => 'app/Presentation/Account',
		'allow' => ['RoleUser,self::Resource'],
	],
];
