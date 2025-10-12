<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Entity\Game;
use App\Domain\Repository\GameRepositoryInterface;
use App\Domain\Repository\WordRepositoryInterface;

/**
 * Clase para coordinar la logica de las partidas del juego.
 * @author prorix
 * @version 4.2.3
 **/
final class GameService
{
    /**
     * Metodo que crea el servicio con sus dependencias.
     * @param games repositorio encargado de las partidas.
     * @param words repositorio encargado de las palabras.
     * @param maxAttempts limite de intentos permitidos.
     * @return void
     **/
    public function __construct(
        private GameRepositoryInterface $games,
        private WordRepositoryInterface $words,
        private int $maxAttempts = 6
    ){}

    /**
     * Metodo que inicia una nueva partida.
     * @return Game
     **/
    public function startGame(): Game
    {
        $id = bin2hex(random_bytes(8));
        $word = $this->words->randomWord();
        $game = new Game($id, $word, [], $this->maxAttempts);
        $this->games->save($game);
        return $game;
    }

    /**
     * Metodo que carga una partida existente.
     * @param id identificador de la partida buscada.
     * @return Game partida (si existe)
     **/
    public function loadGame(string $id): ?Game
    {
        return $this->games->find($id);
    }

    /**
     * Metodo que procesa un intento dentro de una partida.
     * @param id identificador de la partida.
     * @param letter letra que quiere adivinarse.
     * @return Game actializacion en la partida
     **/
    public function guess(string $id, string $letter): ?Game
    {
        $game = $this->loadGame($id);
        if (!$game) return null;
        $game->guess($letter);
        if ($game->getStatus() === Game::STATUS_IN_PROGRESS) {
            $this->games->save($game);
        } else {
            $this->games->delete($id);
        }
        return $game;
    }
}
