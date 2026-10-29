<?php declare(strict_types=1);

namespace App\Presentation\Account\Factory;

use Drago\Utils\ExtraArrayHash;


class ProfileValues extends ExtraArrayHash
{
	public const string CurrentPassword = 'currentPassword';

	public string $username;
	public string $email;
	public string $currentPassword;
}
