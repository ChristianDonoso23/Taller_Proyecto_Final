<?php

declare(strict_types=1);

namespace App\Entities;

class Camioneta extends Auto
{
    private int $cabinas;
    private float $capacidadCarga;

    public function __construct(
        int $idC,
        string $marcaC,
        string $modeloC,
        string $colorC,
        int $anioC,
        int $cabinasC,
        float $capacidadCargaC
    ) {
        parent::__construct($idC, $marcaC, $modeloC, $colorC, $anioC);
        $this->cabinas = $cabinasC;
        $this->capacidadCarga = $capacidadCargaC;
    }

    /*Getters*/
    public function getCabinas(): int
    {
        return $this->cabinas;
    }
    public function getCapacidadCarga(): float
    {
        return $this->capacidadCarga;
    }

    /*Setters*/
    public function setCabinas(int $cabinasIn): void
    {
        $this->cabinas = $cabinasIn;
    }

    public function setCapacidadCarga(float $capacidadCargaIn): void
    {
        $this->capacidadCarga = $capacidadCargaIn;
    }

    public function getInfo(): string
    {
        return "Auto: ".$this->getMarca()
                ." Modelo: ".$this->getModelo()
                ." Color: ".$this->getColor()
                ." Año: ".$this->getAnio()
                ." Cabinas: ".$this->cabinas
                ." Capacidad de Carga: ".$this->capacidadCarga." kg.";
    }
}