<?php

declare(strict_types=1);

namespace App\Presentation\Account\Factory;

use App\Model\User\UserRepository;
use App\Presentation\Sign\Factory;
use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Drago\Form\Autocomplete;
use Drago\Form\Rules\PasswordRules;
use Drago\Localization\Translator;
use Nette\Application\UI\Form;
use Nette\Forms\Control;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Nette\Security\User;
use Nette\Utils\Random;


class ChangePasswordFactory
{
	public Translator $translator;


	public function __construct(
		private readonly Factory $factory,
		private readonly UserFactory $userFactory,
		private readonly UserRepository $userRepository,
		private readonly Passwords $passwords,
		private readonly User $user,
	) {
	}


	/** Creates password change form. */
	public function create(): Form
	{
		$form = $this->factory->create();
		$form->addPasswordInput('currentPassword', 'Current password')
			->setPlaceholder('Current password')
			->setAutocomplete(Autocomplete::CurrentPassword)
			->setRequired('Please enter your current password.')
			->addRule($this->checkCurrentPassword(...), 'Current password is incorrect.');

		$form->addPasswordField()
			->setAutocomplete(Autocomplete::NewPassword)
			->addRule($form::MinLength, 'Password must be at least %d characters long.', 8)
			->addRule($form::Pattern, PasswordRules::StrongMessage, PasswordRules::StrongPattern);

		$form->addPasswordConfirmationField()
			->setAutocomplete(Autocomplete::Off);

		$form->addSubmit('send', 'Change password');
		$form->onSuccess[] = $this->savePassword(...);
		return $form;
	}


	/**
	 * Checks current user password.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function checkCurrentPassword(Control $input): bool
	{
		return $this->passwords->verify(
			(string) $input->getValue(),
			$this->userFactory->getCurrentUser()
				->password,
		);
	}


	/**
	 * Saves new password and signs out all other sessions of the user.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	private function savePassword(Form $form, ChangePasswordValues $values): void
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$currentUser->password = $this->passwords->hash($values->password);

		// Sessions are restored by the token, so a new token invalidates all existing ones (as password recovery does).
		$currentUser->token = Random::generate(32);
		$this->userRepository->save($currentUser);

		// Keep the current session signed in with the new token.
		$identity = $this->user->getIdentity();
		if ($identity instanceof SimpleIdentity) {
			$identity->token = $currentUser->token;
			$this->user->login($identity);
		}

		$form->reset();
	}
}
