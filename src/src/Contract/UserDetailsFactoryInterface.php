<?php

declare(strict_types=1);

namespace App\Contract;

use App\Entity\User;

interface UserDetailsFactoryInterface
{
    public function updateUserDetails(User $user): User;
}
