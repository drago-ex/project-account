<?php

declare(strict_types=1);

namespace App\Presentation\Account\Factory;

use App\Model\User\UserEntity;
use App\Presentation\Sign\RateLimiter;
use Nette\Security\Passwords;


/**
 * Confirms the current password before sensitive account changes.
 * Failures are limited per user, so a hijacked session cannot be used to guess the password.
 */
readonly class CurrentPasswordVerifier
{
	private const string LimitBucket = 'account-password';

	private const int
		MaxFailures = 5,
		FailureWindow = 900;


	public function __construct(
		private RateLimiter $limiter,
		private Passwords $passwords,
	) {
	}


	/**
	 * Returns an error message, or null when the password is correct.
	 */
	public function verify(UserEntity $user, #[\SensitiveParameter] string $password): ?string
	{
		$subject = (string) $user->id;
		if (!$this->limiter->isAllowed(self::LimitBucket, $subject, self::MaxFailures)) {
			return 'Too many incorrect attempts. Try again later.';
		}

		if (!$this->passwords->verify($password, $user->password)) {
			$this->limiter->hit(self::LimitBucket, $subject, self::FailureWindow);
			return 'Current password is incorrect.';
		}

		$this->limiter->clear(self::LimitBucket, $subject);
		return null;
	}
}
