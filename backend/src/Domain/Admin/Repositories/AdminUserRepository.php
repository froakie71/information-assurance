<?php

namespace Domain\Admin\Repositories;

use Domain\Admin\Models\AdminUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserRepository implements AdminUserRepositoryInterface
{
    public function create(array $data): AdminUser
    {
        return AdminUser::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function findByEmail(string $email): ?AdminUser
    {
        return AdminUser::where('email', $email)->first();
    }

    public function getUsersWithCompletedTasksCount()
    {
        return DB::table('users')
            ->select('users.name', DB::raw('COUNT(todos.id) as completed_tasks'))
            ->leftJoin('todos', 'users.id', '=', 'todos.user_id')
            ->where('todos.status', '=', 'completed')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('completed_tasks')
            ->get();
    }
} 