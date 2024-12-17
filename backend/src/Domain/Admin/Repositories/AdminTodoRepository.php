<?php

namespace Domain\Admin\Repositories;

use Domain\Admin\Models\AdminTodo;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminTodoRepository implements AdminTodoRepositoryInterface
{
    public function create(array $data): AdminTodo
    {
        return AdminTodo::create($data);
    }

    public function update(AdminTodo $todo, array $data): AdminTodo
    {
        $todo->update($data);
        return $todo->fresh();
    }

    public function delete(AdminTodo $todo): bool
    {
        return $todo->delete();
    }

    public function findById(int $id): ?AdminTodo
    {
        return AdminTodo::find($id);
    }

    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return AdminTodo::with('admin')
            ->latest()
            ->paginate($perPage);
    }
} 