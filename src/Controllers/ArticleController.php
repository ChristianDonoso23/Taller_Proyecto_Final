<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Article;
use App\Repositories\AuthorRepository;
use App\Repositories\ArticleRepository;

class ArticleController
{
    private ArticleRepository $articleRepository;
    private AuthorRepository $authorRepository;

    public function __construct()
    {
        $this->articleRepository = new ArticleRepository();
        $this->authorRepository = new AuthorRepository();
    }

    public function articleToArray(Article $article): array
    {
        return [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'description' => $article->getDescription(),
            'publication_date' => $article->getPublicationDate()->format('Y-m-d'),
            'author' => [
                'id' => $article->getAuthor()->getId(),
                'first_name' => $article->getAuthor()->getFirstName(),
                'last_name' => $article->getAuthor()->getLastName()
            ],
            'doi' => $article->getDoi(),
            'journal' => $article->getJournal()
        ];
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $article = $this->articleRepository->findById((int)$_GET['id']);
                echo json_encode($article ? $this->articleToArray($article) : null);
            } else {
                $list = array_map(
                    fn(Article $article) => $this->articleToArray($article),
                    $this->articleRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);

        if ($method === 'POST') {
            $author = $this->authorRepository->findById((int)$payload['author']) ?? null;
            if (!$author) {
                http_response_code(400);
                echo json_encode(['error' => 'Autor no encontrado']);
                return;
            }

            $article = new Article(
                0,
                $payload['title'],
                $payload['description'] ?? '',
                new \DateTime($payload['publication_date'] ?? 'now'),
                $author,
                $payload['doi'] ?? '',
                $payload['journal'] ?? ''
            );

            $success = $this->articleRepository->create($article);

            echo json_encode(['success' => $success]);
            return;
        }

        if ($method === 'PUT') {
            try {
                if (!isset($payload['id'])) {
                    throw new \InvalidArgumentException('ID es requerido para actualizar');
                }

                $existing = $this->articleRepository->findById((int)$payload['id']);
                if (!$existing) {
                    http_response_code(404);
                    echo json_encode(['error' => 'Artículo no encontrado']);
                    return;
                }

                if (isset($payload['title'])) $existing->setTitle($payload['title']);
                if (isset($payload['description'])) $existing->setDescription($payload['description']);
                if (isset($payload['publication_date'])) {
                    $existing->setPublicationDate(new \DateTime($payload['publication_date']));
                }
                if (isset($payload['doi'])) $existing->setDoi($payload['doi']);
                if (isset($payload['journal'])) $existing->setJournal($payload['journal']);
                if (isset($payload['author'])) {
                    $author = $this->authorRepository->findById((int)$payload['author']);
                    if ($author) $existing->setAuthor($author);
                }

                $success = $this->articleRepository->update($existing);
                echo json_encode(['success' => $success]);
            } catch (\Throwable $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            return;
        }

        if ($method === 'DELETE') {
            try {
                if (!isset($payload['id'])) {
                    throw new \InvalidArgumentException('ID es requerido para eliminar');
                }

                $success = $this->articleRepository->delete((int)$payload['id']);
                echo json_encode(['success' => $success]);
            } catch (\Throwable $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
    }
}
