<?php

declare(strict_types=1);

namespace App\UI\Backend\UserProfile;

use App\UI\Backend\BackendPresenter;
use App\UI\Backend\Sign\RequireLogged;
use App\UI\Backend\UserProfile\Factory\ChangePasswordFactory;
use App\UI\Backend\UserProfile\Factory\UserProfileFactory;
use Drago\Application\UI\Alert;
use Nette\Application\UI\Form;
use Nette\Neon\Exception;
use Throwable;


/**
 * User profile presenter.
 * @property-read UserProfileTemplate $template
 */
final class UserProfilePresenter extends BackendPresenter
{
	use RequireLogged;

	public function __construct(
		private readonly ChangePasswordFactory $changePasswordFactory,
		private readonly UserProfileFactory $userProfileFactory,
	) {
		parent::__construct();
	}


	/**
	 * @throws Throwable
	 * @throws Exception
	 */
	protected function createComponentUserChangePassword(): Form
	{
		$form = $this->changePasswordFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Password change was successful.', Alert::Success);
		};
		$this->redrawControl('changePassword');
		$this->redrawControl('message');
		return $form;
	}


	/**
	 * @throws Exception
	 * @throws Throwable
	 */
	protected function createComponentUserProfile(): Form
	{
		$form = $this->userProfileFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Profile has been saved.', Alert::Success);
		};
		$this->redrawControl('profile');
		$this->redrawControl('message');
		return $form;
	}
}
