<?php

namespace Domain\Todo\Entities;

class Todo
{
    private int $id;
    private string $title;
    private string $description;
    private string $dueDate;
    private string $priority;
    private ?string $image;
    private bool $isCompleted;
    
    public function __construct(
        string $title,
        string $description,
        string $dueDate,
        string $priority,
        ?string $image = null
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->dueDate = $dueDate;
        $this->priority = $priority;
        $this->image = $image;
        $this->isCompleted = false;
    }
    
    // Add getters and setters
} 