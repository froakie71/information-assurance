<?php

namespace App\Application\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;

class RegisterUser
{
    public function __construct(private UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function register(string $name, string $password, string $email): void
    {
        $user = new User(null, $name, $email, $password);
        $this->userRepository->create($user);
    }
}
