<?php declare(strict_types=1);

namespace App\Presentation\Account\Factory;

use App\Model\User\UserEntity;
use App\Model\User\UserRepository;
use App\Presentation\Sign\Factory;
use App\Presentation\Sign\Verification\VerificationService;
use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Drago\Form\Autocomplete;
use Nette\Application\UI\Form;
use Nette\Application\UI\InvalidLinkException;
use Nette\Forms\Controls\BaseControl;


class ProfileFactory
{
	private bool $emailChangePending = false;


	public function __construct(
		private readonly Factory $factory,
		private readonly UserFactory $userFactory,
		private readonly UserRepository $userRepository,
		private readonly CurrentPasswordVerifier $currentPasswordVerifier,
		private readonly VerificationService $verification,
	) {
	}


	/**
	 * Creates profile form.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function create(string $lang = 'cs'): Form
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$form = $this->factory->create();
		$form->addTextInput(UserEntity::ColumnUsername, 'Full name')
			->setPlaceholder('Full name')
			->setAutocomplete(Autocomplete::Name)
			->setRequired('Please enter your full name.')
			->addRule($form::MaxLength, 'The full name is too long (maximum %d characters).', UserEntity::ColumnUsernameSize);

		$email = $form->addEmailField()
			->addRule($form::MaxLength, 'The email address is too long (maximum %d characters).', UserEntity::ColumnEmailSize);

		// The email is where password recovery goes, so changing it needs the current password.
		$form->addPasswordInput(ProfileValues::CurrentPassword, 'Current password')
			->setPlaceholder('Current password')
			->setAutocomplete(Autocomplete::CurrentPassword)
			->addConditionOn($email, $form::NotEqual, $currentUser->email)
				->setRequired('Please enter your current password to change the email address.');

		$form->addSubmit('send', 'Save profile');
		$form->setDefaults([
			UserEntity::ColumnUsername => $currentUser->username,
			UserEntity::ColumnEmail => $currentUser->email,
		]);
		$form->onValidate[] = $this->validateEmailChange(...);
		$form->onSuccess[] = fn(Form $form, ProfileValues $values) => $this->saveProfile($form, $values, $lang);
		return $form;
	}


	/** True after saving, when the new email address waits for confirmation by the link sent to it. */
	public function isEmailChangePending(): bool
	{
		return $this->emailChangePending;
	}


	/**
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	private function validateEmailChange(Form $form): void
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$email = $form->getComponent(UserEntity::ColumnEmail);


		if ($form->hasErrors() || $email->getValue() === $currentUser->email) {
			return;
		}

		$input = $form->getComponent(ProfileValues::CurrentPassword);
		assert($input instanceof BaseControl);

		$error = $this->currentPasswordVerifier->verify($currentUser, (string) $input->getValue());
		if ($error !== null) {
			$input->addError($error);
		}
	}


	/**
	 * Saves profile data.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 * @throws InvalidLinkException
	 */
	private function saveProfile(Form $form, ProfileValues $values, string $lang): void
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$email = $values->email;
		$existingUser = $this->userRepository->findUserByEmail($email);

		if ($existingUser !== null && $existingUser->id !== $currentUser->id) {
			$form->addError("We're sorry, but an account with this email address already exists.");
			return;
		}

		$currentUser->username = $values->username;
		$this->userRepository->save($currentUser);

		// The new address applies after it is confirmed by a link (or at once without verification);
		// the previous address is notified about the change.
		if ($email !== $currentUser->email) {
			$this->emailChangePending = $this->verification->changeEmail($currentUser, $email, $lang);
		}

		// Until it is confirmed, the form shows the address the account really uses.
		$input = $form->getComponent(UserEntity::ColumnEmail);
		assert($input instanceof BaseControl);
		$input->setValue($currentUser->email);
	}
}
