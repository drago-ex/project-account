<?php

declare(strict_types=1);

namespace App\Core\UserProfile;

use App\UI\Backend\Sign\Factory;
use App\UI\Backend\Sign\User\UserEntity;
use App\UI\Backend\Sign\User\UserRepository;
use Dibi\Exception;
use Drago\Application\UI\Alert;
use Drago\Application\UI\ExtraControl;
use Drago\Attr\AttributeDetectionException;
use Drago\Form\Autocomplete;
use Nette\Application\UI\Form;
use Nette\Forms\Control;
use Nette\Security\Identity;
use Nette\Security\Passwords;
use Nette\Security\User;


/** User profile control. */
class UserProfileControl extends ExtraControl
{
	public function __construct(
		private readonly Factory $factory,
		private readonly UserRepository $userRepository,
		private readonly Passwords $passwords,
		private readonly User $user,
	) {
	}


	/**
	 * Render profile control.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function render(): void
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/userProfile.latte');
		$template->setTranslator($this->translator);
		$template->userProfile = $this->getCurrentUser();
		$template->render();
	}


	/**
	 * Creates profile form.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	protected function createComponentProfile(): Form
	{
		$currentUser = $this->getCurrentUser();
		$form = $this->factory->create();
		$form->addTextInput(UserEntity::ColumnUsername, 'Full name')
			->setPlaceholder('Full name')
			->setRequired('Please enter your full name.')
			->setAutocomplete(Autocomplete::Name);

		$form->addEmailField();
		$form->addSubmit('send', 'Save profile');
		$form->setDefaults([
			UserEntity::ColumnUsername => $currentUser->username,
			UserEntity::ColumnEmail => $currentUser->email,
		]);
		$form->onSuccess[] = $this->saveProfile(...);
		return $form;
	}


	/** Creates password change form. */
	protected function createComponentPassword(): Form
	{
		$form = $this->factory->create();
		$form->addPasswordInput('currentPassword', 'Current password')
			->setPlaceholder('Current password')
			->setRequired('Please enter your current password.')
			->setAutocomplete(Autocomplete::CurrentPassword)
			->addRule(
				fn(Control $input): bool => $this->checkCurrentPassword($input),
				'Current password is incorrect.',
			);

		$form->addPasswordField()
			->setAutocomplete(Autocomplete::NewPassword)
			->addRule($form::MinLength, 'Password must be at least %d characters long.', 8)
			->addRule(
				$form::Pattern,
				'The password must contain uppercase and lowercase letters, numbers, and a special character.',
				'^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[^A-Za-z0-9])[\S]{8,}$',
			);

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
			$this->getCurrentUser()->password,
		);
	}


	/**
	 * Saves profile data.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	private function saveProfile(Form $form): void
	{
		$values = $form->getValues('array');
		$currentUser = $this->getCurrentUser();
		$email = (string) $values[UserEntity::ColumnEmail];
		$existingUser = $this->userRepository->findUserByEmail($email);

		if ($existingUser !== null && $existingUser->id !== $currentUser->id) {
			$form->addError("We're sorry, but an account with this email address already exists.");
			return;
		}

		$currentUser->username = (string) $values[UserEntity::ColumnUsername];
		$currentUser->email = $email;
		$this->userRepository->save($currentUser);
		$this->updateIdentity($currentUser);

		$this->flashMessage('Profile has been saved.', Alert::Success);
		$this->redrawControl();
	}


	/**
	 * Saves new password.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	private function savePassword(Form $form): void
	{
		$values = $form->getValues('array');
		$currentUser = $this->getCurrentUser();
		$currentUser->password = $this->passwords->hash((string) $values['password']);
		$this->userRepository->save($currentUser);

		$form->reset();
		$this->flashMessage('Password change was successful.', Alert::Success);
		$this->redrawControl();
	}


	/**
	 * Gets current user entity.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	private function getCurrentUser(): UserEntity
	{
		$identity = $this->user->getIdentity();
		if ($identity === null) {
			$this->error('You must be logged in.', 403);
		}

		$user = $this->userRepository->get((int) $identity->getId())
			->record();

		if ($user === null) {
			$this->error('User not found.', 404);
		}
		return $user;
	}


	/** Updates current identity data after profile save. */
	private function updateIdentity(UserEntity $user): void
	{
		$identity = $this->user->getIdentity();
		if (!$identity instanceof Identity) {
			return;
		}

		$identity->__set(UserEntity::ColumnUsername, $user->username);
		$identity->__set(UserEntity::ColumnEmail, $user->email);
	}
}
