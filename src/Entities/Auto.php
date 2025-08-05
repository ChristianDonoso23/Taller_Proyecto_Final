<?php

declare(strict_types=1);

namespace App\Entities;

abstract class Auto
{
    private int $id;
    private string $marca;
    private string $modelo;
    private string $color;
    private int $anio;

    public function __construct(
        int $idC,
        string $marcaC,
        string $modeloC,
        string $colorC,
        int $anioC
    ) {
        $this->id = $idC;
        $this->marca = $marcaC;
        $this->modelo = $modeloC;
        $this->color = $colorC;
        $this->anio = $anioC;
    }

    /*Getters*/
    public function getId(): int
    {
        return $this->id;
    }
    public function getMarca(): string
    {
        return $this->marca;
    }
    public function getModelo(): string
    {
        return $this->modelo;
    }
    public function getColor(): string
    {
        return $this->color;
    }
    public function getAnio(): int
    {
        return $this->anio;
    }
    /*Setters*/
    public function setId(int $idIn): void
    {
        $this->id = $idIn;
    }

    public function setMarca(string $marcaIn): void
    {
        $marcaTemp = trim($marcaIn);
        if ($marcaTemp === '') {
            throw new \InvalidArgumentException('La marca no debe estar vacía.');
        }
        $this->marca = $marcaTemp;
    }

    public function setModelo(string $modeloIn): void
    {
        $modeloTemp = trim($modeloIn);
        if ($modeloTemp === '') {
            throw new \InvalidArgumentException('El modelo no debe estar vacío.');
        }
        $this->modelo = $modeloTemp;
    }

    public function setColor(string $colorIn): void
    {
        $colorTemp = trim($colorIn);
        if ($colorTemp === '') {
            throw new \InvalidArgumentException('El color no debe estar vacío.');
        }
        $this->color = $colorTemp;
    }

    public function setAnio(int $anioIn): void
    {
        if ($anioIn < 1900 || $anioIn > (int)date('Y') + 1) 
        {
            throw new \InvalidArgumentException('El año no puede ser menor a 1900 ni mayor al año actual.');
        }
        $this->anio = $anioIn;
    }

    abstract public function getInfo(): string;
}
