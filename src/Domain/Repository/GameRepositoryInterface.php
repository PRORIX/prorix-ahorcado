<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Game;

/**
 * Clase para definir almacenamiento de partidas.
 * @author prorix
 * @version 4.2.3
 **/
interface GameRepositoryInterface
{
    /**
     * Metodo que persiste una partida.
     * @param game instancia a guardar.
     * @return void partida guardada (o error)
     **/
    public function save(Game $game): void;

    /**
     * Metodo que busca una partida por identificador.
     * @param id identificador solicitado.
     * @return ?Game partida (si existe)
     **/
    public function find(string $id): ?Game;
    /**
     * Metodo que elimina una partida almacenada.
     * @param id identificador que se debe eliminar.
     * @return void partida eliminada (o error)
     **/
    public function delete(string $id): void;
}
