<?php declare(strict_types=1);

namespace App\Presentation\Account\Factory;

use App\Model\User\UserRepository;
use App\Presentation\Sign\Factory;
use App\Presentation\Sign\PasswordPolicy;
use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Drago\Form\Autocomplete;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Nette\Security\User;
use Nette\Utils\Random;


class ChangePasswordFactory
{
	public function __construct(
		private readonly Factory $factory,
		private readonly UserFactory $userFactory,
		private readonly UserRepository $userRepository,
		private readonly Passwords $passwords,
		private readonly User $user,
		private readonly CurrentPasswordVerifier $currentPasswordVerifier,
	) {
	}


	/** Creates password change form. */
	public function create(): Form
	{
		$form = $this->factory->create();
		$form->addPasswordInput('currentPassword', 'Current password')
			->setPlaceholder('Current password')
			->setAutocomplete(Autocomplete::CurrentPassword)
			->setRequired('Please enter your current password.');

		$password = $form->addPasswordField()
			->setAutocomplete(Autocomplete::NewPassword);
		PasswordPolicy::apply($password);

		$form->addPasswordConfirmationField()
			->setAutocomplete(Autocomplete::Off);

		$form->addSubmit('send', 'Change password');
		$form->onValidate[] = $this->validateCurrentPassword(...);
		$form->onSuccess[] = $this->savePassword(...);
		return $form;
	}


	/**
	 * Checks the current password once the other fields are valid, so mistakes in them do not use up attempts.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	private function validateCurrentPassword(Form $form): void
	{
		if ($form->hasErrors()) {
			return;
		}

		$input = $form->getComponent('currentPassword');
		assert($input instanceof BaseControl);

		$error = $this->currentPasswordVerifier->verify(
			$this->userFactory->getCurrentUser(),
			(string) $input->getValue(),
		);

		if ($error !== null) {
			$input->addError($error);
		}
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
