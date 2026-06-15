<?php

declare(strict_types=1);

namespace App\UI\Backend\UserProfile\Factory;

use App\UI\Backend\Sign\Factory;
use App\UI\Backend\Sign\User\UserRepository;
use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Drago\Form\Autocomplete;
use Drago\Form\Rules\PasswordRules;
use Drago\Localization\Translator;
use Nette\Application\UI\Form;
use Nette\Forms\Control;
use Nette\Security\Passwords;


class ChangePasswordFactory
{
	public Translator $translator;


	public function __construct(
		private readonly Factory $factory,
		private readonly UserFactory $userFactory,
		private readonly UserRepository $userRepository,
		private readonly Passwords $passwords,
	) {
	}


	/** Creates password change form. */
	public function create(): Form
	{
		$form = $this->factory->create();
		$form->addPasswordInput('currentPassword', 'Current password')
			->setPlaceholder('Current password')
			->setRequired('Please enter your current password.')
			->setAutocomplete(Autocomplete::CurrentPassword)
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
	 * Saves new password.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	private function savePassword(Form $form): void
	{
		$values = $form->getValues('array');
		$currentUser = $this->userFactory->getCurrentUser();
		$currentUser->password = $this->passwords->hash((string) $values['password']);
		$this->userRepository->save($currentUser);
		$form->reset();
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
}
