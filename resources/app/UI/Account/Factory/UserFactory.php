<?php

declare(strict_types=1);

namespace App\UI\Account\Factory;

use App\UI\Backend\Sign\User\UserEntity;
use App\UI\Backend\Sign\User\UserRepository;
use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;
use Nette\Security\User;


readonly class UserFactory
{
	public function __construct(
		private UserRepository $userRepository,
		private User $user,
	) {
	}


	/**
	 * Gets current user entity.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function getCurrentUser(): UserEntity
	{
		$identity = $this->user->getIdentity();
		if ($identity === null) {
			throw new ForbiddenRequestException('You must be logged in.');
		}

		$user = $this->userRepository->get((int) $identity->getId())
			->record();

		if ($user === null) {
			throw new BadRequestException('User not found.', 404);
		}
		return $user;
	}
}
