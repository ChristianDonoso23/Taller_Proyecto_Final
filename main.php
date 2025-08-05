<?php

declare(strict_types=1);

// Autoload de Composer
require_once __DIR__ . '/vendor/autoload.php';

use App\Repositories\BookRepository;
use App\Repositories\AuthorRepository;

try {
    echo "=== LIBROS ===" . PHP_EOL;
    $bookRepo = new BookRepository();
    $books = $bookRepo->findAll();

    foreach ($books as $book) {
        echo "Título: " . $book->getTitle() . PHP_EOL;
        echo "Autor: " . $book->getAuthor()->getFirstName() . " " . $book->getAuthor()->getLastName() . PHP_EOL;
        echo "ISBN: " . $book->getIsbn() . PHP_EOL;
        echo "Género: " . $book->getGender() . PHP_EOL;
        echo "Edición: " . $book->getEdition() . PHP_EOL;
        echo str_repeat("─", 40) . PHP_EOL;
    }

    echo PHP_EOL . "=== AUTORES ===" . PHP_EOL;
    $authorRepo = new AuthorRepository();
    $authors = $authorRepo->findAll();

    foreach ($authors as $author) {
        echo "ID: " . $author->getId() . PHP_EOL;
        echo "Nombre: " . $author->getFirstName() . " " . $author->getLastName() . PHP_EOL;
        echo "Username: " . $author->getUsername() . PHP_EOL;
        echo "Email: " . $author->getEmail() . PHP_EOL;
        echo "ORCID: " . $author->getOrcid() . PHP_EOL;
        echo "Afiliación: " . $author->getAffiliation() . PHP_EOL;
        echo str_repeat("-", 40) . PHP_EOL;
    }

    // Aquí probamos update() de AuthorRepository
    echo PHP_EOL . "=== PRUEBA DE ACTUALIZACIÓN DE AUTOR ===" . PHP_EOL;

    // Obtener autor con ID 1
    $author = $authorRepo->findById(1);

    if ($author === null) {
        echo "Autor con ID 1 no encontrado." . PHP_EOL;
    } else {
        // Modificar datos
        $author->setFirstName("Christian");
        $author->setLastName("Donoso");
        $author->setEmail("christian.donoso@ute.edu.ec");

        // Guardar cambios
        $success = $authorRepo->update($author);

        if ($success) {
            echo "Autor actualizado correctamente." . PHP_EOL;
        } else {
            echo "Error al actualizar autor." . PHP_EOL;
        }
    }

} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
