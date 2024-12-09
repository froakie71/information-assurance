<?php

namespace App\Domain\Todo;

class Todo
{
    private int $id;
    private ?string $title;
    private ?string $description;
    private ?bool $isCompleted;
    private ?string $image;
    private ?string $createdAt;
    private ?string $updatedAt;

    public function __construct(?int $id, ?string $title, ?string $description, ?bool $isCompleted, ?string $image, ?string $createdAt, ?string $updatedAt)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->isCompleted = $isCompleted;
        $this->image = $image;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'isCompleted' => $this->isCompleted,
            'image' => $this->image,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getIsCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }
}
