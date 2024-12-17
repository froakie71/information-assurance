<?php

namespace Domain\Admin\Repositories;

use Domain\Admin\Models\AdminUser;

interface AdminUserRepositoryInterface
{
    public function create(array $data): AdminUser;
    public function findByEmail(string $email): ?AdminUser;
    public function getUsersWithCompletedTasksCount();
} 