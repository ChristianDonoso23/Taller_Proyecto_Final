<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Config\Database;
use App\Entities\Author;
use App\Entities\Book;
use PDO;

class BookRepository implements RepositoryInterface
{
    private PDO $db;
    private AuthorRepository $authorRepo;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->authorRepo = new AuthorRepository();
    }

    private function hydrate(array $row): Book
    {
        $author = new Author(
            (int)$row['author_id'],
            $row['first_name'],
            $row['last_name'],
            $row['username'],
            $row['email'],
            'temporal',
            $row['orcid'],
            $row['affiliation']
        );

        $ref = new \ReflectionClass($author);
        $property = $ref->getProperty('password');
        $property->setAccessible(true);
        $property->setValue($author, $row['password']);

        return new Book(
            (int)$row['publication_id'],
            $row['title'],
            $row['description'] ?? '',
            new \DateTime($row['publication_date']),
            $author,
            $row['ISBN'],
            $row['gender'],
            (int)$row['edition'],
        );
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("CALL sp_book_list()");
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();
        $out = [];
        foreach ($rows as $row) {
            $out[] = $this->hydrate($row);
        }
        return $out;
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Book) {
            throw new \InvalidArgumentException('Expected instance of Book');
        }

        $stmt = $this->db->prepare("CALL sp_create_book(:title, :description, :pub_date, :author_id, :isbn, :gender, :edition)");
        $ok = $stmt->execute(
            [
                'title' => $entity->getTitle(),
                'description' => $entity->getDescription(),
                'pub_date' => $entity->getPublicationDate()->format('Y-m-d'),
                'author_id' => $entity->getAuthor()->getId(),
                'isbn' => $entity->getIsbn(),
                'gender' => $entity->getGender(),
                'edition' => $entity->getEdition()
            ]
        );

        if ($ok) {
            $row = $stmt->fetch();
            $stmt->closeCursor();
            if ($row && isset($row['pub_id'])) {
                $entity->setId((int)$row['pub_id']); // Actualiza ID en objeto
            }
            return true;
        }
        $stmt->closeCursor();
        return false;
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->db->prepare("CALL sp_find_book(:id)");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        $stmt->closeCursor();
        return $row ? $this->hydrate($row) : null;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof Book) {
            throw new \InvalidArgumentException('Expected instance of Book');
        }

        $stmt = $this->db->prepare("CALL sp_update_book(:id, :title, :description, :pub_date, :author_id, :isbn, :gender, :edition)");
        $ok = $stmt->execute(
            [
                'id' => $entity->getId(),
                'title' => $entity->getTitle(),
                'description' => $entity->getDescription(),
                'pub_date' => $entity->getPublicationDate()->format('Y-m-d'),
                'author_id' => $entity->getAuthor()->getId(),
                'isbn' => $entity->getIsbn(),
                'gender' => $entity->getGender(),
                'edition' => $entity->getEdition()
            ]
        );

        if ($ok) {
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("CALL sp_delete_book(:id)");
        $ok = $stmt->execute(['id' => $id]);
        if ($ok) {
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }
}
