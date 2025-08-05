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

        $payLoad = json_decode(file_get_contents('php://input'), true);

        if ($method === 'POST') {
            $author = $this->authorRepository->findById((int)$payLoad['author']) ?? null;
            if (!$author) {
                http_response_code(400);
                echo json_encode(['error' => 'Autor no encontrado']);
                return;
            }

            $article = new Article(
                0,
                $payLoad['title'],
                $payLoad['description'] ?? '',
                new \DateTime($payLoad['publication_date'] ?? 'now'),
                $author,
                $payLoad['doi'] ?? '',
                $payLoad['journal'] ?? ''
            );

            $success = $this->articleRepository->create($article);

            echo json_encode(['success' => $success]);
            return;
        }

        // Opcional: manejar PUT, DELETE si quieres

        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
    }
}
