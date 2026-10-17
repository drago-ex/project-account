<?php

/**
 * Test: App\Presentation\Account\Factory\CurrentPasswordVerifier
 */

declare(strict_types=1);

use App\Model\User\UserEntity;
use App\Presentation\Account\Factory\CurrentPasswordVerifier;
use App\Presentation\Sign\RateLimiter;
use Nette\Caching\Storages\MemoryStorage;
use Nette\Security\Passwords;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function createUser(int $id, Passwords $passwords): UserEntity
{
	$user = new UserEntity;
	$user->id = $id;
	$user->password = $passwords->hash('correct horse battery staple');
	return $user;
}


test('The correct password passes and a wrong one is reported', function () {
	$passwords = new Passwords;
	$verifier = new CurrentPasswordVerifier(new RateLimiter(new MemoryStorage), $passwords);
	$user = createUser(1, $passwords);

	Assert::null($verifier->verify($user, 'correct horse battery staple'));
	Assert::same('Current password is incorrect.', $verifier->verify($user, 'wrong'));
});


test('After 5 wrong passwords even the correct one is refused for a while, only for that user', function () {
	$passwords = new Passwords;
	$verifier = new CurrentPasswordVerifier(new RateLimiter(new MemoryStorage), $passwords);
	$user = createUser(1, $passwords);

	for ($i = 0; $i < 5; $i++) {
		Assert::same('Current password is incorrect.', $verifier->verify($user, "wrong $i"));
	}

	Assert::same('Too many incorrect attempts. Try again later.', $verifier->verify($user, 'correct horse battery staple'));
	Assert::null($verifier->verify(createUser(2, $passwords), 'correct horse battery staple'));
});


test('A correct password resets the count of wrong ones', function () {
	$passwords = new Passwords;
	$verifier = new CurrentPasswordVerifier(new RateLimiter(new MemoryStorage), $passwords);
	$user = createUser(1, $passwords);

	for ($i = 0; $i < 4; $i++) {
		$verifier->verify($user, "wrong $i");
	}

	Assert::null($verifier->verify($user, 'correct horse battery staple'));
	for ($i = 0; $i < 4; $i++) {
		Assert::same('Current password is incorrect.', $verifier->verify($user, "wrong $i"));
	}
});
