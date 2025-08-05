<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Config\Database;
use App\Entities\Author;
use App\Entities\Article;
use PDO;

class ArticleRepository implements RepositoryInterface
{
    private PDO $db;
    private AuthorRepository $authorRepo;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->authorRepo = new AuthorRepository();
    }

    private function hydrate(array $row): Article
    {
        $author = new Author(
            (int)$row['author_id'],
            $row['first_name'],
            $row['last_name'],
            '',
            '',
            'temporal',
            '',    
            ''       
        );

        return new Article(
            (int)$row['publication_id'],
            $row['title'],
            $row['description'] ?? '',
            new \DateTime($row['publication_date']),
            $author,
            $row['doi'] ?? '',
            $row['magazine'] ?? ''
        );
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("CALL sp_article_list()");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        $out = [];
        foreach ($rows as $row) {
            $out[] = $this->hydrate($row);
        }
        return $out;
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->db->prepare("CALL sp_find_article(:id)");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $row ? $this->hydrate($row) : null;
    }

    public function create(object $entity): bool
    {
        if (!$entity instanceof Article) {
            throw new \InvalidArgumentException('Expected instance of Article');
        }

        $stmt = $this->db->prepare("CALL sp_create_article(
            :title, :description, :pub_date, :author_id, 
            :doi, :magazine
        )");

        $ok = $stmt->execute([
            'title' => $entity->getTitle(),
            'description' => $entity->getDescription(),
            'pub_date' => $entity->getPublicationDate()->format('Y-m-d'),
            'author_id' => $entity->getAuthor()->getId(),
            'doi' => $entity->getDoi(),
            'magazine' => $entity->getJournal()
        ]);

        if ($ok) {
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    public function update(object $entity): bool
    {
        if (!$entity instanceof Article) {
            throw new \InvalidArgumentException('Expected instance of Article');
        }

        $stmt = $this->db->prepare("CALL sp_update_article(
            :id, :title, :description, :pub_date, :author_id, 
            :doi, :magazine
        )");

        $ok = $stmt->execute([
            'id' => $entity->getId(),
            'title' => $entity->getTitle(),
            'description' => $entity->getDescription(),
            'pub_date' => $entity->getPublicationDate()->format('Y-m-d'),
            'author_id' => $entity->getAuthor()->getId(),
            'doi' => $entity->getDoi(),
            'magazine' => $entity->getJournal(),
        ]);

        if ($ok) {
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("CALL sp_delete_article(:id)");
        $ok = $stmt->execute(['id' => $id]);
        if ($ok) {
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }
}
