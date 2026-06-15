<?php

declare(strict_types=1);

namespace App\UI\Account;

use App\UI\Account\Factory\ChangePasswordFactory;
use App\UI\Account\Factory\ProfileFactory;
use App\UI\Backend\Sign\RequireLogged;
use App\UI\BasePresenter;
use Drago\Application\UI\Alert;
use Nette\Application\UI\Form;
use Nette\Neon\Exception;
use Throwable;


/**
 * Account presenter.
 * @property-read AccountTemplate $template
 */
final class AccountPresenter extends BasePresenter
{
	use RequireLogged;

	public function __construct(
		private readonly ChangePasswordFactory $changePasswordFactory,
		private readonly ProfileFactory $profileFactory,
	) {
		parent::__construct();
	}


	/**
	 * @throws Throwable
	 * @throws Exception
	 */
	protected function createComponentChangePassword(): Form
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
	protected function createComponentProfile(): Form
	{
		$form = $this->profileFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Profile has been saved.', Alert::Success);
		};
		$this->redrawControl('profile');
		$this->redrawControl('message');
		return $form;
	}
}
