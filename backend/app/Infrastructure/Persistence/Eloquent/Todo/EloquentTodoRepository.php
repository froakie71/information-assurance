<?php

namespace App\Infrastructure\Persistence\Eloquent\Todo;

use App\Domain\Todo\Todo;
use App\Domain\Todo\TodoRepository;

class EloquentTodoRepository implements TodoRepository
{
    public function getAll(): array
    {
        return TodoModel::all()->map(fn($todo)=>new Todo(
                $todo->id,
                $todo->title,
                $todo->description,
                $todo->isCompleted,
                $todo->image,
                $todo->created_at,
                $todo->updated_at
            )
        )->toArray();
    }

    public function getById(int $id): Todo
    {
        return TodoModel::find($id)->toArray();
    }

    public function create(Todo $todo): Todo
    {
        return TodoModel::create($todo->toArray());
    }

    public function update(Todo $todo): Todo
    {
        return TodoModel::find($todo->getId())->update($todo->toArray());
    }

    public function delete(int $id): void
    {
        TodoModel::find($id)->delete();
    }
}
