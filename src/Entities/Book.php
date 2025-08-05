<?php

declare(strict_types=1);

namespace App\Entities;

class Book extends Publication
{
    private string $isbn;
    private string $gender;
    private int $edition;

    public function __construct(
        int $id,
        string $title,
        string $description,
        \DateTime $publication_date,
        Author $author,
        string $isbn,
        string $gender,
        int $edition
    ) {
        parent::__construct($id, $title, $description, $publication_date, $author);
        $this->isbn = $isbn;
        $this->gender = $gender;
        $this->edition = $edition;
    }

    /* Getters */

    public function getIsbn(): string { return $this->isbn; }
    public function getGender(): string { return $this->gender; }
    public function getEdition(): int { return $this->edition; }

    /* Setters */

    public function setIsbn(string $isbn): void { $this->isbn = $isbn; }
    public function setGender(string $gender): void { $this->gender = $gender; }
    public function setEdition(int $edition): void { $this->edition = $edition; }
}
