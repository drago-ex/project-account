<?php

declare(strict_types=1);

namespace App\UI\Backend\UserProfile;

use App\Core\UserProfile\ProfileControl;
use App\UI\Backend\Sign\RequireLogged;
use App\UI\BasePresenter;
use Throwable;


/**
 * User profile presenter.
 * @property-read UserProfileTemplate $template
 */
final class UserProfilePresenter extends BasePresenter
{
	use RequireLogged;

	public function __construct(
		private readonly ProfileControl $userProfileControl,
	) {
		parent::__construct();
	}


	/** @throws Throwable */
	protected function createComponentUserProfile(): ProfileControl
	{
		$control = $this->userProfileControl;
		$control->translator = $this->getTranslator();
		return $control;
	}
}
