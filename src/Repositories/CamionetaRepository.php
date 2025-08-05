<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Config\Database;
use App\Entities\Camioneta;
use PDO;

class CamionetaRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function hydrate(array $row): Camioneta
    {
        return new Camioneta(
            (int)$row['id'],
            $row['marca'],
            $row['modelo'],
            $row['color'],
            (int)$row['anio'],
            (int)$row['cabinas'],
            (float)$row['capacidad_carga']
        );
    }

    public function findAll(): array{
        $stmt = $this->db->query("CALL sp_camioneta_list()");
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();
        $out = [];
        foreach ($rows as $row) {
            $out[] = $this->hydrate($row);
        }
        return $out;
    }

    public function create(object $entity): bool{
        $stmt = $this->db->prepare("CALL sp_camioneta_create(:marca, :modelo, :color, :anio, :cabinas, :capacidad_carga)");
        $params = [
            ':marca' => $entity->getMarca(),
            ':modelo' => $entity->getModelo(),
            ':color' => $entity->getColor(),
            ':anio' => $entity->getAnio(),
            ':cabinas' => $entity->getCabinas(),
            ':capacidad_carga' => $entity->getCapacidadCarga()
        ];
        $ok = $stmt->execute($params);
        if ($ok) {
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    public function findById(int $id): ?object{
        $stmt = $this->db->prepare("CALL sp_camioneta_get(:id)");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if ($row) {
            return $this->hydrate($row);
        }
        return null;

    }

    public function update(object $entity): bool{
        if (!$entity instanceof Camioneta) {
            throw new \InvalidArgumentException('Expected instance of Camioneta');
        }

        $stmt = $this->db->prepare("CALL sp_camioneta_update(:id, :marca, :modelo, :color, :anio, :cabinas, :capacidad_carga)");
        $params = [
            ':id' => $entity->getId(),
            ':marca' => $entity->getMarca(),
            ':modelo' => $entity->getModelo(),
            ':color' => $entity->getColor(),
            ':anio' => $entity->getAnio(),
            ':cabinas' => $entity->getCabinas(),
            ':capacidad_carga' => $entity->getCapacidadCarga()
        ];
        $ok = $stmt->execute($params);
        if ($ok) {
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }

    public function delete(int $id): bool{
        $stmt = $this->db->prepare("CALL sp_camioneta_delete(:id)");
        $ok = $stmt->execute([':id' => $id]);
        if ($ok) {
            $stmt->fetch();
        }
        $stmt->closeCursor();
        return $ok;
    }
}
