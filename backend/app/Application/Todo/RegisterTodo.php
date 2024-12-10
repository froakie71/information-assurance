<?php

namespace App\Application\Todo;
use App\Domain\Todo\TodoRepository;


class RegisterTodo
{
    public function __construct(private TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function findall(): array
    {
        return $this->todoRepository->getAll();
    }
}
