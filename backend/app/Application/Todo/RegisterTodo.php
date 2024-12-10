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
    public function findCompleted(): array
    {
        return $this->todoRepository->findCompleted();
    }
    public function findPending(): array
    {
        return $this->todoRepository->findPending();
    }
}
