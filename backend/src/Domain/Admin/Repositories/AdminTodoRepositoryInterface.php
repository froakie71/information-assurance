<?php

namespace Domain\Admin\Repositories;

use Domain\Admin\Models\AdminTodo;
use Illuminate\Pagination\LengthAwarePaginator;

interface AdminTodoRepositoryInterface
{
    public function create(array $data): AdminTodo;
    public function update(AdminTodo $todo, array $data): AdminTodo;
    public function delete(AdminTodo $todo): bool;
    public function findById(int $id): ?AdminTodo;
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator;
} 