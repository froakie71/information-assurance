<?php

namespace App\Domain\User;

interface UserRepository
{
    public function findAll(): array;
    public function findById(int $id): ?User;
    public function create(User $user): void;
    public function update(User $user): void;
    public function delete(int $id): void;
}
