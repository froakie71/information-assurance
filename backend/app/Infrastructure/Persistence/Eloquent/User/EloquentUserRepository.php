<?php

namespace App\Infrastructure\Persistence\Eloquent\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Illuminate\Support\Facades\Hash;

class EloquentUserRepository implements UserRepository
{
    public function findAll(): array
    {
        return UserModel::all()->toArray();
    }

    public function findById(int $id): ?User
    {
        return UserModel::find($id);
    }

    public function create(User $user): void
    {
        $userModel = new UserModel();
        $userModel->name = $user->getName();
        $userModel->email = $user->getEmail();
        $userModel->password = Hash::make($user->getPassword());
        $userModel->save();
    }

    public function update(User $user): void
    {
        UserModel::update($user->toArray());
    }

    public function delete(int $id): void
    {
        UserModel::delete($id);
    }
}
