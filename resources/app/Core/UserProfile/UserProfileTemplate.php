<?php

declare(strict_types=1);

namespace App\Core\UserProfile;

use App\UI\Backend\Sign\User\UserEntity;
use Drago\Application\UI\ExtraTemplate;


/** User profile template. */
class UserProfileTemplate extends ExtraTemplate
{
	public UserEntity $userProfile;
}
