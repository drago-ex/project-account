<?php declare(strict_types=1);

namespace App\Presentation\Account\Factory;

use Drago\Utils\ExtraArrayHash;


class ChangePasswordValues extends ExtraArrayHash
{
	public string $currentPassword;
	public string $password;
	public string $verify;
}
