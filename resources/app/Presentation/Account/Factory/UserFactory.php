<?php declare(strict_types=1);

namespace App\Presentation\Account\Factory;

use App\Model\User\UserEntity;
use App\Model\User\UserRepository;
use Dibi\Exception;
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
	 * @throws Exception
	 */
	public function getCurrentUser(): UserEntity
	{
		$identity = $this->user->getIdentity() ?? throw new ForbiddenRequestException('You must be logged in.');

		return $this->userRepository->table->get((int) $identity->getId())
			->record() ?? throw new BadRequestException('User not found.', 404);
	}
}
