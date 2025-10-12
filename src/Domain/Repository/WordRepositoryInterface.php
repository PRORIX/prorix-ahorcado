<?php
declare(strict_types=1);

namespace App\Domain\Repository;

/**
 * Clase para definir el contrato de palabras disponibles.
 * @author prorix
 * @version 4.2.3
 **/
interface WordRepositoryInterface
{
    /**
     * Metodo que obtiene una palabra aleatoria.
     * @return string palabra elegida aleatoriamente
     **/
    public function randomWord(): string;
}
