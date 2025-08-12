<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Author;
use App\Repositories\AuthorRepository;

class AuthorController
{
    private AuthorRepository $authorRepository;

    public function __construct()
    {
        $this->authorRepository = new AuthorRepository();
    }

    private function authorToArray(Author $author): array
    {
        return [
            'id' => $author->getId(),
            'first_name' => $author->getFirstName(),
            'last_name' => $author->getLastName(),
            'username' => $author->getUsername(),
            'password' => $author->getPassword(),
            'email' => $author->getEmail(),
            'orcid' => $author->getOrcid(),
            'affiliation' => $author->getAffiliation()
            // omitimos password por seguridad
        ];
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];
        $payload = json_decode(file_get_contents('php://input'), true);

        if ($method === 'GET') {
            if (isset($_GET['id'])) {
                $author = $this->authorRepository->findById((int)$_GET['id']);
                echo json_encode($author ? $this->authorToArray($author) : null);
            } else {
                $list = array_map(
                    fn(Author $author) => $this->authorToArray($author),
                    $this->authorRepository->findAll()
                );
                echo json_encode($list);
            }
            return;
        }

        if ($method === 'POST') {
            try {
                $author = new Author(
                    0,
                    $payload['first_name'],
                    $payload['last_name'],
                    $payload['username'],
                    $payload['email'],
                    $payload['password'], // la entidad se encargará de hashear
                    $payload['orcid'] ?? '',
                    $payload['affiliation'] ?? ''
                );

                $success = $this->authorRepository->create($author);
                echo json_encode(['success' => $success]);
            } catch (\Throwable $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            return;
        }

        if ($method === 'PUT') {
            try {
                if (!isset($payload['id'])) {
                    throw new \InvalidArgumentException('ID es requerido para actualizar');
                }

                $author = new Author(
                    (int)$payload['id'],
                    $payload['first_name'],
                    $payload['last_name'],
                    $payload['username'],
                    $payload['email'],
                    $payload['password'],
                    $payload['orcid'] ?? '',
                    $payload['affiliation'] ?? ''
                );

                $success = $this->authorRepository->update($author);
                echo json_encode(['success' => $success]);
            } catch (\Throwable $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            return;
        }

        if ($method === 'DELETE') {
            try {
                if (!isset($_GET['id'])) {
                    throw new \InvalidArgumentException('ID es requerido para eliminar');
                }
                $success = $this->authorRepository->delete((int)$_GET['id']);
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
