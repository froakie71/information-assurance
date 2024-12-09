<?php

namespace App\Domain\Todo;

interface TodoRepository
{
    public function getAll(): array;
    public function getById(int $id): Todo;
    public function create(Todo $todo): Todo;
    public function update(Todo $todo): Todo;
    public function delete(int $id): void;
}
