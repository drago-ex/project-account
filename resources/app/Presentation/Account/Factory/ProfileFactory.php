<?php

declare(strict_types=1);

namespace App\Presentation\Account\Factory;

use App\Model\User\UserEntity;
use App\Model\User\UserRepository;
use App\Presentation\Sign\Factory;
use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Drago\Form\Autocomplete;
use Drago\Localization\Translator;
use Nette\Application\UI\Form;


class ProfileFactory
{
	public Translator $translator;


	public function __construct(
		private readonly Factory $factory,
		private readonly UserFactory $userFactory,
		private readonly UserRepository $userRepository,
	) {
	}


	/**
	 * Creates profile form.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function create(): Form
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$form = $this->factory->create();
		$form->addTextInput(UserEntity::ColumnUsername, 'Full name')
			->setPlaceholder('Full name')
			->setAutocomplete(Autocomplete::Name)
			->setRequired('Please enter your full name.');

		$form->addEmailField();
		$form->addSubmit('send', 'Save profile');
		$form->setDefaults([
			UserEntity::ColumnUsername => $currentUser->username,
			UserEntity::ColumnEmail => $currentUser->email,
		]);
		$form->onSuccess[] = $this->saveProfile(...);
		return $form;
	}


	/**
	 * Saves profile data.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	private function saveProfile(Form $form, ProfileValues $values): void
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$email = $values->email;
		$existingUser = $this->userRepository->findUserByEmail($email);

		if ($existingUser !== null && $existingUser->id !== $currentUser->id) {
			$form->addError("We're sorry, but an account with this email address already exists.");
			return;
		}

		$currentUser->username = $values->username;
		$currentUser->email = $email;
		$this->userRepository->save($currentUser);
	}
}
