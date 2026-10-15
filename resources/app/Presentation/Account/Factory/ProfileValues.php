<?php

declare(strict_types=1);

namespace App\Presentation\Account\Factory;

use Drago\Utils\ExtraArrayHash;


class ProfileValues extends ExtraArrayHash
{
	public string $username;
	public string $email;
}
